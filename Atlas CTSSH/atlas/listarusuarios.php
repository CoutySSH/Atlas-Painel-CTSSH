error_reporting(0);
session_start();
include('conexao.php');
include('header2.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    $sql = "SELECT * FROM ssh_accounts WHERE byid = '$_SESSION[iduser]' ORDER BY expira ASC";
    $result = $conn -> query($sql);
  
    $sql44 = "SELECT * FROM configs";
    $result44 = $conn -> query($sql44);
    while ($row44 = $result44->fetch_assoc()) {
      $deviceativo = $row44['deviceativo'];
    }
         
   if ($deviceativo == "1") {
    $limpardeviceids = '<button type="button" class="btn btn-outline-danger btn-lg btn-block" onclick="limpardeviceids()">Limpar Device IDs</button>';
     
  }
  $sql5 = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[iduser]'";
$sql5 = $conn->query($sql5);
$row = $sql5->fetch_assoc();
$validade = $row['expira'];
$tipo = $row['tipo'];
$_SESSION['tipodeconta'] = $row['tipo'];
//time zone
date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d H:i:s');
if ($_SESSION['tipodeconta'] == 'Credito') {
}else{
if ($validade < $hoje) {
    echo "<script>alert('Sua conta está vencida')</script>";
    echo "<script>window.location.href = '../home.php'</script>";
    unset($_POST['criaruser']);
    unset($_POST['usuariofin']);
    unset($_POST['senhafin']);
    unset($_POST['validadefin']);
}
}
if (!file_exists('../admin/suspenderrev.php')) {
  exit ("<script>alert('Token Invalido!');</script>");
}else{
  include_once 'suspenderrev.php';
  
}
if (!isset($_SESSION['sgdfsr43erfggfd4rgs3rsdfsdfsadfe']) || !isset($_SESSION['token']) || $_SESSION['tokenatual'] != $_SESSION['token'] || isset($_SESSION['token_invalido_']) && $_SESSION['token_invalido_'] === true) {
  if (function_exists('security')) {
      security();
  } else {
      echo "<script>alert('Token Inválido!');</script>";
      echo "<script>location.href='../index.php';</script>";

      $_SESSION['token_invalido_'] = true;
      exit;
  }
}
  ?>
  
   <script>
  function limpardeviceids() {
    swal({
    title: "Tem certeza?",
    text: "Você não poderá reverter isso!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      window.location.href = "limpardeviceids.php";
    } else {
      swal("Cancelado!");
    }
  });
  }
  </script>
      <script>
    
  
