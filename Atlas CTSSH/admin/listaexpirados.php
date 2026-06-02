<?php error_reporting(0);
if (session_status() == PHP_SESSION_NONE) { session_start(); }
set_time_limit(0);
ignore_user_abort(true);

date_default_timezone_set('America/Sao_Paulo');

include('../atlas/conexao.php');

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");

set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
include('Net/SSH2.php');

function montarConteudoSync($conn, $categoriaid)
{
    $categoriaid = (int)$categoriaid;
    $linhas = "";

    $stmt = mysqli_prepare($conn, "SELECT login, senha, expira, limite, uuid FROM ssh_accounts WHERE categoriaid = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $categoriaid);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $login = (string)($row['login'] ?? "");
                $senha = (string)($row['senha'] ?? "");
                $expira = (string)($row['expira'] ?? "");
                $uuid = (string)($row['uuid'] ?? "");
                $limite = (string)($row['limite'] ?? "0");

                $expiraFmt = date('Y-m-d H:i:s', strtotime($expira));
                $agora = date('Y-m-d H:i:s');
                $diferenca = strtotime($expiraFmt) - strtotime($agora);
                $dias = floor($diferenca / (60 * 60 * 24));

                $linhas .= $login . " " . $senha . " " . $dias . " " . $limite . " " . $uuid . "\n";
            }
        }
        mysqli_stmt_close($stmt);
    }
    return $linhas;
}

function buscarServidoresCategoria($conn, $categoriaid)
{
    $categoriaid = (int)$categoriaid;
    $servers = [];

    $stmt = mysqli_prepare($conn, "SELECT id, nome, ip, porta, usuario, senha, subid FROM servidores WHERE subid = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $categoriaid);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $servers[] = $r;
        }
        mysqli_stmt_close($stmt);
    }
    return $servers;
}

function sincronizarCategoriaViaSSH($conn, $categoriaid)
{
    $servers = buscarServidoresCategoria($conn, $categoriaid);

    $conteudo = montarConteudoSync($conn, $categoriaid);
    $nome = md5(uniqid(rand(), true));
    $nome = substr($nome, 0, 10) . ".txt";
    $b64 = base64_encode($conteudo);

    $sucess_servers = [];
    $failed_servers = [];
    $sucess = false;

    foreach ($servers as $srv) {
        $ip = (string)($srv['ip'] ?? "");
        $porta = isset($srv['porta']) && $srv['porta'] ? (int)$srv['porta'] : 22;
        $usuario = (string)($srv['usuario'] ?? "");
        $senha = (string)($srv['senha'] ?? "");
        $nomeSrv = (string)($srv['nome'] ?? $ip);

        if ($ip === "" || $usuario === "" || $senha === "") {
            $failed_servers[] = $nomeSrv;
            continue;
        }

        $tentativas = 0;
        $conectado = false;

        while ($tentativas < 2 && !$conectado) {
            $ssh = new Net_SSH2($ip, $porta);
            if ($ssh->login($usuario, $senha)) {
                $ssh->exec("echo '$b64' | base64 -d > /root/$nome");
                $ssh->exec("python3 /root/sincronizar.py $nome > /dev/null 2>/dev/null &");
                $ssh->disconnect();
                $conectado = true;
                $sucess = true;
                $sucess_servers[] = $nomeSrv;
            } else {
                $tentativas++;
            }
        }

        if (!$conectado) $failed_servers[] = $nomeSrv;
    }

    return [$sucess, $sucess_servers, $failed_servers];
}

if (isset($_GET['action']) && $_GET['action'] === 'sync_cat') {
    header('Content-Type: application/json; charset=utf-8');

    $categoriaid = isset($_GET['categoriaid']) ? (int)$_GET['categoriaid'] : 0;
    if ($categoriaid <= 0) {
        echo json_encode(['success' => false, 'error' => 'CATEGORIA_INVALIDA']);
        exit;
    }

    list($ok, $okServers, $badServers) = sincronizarCategoriaViaSSH($conn, $categoriaid);
    echo json_encode(['success' => (bool)$ok, 'categoriaid' => $categoriaid, 'sucess' => $okServers, 'failed' => $badServers]);
    exit;
}

$nomepainel = "";
$logo = "";
$icon = "";

$resultCfg = $conn->query("SELECT * FROM configs");
if ($resultCfg && $resultCfg->num_rows > 0) {
    while($rowCfg = $resultCfg->fetch_assoc()) {
        $nomepainel = $rowCfg["nomepainel"];
        $logo = $rowCfg["logo"];
        $icon = $rowCfg["icon"];
    }
}

$dataAgora = date('Y-m-d H:i:s');

$rowsExp = [];
$catsToSync = [];

$byid = isset($_SESSION['iduser']) ? (int)$_SESSION['iduser'] : 0;
$stmtSel = $conn->prepare("SELECT * FROM ssh_accounts WHERE byid = ? and expira < ?");
$stmtSel->bind_param("is", $byid, $dataAgora);
$stmtSel->execute();
$result = $stmtSel->get_result();
if ($result) {
    while ($r = $result->fetch_assoc()) {
        $rowsExp[] = $r;
        $cid = (int)($r['categoriaid'] ?? 0);
        if ($cid > 0) $catsToSync[$cid] = $cid;
    }
}
$stmtSel->close();

include('headeradmin2.php');

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../atlas-assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../atlas-assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../atlas-assets/css/style.css">
    <link rel="shortcut icon" href="<?php echo $icon; ?>" />
  </head>
  <body>
