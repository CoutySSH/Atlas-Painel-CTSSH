<?php


echo "\r\n<!DOCTYPE html>\r\n<html lang=\"pt-br\">\r\n  <head>\r\n    <!-- Required meta tags -->\r\n    <meta charset=\"utf-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">\r\n    ";
error_reporting(0);
session_start();
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$sql = "SELECT * FROM configs";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $nomepainel = $row["nomepainel"];
        $logo = $row["logo"];
        $icon = $row["icon"];
    }
}
echo "    <!-- plugins:css -->\r\n    <link rel=\"stylesheet\" href=\"../atlas-assets/vendors/mdi/css/materialdesignicons.min.css\">\r\n    <link rel=\"stylesheet\" href=\"../atlas-assets/vendors/css/vendor.bundle.base.css\">\r\n    <!-- endinject -->\r\n    <link rel=\"stylesheet\" href=\"../atlas-assets/css/style.css\">\r\n    <!-- End layout styles -->\r\n    <link rel=\"shortcut icon\" href=\"";
echo $icon;
echo "\" />\r\n  </head>\r\n  <body>\r\n  ";
include "headeradmin2.php";
date_default_timezone_set("America/Sao_Paulo");
$data = date("Y-m-d H:i:s");
$slq = "SELECT * FROM limiter";
$result = mysqli_query($conn, $slq);
echo "<script src=\"https://code.jquery.com/jquery-3.5.1.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js\"></script>\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\">\r\n<div class=\"app-content content\">\r\n    <div class=\"content-overlay\"></div>\r\n    <div class=\"content-wrapper\">\r\n        <section id=\"basic-datatable\">\r\n            <div class=\"row\">\r\n             \r\n                <div class=\"col-12\">\r\n                    <div class=\"card\">\r\n                        <div class=\"card-header\">\r\n                            <h4 class=\"card-title\">Lista Usuarios Ultrapassando</h4>\r\n                 \r\n                        </div>\r\n                        <script>\r\n\r\n\r\nif (window.innerWidth < 678) {\r\n\r\n    document.write('<div class=\"alert alert-warning\" role=\"alert\"> <strong>Atenção!</strong> Mova para lado para Fazer Alguma Ação! </div>');\r\n    window.setTimeout(function() {\r\n        \$(\".alert\").fadeTo(500, 0).slideUp(500, function(){\r\n            \$(this).remove(); \r\n        });\r\n    }, 3000);\r\n}\r\n\r\n</script>\r\n<div class=\"card-content\">\r\n                            <div class=\"card-body card-dashboard\">\r\n                                <!-- nao mostar o sroll -->\r\n                                \r\n                                <div class=\"table-responsive\" style=\" overflow: auto; overflow-y: hidden;\">\r\n                                    <table class=\"table zero-configuration\" id=\"myTable\">\r\n                                                <thead>\r\n                                                    <tr>\r\n                                                        <th>Usuario</th>\r\n                                                        <th>Limite</th>\r\n                                                        <th>Tempo</th>\r\n                                                        \r\n                                                        <th>Dono do Usuario</th>\r\n\r\n                                                    </tr>\r\n                                                </thead>\r\n                                                <tbody>\r\n                                                ";
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $usuario = $row["usuario"];
        $tempo = $row["tempo"];
        $sqlee = "SELECT * FROM ssh_accounts WHERE login = '" . $usuario . "'";
        $resultee = mysqli_query($conn, $sqlee);
        $row = mysqli_fetch_assoc($resultee);
        $byid = $row["byid"];
        $limite = $row["limite"];
        $sqleee = "SELECT * FROM accounts WHERE id = '" . $byid . "'";
        $resulteee = mysqli_query($conn, $sqleee);
        $row2 = mysqli_fetch_assoc($resulteee);
        $sqlefee = "SELECT * FROM onlines WHERE usuario = '" . $usuario . "'";
        $resultefee = mysqli_query($conn, $sqlefee);
        $rowfee = mysqli_fetch_assoc($resultefee);
        $quantidade = $rowfee["quantidade"];
        $dono = $row2["login"];
        if ($tempo == "Deletado") {
            $tempo = "<span style=\"color: #fff;\" class=\"badge badge-danger\">Suspenso Limite Ultrapassado</span>";
        }
        echo "<tr>\r\n                                                        <td>" . $usuario . "</td>\r\n                                                        <td><label class='badge badge-danger' style='color: #fff;'> " . $quantidade . " / " . $limite . " </label></td>\r\n                                                        <td>" . $tempo . "</td>\r\n                                                        <td>" . $dono . "</td>\r\n                                                        </td>\r\n                                                        ";
    }
}
echo "\r\n  </tbody>\r\n    </table>\r\n    </div>\r\n    </div>\r\n    </div>\r\n    \r\n\r\n                                        \r\n                    \r\n                          \r\n                                        \r\n                        \r\n    <!-- END: Content-->\r\n    <script src=\"cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js\"></script>\r\n    <script src=\"../app-assets/js/scripts/datatables/datatable.js\"></script>\r\n    <script>\r\n    \$('#myTable').DataTable({\r\n\r\n        /* traduzir somente */\r\n        \"language\": {\r\n            \"lengthMenu\": \"Mostrar _MENU_ registros por página\",\r\n            \"zeroRecords\": \"Nenhum registro encontrado\",\r\n            \"info\": \"Mostrando página _PAGE_ de _PAGES_\",\r\n            \"infoEmpty\": \"Nenhum registro disponível\",\r\n            \"infoFiltered\": \"(filtrado de _MAX_ registros no total)\",\r\n            \"search\": \"Pesquisar:\",\r\n            \"paginate\": {\r\n                \"first\": \"\",\r\n                \"last\": \"\",\r\n                \"next\": \"\",\r\n                \"previous\": \"\"\r\n            }\r\n        }\r\n    \r\n    });\r\n\r\n</script>\r\n<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n<!-- ajax -->\r\n<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js\" integrity=\"sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>\r\n";

?>