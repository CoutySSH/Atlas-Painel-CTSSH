    error_reporting(0);
session_start();
include('../atlas/conexao.php');
include('headeradmin2.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

/*  $sql = "SELECT * FROM ssh_accounts WHERE byid = '$_SESSION[iduser]'";
          $result = $conn -> query($sql); */
          $sql = "SELECT accounts.login AS acc_login, ssh_accounts.login AS ssh_login, accounts.*, ssh_accounts.* FROM accounts INNER JOIN ssh_accounts ON accounts.id = ssh_accounts.byid WHERE status = 'Online'";
          $result = $conn->query($sql);
          
            /* $sql = "SELECT * FROM ssh_accounts WHERE status = 'Online'";
            $result = $conn -> query($sql);  */
            if (!file_exists('suspenderrev.php')) {
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
                            <h4 class="card-title">Lista de Onlines</h4>
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
                                                        <th>Dono</th>
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
                                                        $dono = $row['acc_login'];
                                                        $deviceid = $row['deviceid'];
                                                        $deviceativo = $row['deviceativo'];
                                                        $categoria = $row['categoriaid'];
                                                        $suspenso = $row['mainid'];
                                                        $notas = $row['lastview'];
                                                        $data = date('Y-m-d');
                                                        $diferenca = strtotime($validade) - strtotime($data);
                                                        $dias = floor($diferenca / (60 * 60 * 24));
                                                        if ($dias < 0) {
                                                            $dias = "Expirado";
                                                        }else{
                                                            $dias = $dias." Restantes";
                                                        }
                                                        
                                                        if ($deviceativo == "1") {
                                                            $deviceativo = "Sim";
                                                        }else{
                                                            $deviceativo = "Não";
                                                        }
                                                        if ($deviceid == "") {
                                                            $deviceid = "Nenhum";
                                                        }
                                                        echo "<tr>
                                                        <td>$login</td>
                                                        <td>$limite</td>
                                                        <td>$dono</td>
                                                        <td>$categoria</td>
                                                        <td>$dias</td>
                                                        ";
                                                        if ($suspenso == 'Suspenso') {
                                                            echo "<td class='text-danger'>Suspenso</td>";
                                                          }else{
                                                            if ($status == 'Online'){
                                                              echo "<td class='text-success'>Online</td>";
                                                            }else{
                                                              echo "<td class='text-alert'>Offline</td>";
                                                            }

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
                    if (data == 'renovado com sucesso') {
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