</script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout">
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <section id="basic-datatable">
            <div class="row">
             
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Lista de Usuarios</h4>
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
                                
                                <!-- nao mostar o sroll -->
                                <div class="table-responsive" style=" overflow: auto; overflow-y: hidden;">
                                    <table class="table zero-configuration" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>Usuario</th>
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
                                                if ($result->num_rows > 0) {
                                                    while($row = $result->fetch_assoc()) {
                                                        $id = $row['id'];
                                                        $login = $row['login'];
                                                        $senha = $row['senha'];
                                                        $limite = $row['limite'];
                                                        $validade = $row['expira'];
                                                        $status = $row['status'];
                                                        $deviceid = $row['deviceid'];
                                                        $deviceativo = $row['deviceativo'];
                                                        $categoria = $row['categoriaid'];
                                                        $suspenso = $row['mainid'];
                                                        $notas = $row['lastview'];
                                                        $expira = $row['expira'];
                                                        $uuid = $row['uuid'];
                                                        $expira = date('d/m/Y', strtotime($expira));
                                                        $expira2 = $expira;
                                                        $sql2 = "SELECT * FROM onlines WHERE usuario = '$login'";
                                                        $result2 = $conn -> query($sql2);
                                                        $row2 = $result2->fetch_assoc();
                                                        $usando = $row2['quantidade'];
                                                        $today = strtotime(date('Y-m-d')); // Convert today's date to a timestamp
                                                        $expiraTimestamp = strtotime(str_replace('/', '-', $expira2)); // Convert the given date to a timestamp
                                                        


                            if ($expira2 == date('d/m/Y', strtotime('+5 days'))) {
                              $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 5 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('+4 days'))) {
                              $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 4 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('+3 days'))) {
                              $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 3 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('+2 days'))) {
                              $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 2 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('+1 days'))) {
                              $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 1 dias</label>";
                            }

                            if ($expira2 == date('d/m/Y')) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expira Hoje</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-1 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou ontem</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-2 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou anteontem</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-3 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 3 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-4 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 4 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-5 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 5 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-6 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 6 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-7 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 7 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-8 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 8 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-9 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 9 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-10 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 10 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-11 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 11 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-12 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 12 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-13 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 13 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-14 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 14 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-15 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 15 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-16 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 16 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-17 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 17 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-18 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 18 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-19 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 19 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-20 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 20 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-21 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 21 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-22 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 22 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-23 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 23 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-24 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 24 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-25 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 25 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-26 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 26 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-27 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 27 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-28 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 28 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-29 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 29 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-30 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 30 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-31 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 31 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-32 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 32 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-33 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 33 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-34 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 34 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-35 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 35 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-36 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 36 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-37 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 37 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-38 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 38 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-39 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 39 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-40 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 40 dias</label>";
                            }

                                                        $sql2 = "SELECT * FROM onlines WHERE usuario = '$login'";
                                                        $result2 = $conn -> query($sql2);
                                                        $row2 = $result2->fetch_assoc();
                                                        $usando = $row2['quantidade'];

                                                        
                                                        
                                                        if ($deviceativo == "1") {
                                                            $deviceativo = "Sim";
                                                        }else{
                                                            $deviceativo = "Não";
                                                        }
                                                        if ($deviceid == "") {
                                                            $deviceid = "Nenhum";
                                                        }
                                                        if ($uuid == "") {
                                                        }else{
                                                          $login = "<label class='badge badge-success' style='color: #000000;'>V2Ray</label> $login";
                                                        }
                                                        echo "<tr>
                                                        <td>$login</td>
                                                        ";
                                                        if ($usando == 0) {
                                                            echo "<td>".$limite."</td>";
                                                          }else{
                                                            if ($usando > $limite) {
                                                              echo "<td><label class='badge badge-danger' style='color: #fff;'> $usando / $limite </label></td>";
                                                            }else{
                                                              echo "<td><label class='badge badge-success' style='color: #fff;'> $usando / $limite </label></td>";
                                                            }
                                                          }
                                                        echo "
                                                        <td>$categoria</td>
                                                        <td>$expira</td>
                                                        ";
                                                        switch ($suspenso) {
                                                          case 'Suspenso':
                                                            echo "<td class='text-danger'>Suspenso</td>";
                                                            break;
                                                          case 'Limite Ultrapassado':
                                                            echo "<td class='text-danger'>Limite Ultrapassado</td>";
                                                            break;
                                                          default:
                                                            if ($status == 'Online') {
                                                              echo "<td class='text-success'>Online</td>";
                                                            } else {
                                                              echo "<td class='text-alert'>Offline</td>";
                                                            }
                                                            break;
                                                        }
                                                        echo "<td>$notas</td>";

                                                        echo '
                                                        <td><div class="btn-group mb-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle mr-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Ações
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="editarlogin.php?id='.$id.'">Editar</a>
                                                                <a class="dropdown-item" onclick="limpardeviceids('.$id.')">Limpar Device ID</a>
                                                                <a class="dropdown-item" onclick="renovardias('.$id.')">Renovar Dias</a>
                                                                <a class="dropdown-item" onclick="reativar('.$id.')">Reativar</a>
                                                                <a class="dropdown-item" onclick="suspender('.$id.')">Suspender</a>
                                                                <a class="dropdown-item" onclick="excluir('.$id.')">Excluir</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </td>
                                                    </tr>';
                                                    }

                                                }
                                                ?>
                                                <script>
    function limpardeviceids($id) {
    /* confirma */
    swal({
    title: "Tem certeza?",
    text: "Você deseja limpar o Device ID do usuário?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    })
  .then((willDelete) => {
    if (willDelete) {
      /* faz uma requisiçao com ajax */
        $.ajax({
            url: 'deviceid.php?id='+$id,
            type: 'GET',
            success: function(data){
              //remove /n
              data = data.replace(/(\r\n|\n|\r)/gm, "");
               if (data == 'deletado com sucesso') {
                swal("Sucesso!", "Device ID limpo com sucesso!", "success");
               }else{
                swal("Erro!", "Erro ao limpar Device ID!", "error");
               }
            }
        });
    } else {
      swal("Cancelado!");
    }
  });
  }
    function renovardias($id) {
        /* confirma */
        swal({
        title: "Tem certeza?",
        text: "Você deseja renovar os dias do usuário?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: 'renovardias.php?id='+$id,
                type: 'GET',
                success: function(data){
                    if (data == 'Renovado com Sucesso!') {
                        /* ao clicar atualiza pagina */
                        swal("Sucesso!", "Dias renovados com sucesso!", "success").then(function() {
                            location.reload();
                        });
                    }else{
                        swal("Erro!", "Erro ao renovar dias!", "error");
                    }
                }
            });
        } else {
            swal("Cancelado!");
        }
        });
    }
    function reativar($id) {
        /* confirma */
        swal({
        title: "Tem certeza?",
        text: "Você deseja reativar o usuário?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: 'reativar.php?id='+$id,
                type: 'GET',
                success: function(data){
                    if (data == 'reativado com sucesso') {
                        /* ao clicar atualiza pagina */
                        swal("Sucesso!", "Usuário reativado com sucesso!", "success").then(function() {
                            location.reload();
                        });
                    }else{
                        swal("Erro!", "Erro ao reativar usuário!", "error");
                    }
                }
            });
        } else {
            swal("Cancelado!");
        }
        });
    }
    function suspender($id) {
        /* confirma */
        swal({
        title: "Tem certeza?",
        text: "Você deseja suspender o usuário?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: 'suspender.php?id='+$id,
                type: 'GET',
                success: function(data){
                    if (data == 'erro no servidor') {
                        /* ao clicar atualiza pagina */
                        swal("Erro!", "Erro no servidor, verifique se o servidor está online ou se a senha está correta!", "error");
                    }else{

                    if (data == 'suspenso com sucesso') {
                        /* ao clicar atualiza pagina */
                        swal("Sucesso!", "Usuário suspenso com sucesso!", "success").then(function() {
                            location.reload();
                        });
                    }else{
                        swal("Erro!", "Erro ao suspender usuário!", "error");
                    }
                }
                }
            });
        } else {
            swal("Cancelado!");
        }
    });
}
function excluir($id) {
        /* confirma */
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
                url: 'excluiruser.php?id='+$id,
                type: 'GET',
                success: function(data){
                    if (data == 'excluido') {
                        /* ao clicar atualiza pagina */
                        swal("Sucesso!", "Usuário excluido com sucesso!", "success").then(function() {
                            location.reload();
                        });
                    }else{
                        swal("Erro!", "Erro ao excluir usuário!", "error");
                    }
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
    

                                        
                    
                          
                                        
                        
    <!-- END: Content-->
    <script src="cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="../app-assets/js/scripts/datatables/datatable.js"></script>
    <script>
    $('#myTable').DataTable({

        /* traduzir somente */
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
<!-- ajax -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
