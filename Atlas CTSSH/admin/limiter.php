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
include('headeradmin2.php');
date_default_timezone_set('America/Sao_Paulo');
$data = date('Y-m-d H:i:s');
    $slq = "SELECT * FROM limiter";
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
                            <h4 class="card-title">Lista Usuarios Ultrapassando</h4>
                 
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
                                                        <th>Tempo</th>
                                                        
                                                        <th>Dono do Usuario</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while($row = $result->fetch_assoc()) {
                                                        $usuario = $row["usuario"];
                                                        $tempo = $row["tempo"];
                                                        $sqlee = "SELECT * FROM ssh_accounts WHERE login = '$usuario'";
                                                        $resultee = mysqli_query($conn, $sqlee);
                                                        $row = mysqli_fetch_assoc($resultee);
                                                        $byid = $row["byid"];
                                                        $limite = $row["limite"];
                                                        $sqleee = "SELECT * FROM accounts WHERE id = '$byid'";
                                                        $resulteee = mysqli_query($conn, $sqleee);
                                                        $row2 = mysqli_fetch_assoc($resulteee);
                                                        $sqlefee = "SELECT * FROM onlines WHERE usuario = '$usuario'";
                                                        $resultefee = mysqli_query($conn, $sqlefee);
                                                        $rowfee = mysqli_fetch_assoc($resultefee);
                                                        $quantidade = $rowfee["quantidade"];
                                                        $dono = $row2["login"];
                                                        if ($tempo == 'Deletado') {
                                                            $tempo = '<span style="color: #fff;" class="badge badge-danger">Suspenso Limite Ultrapassado</span>';
                                                        }
                                                        echo "<tr>
                                                        <td>$usuario</td>
                                                        <td><label class='badge badge-danger' style='color: #fff;'> $quantidade / $limite </label></td>
                                                        <td>$tempo</td>
                                                        <td>$dono</td>
                                                        </td>
                                                        ";
                                                        
                                                    }

                                                }
                                                ?>

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

                       