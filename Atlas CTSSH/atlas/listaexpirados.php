<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php error_reporting(0);
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    include('../atlas/conexao.php');
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    $sql = "SELECT * FROM configs";
    $result = $conn -> query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $nomepainel = $row["nomepainel"];
            $logo = $row["logo"];
            $icon = $row["icon"];
        }
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

  
?>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../atlas-assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../atlas-assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <link rel="stylesheet" href="../atlas-assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="<?php echo $icon; ?>" />
  </head>
  <body>
  <?php
include('header2.php');
date_default_timezone_set('America/Sao_Paulo');
$data = date('Y-m-d H:i:s');
    $slq = "SELECT * FROM ssh_accounts WHERE byid = '".$_SESSION['iduser']."' and expira < '$data'";
    $result = mysqli_query($conn, $slq);
      
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
                                <!-- nao mostar o sroll -->
                                <a class="btn btn-danger btn-rounded btn-fw" onclick="excluirTodos()">Excluir Todos</a>
                                <script>
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
                                    swal("Os Usuarios foram deletados com sucesso!", {
                                      icon: "success",
                                    });
                                    window.location.href = "deleteexpirados.php";
                                  } else {
                                    swal("Os Usuarios não foram deletados!");
                                  }
                                });
                                    }
                                </script>
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
                                                        <td>$senha</td>
                                                        <td>$limite</td>
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
                                                               
                                                                <a class="dropdown-item" onclick="excluir('.$id.')">Excluir</a>
                                                            </div>
                                                        </div>
                                                    </div></td>
                                                    </tr>';
                                                    }

                                                }
                                                ?>
<script>
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

                       