<script src="../app-assets/sweetalert.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

<script>
var CURRENT_PAGE = <?php echo json_encode($currentPage); ?>;
var CATS_TO_SYNC = <?php echo json_encode(array_values($catsToSync)); ?>;

function syncCategoria(cat, cb){
  try{
    $.ajax({
      url: CURRENT_PAGE + '?action=sync_cat&categoriaid=' + cat,
      type: 'GET',
      dataType: 'json',
      success: function(resp){ if(cb) cb(resp); },
      error: function(){ if(cb) cb({success:false}); }
    });
  }catch(e){
    if(cb) cb({success:false});
  }
}

function syncCategoriasLista(list, idx, done){
  if(!list || list.length === 0){ if(done) done(); return; }
  if(idx >= list.length){ if(done) done(); return; }
  var cat = list[idx];
  syncCategoria(cat, function(){
    syncCategoriasLista(list, idx+1, done);
  });
}

function excluirTodos() {
  swal({
    title: "Tem certeza?",
    text: "Uma vez deletado, você não poderá recuperar esses Usuarios!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: 'deleteexpirados.php',
        type: 'GET',
        success: function(){
          if(CATS_TO_SYNC && CATS_TO_SYNC.length){
            swal("Sincronizando servidor...", "Aguarde...", "info");
            syncCategoriasLista(CATS_TO_SYNC, 0, function(){
              swal("Sucesso!", "Os Usuarios foram deletados e sincronizados!", "success").then(function(){
                location.reload();
              });
            });
          }else{
            swal("Sucesso!", "Os Usuarios foram deletados com sucesso!", "success").then(function(){
              location.reload();
            });
          }
        },
        error: function(){
          swal("Erro!", "Erro ao excluir todos.", "error");
        }
      });
    } else {
      swal("Os Usuarios não foram deletados!");
    }
  });
}
</script>

<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout">
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Lista de Expirados</h4>
                        </div>

                        <script>
if (window.innerWidth < 678) {
    document.write('<div class="alert alert-warning" role="alert"> <strong>Atenção!</strong> Mova para lado para Fazer Alguma Ação! </div>');
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 3000);
}
</script>

                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <a class="btn btn-danger btn-rounded btn-fw" onclick="excluirTodos()">Excluir Todos</a>
                                <div class="table-responsive" style=" overflow: auto; overflow-y: hidden;">
                                    <table class="table zero-configuration" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>Usuario</th>
                                                        <th>Senha</th>
                                                        <th>Limite</th>
                                                        <th>categoria</th>
                                                        <th>Validade</th>
                                                        <th>Status</th>
                                                        <th>Notas</th>
                                                        <th>Editar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
<?php
if (count($rowsExp) > 0) {
    foreach ($rowsExp as $row) {
        $id = $row['id'];
        $login = $row['login'];
        $senha = $row['senha'];
        $limite = $row['limite'];
        $validade = $row['expira'];
        $status = $row['status'];
        $categoria = (int)$row['categoriaid'];
        $suspenso = $row['mainid'];
        $notas = $row['lastview'];

        $data = date('Y-m-d');
        $diferenca = strtotime($validade) - strtotime($data);
        $dias = floor($diferenca / (60 * 60 * 24));
        if ($dias < 0) $dias = "Expirado";
        else $dias = $dias." Restantes";

        echo "<tr>
                <td>$login</td>
                <td>$senha</td>
                <td>$limite</td>
                <td>$categoria</td>
                <td>$dias</td>
        ";

        if ($suspenso == 'Suspenso') {
            echo "<td class='text-danger'>Suspenso</td>";
        } else {
            if ($status == 'Online') echo "<td class='text-success'>Online</td>";
            else echo "<td class='text-alert'>Offline</td>";
        }

        echo "<td>$notas</td>";

        echo '
            <td><div class="btn-group mb-1">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle mr-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Ações
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" onclick="excluir('.$id.', '.$categoria.')">Excluir</a>
                </div>
            </div>
        </div>
            </td>
        </tr>';
    }
}
?>
<script>
function excluir($id, $categoriaid) {
    swal({
      title: "Tem certeza?",
      text: "Você deseja excluir o usuário?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: 'excluiruser.php?id=' + $id,
          type: 'GET',
          success: function(data){
            data = (data || "").toString().replace(/(\r\n|\n|\r)/gm, "").trim();
            if (data == 'excluido') {
              swal("Sucesso!", "Usuário excluido com sucesso!", "success").then(function() {
                swal("Sincronizando servidor...", "Aguarde...", "info");
                syncCategoria($categoriaid, function(){
                  location.reload();
                });
              });
            } else {
              swal("Erro!", "Erro ao excluir usuário!", "error");
            }
          },
          error: function(){
            swal("Erro!", "Erro ao excluir usuário!", "error");
          }
        });
      } else {
        swal("Cancelado!");
      }
    });
}
</script>

                                                </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

    <script src="cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="../app-assets/js/scripts/datatables/datatable.js"></script>
    <script>
    $('#myTable').DataTable({
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro disponível",
            "infoFiltered": "(filtrado de _MAX_ registros no total)",
            "search": "Pesquisar:",
            "paginate": {
                "first": "",
                "last": "",
                "next": "",
                "previous": ""
            }
        }
    });
    </script>

<script src="../app-assets/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

