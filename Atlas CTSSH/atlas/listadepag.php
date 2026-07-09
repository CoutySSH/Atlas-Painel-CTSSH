<?php


error_reporting(0);
session_start();
include "conexao.php";
include "header2.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$sql = "SELECT * FROM pagamentos  where byid = '" . $_SESSION["iduser"] . "' ";
$result = $conn->query($sql);
echo "<script src=\"https://code.jquery.com/jquery-3.5.1.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js\"></script>\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\">\r\n<div class=\"app-content content\">\r\n    <div class=\"content-overlay\"></div>\r\n    <div class=\"content-wrapper\">\r\n        <section id=\"basic-datatable\">\r\n            <div class=\"row\">\r\n             \r\n                <div class=\"col-12\">\r\n                    <div class=\"card\">\r\n                        <div class=\"card-header\">\r\n                            <h4 class=\"card-title\">Lista de Pagamentos</h4>\r\n                        </div>\r\n                        <script>\r\n\r\n\r\nif (window.innerWidth < 678) {\r\n\r\n    document.write('<div class=\"alert alert-warning\" role=\"alert\"> <strong>Atenção!</strong> Mova para lado para ver mais detalhes! </div>');\r\n    window.setTimeout(function() {\r\n        \$(\".alert\").fadeTo(500, 0).slideUp(500, function(){\r\n            \$(this).remove(); \r\n        });\r\n    }, 3000);\r\n}\r\n\r\n</script>\r\n                        <div class=\"card-content\">\r\n                            <div class=\"card-body card-dashboard\">\r\n                                <!-- nao mostar o sroll -->\r\n                                <div class=\"table-responsive\" style=\" overflow: auto; overflow-y: hidden;\">\r\n                                    <table class=\"table zero-configuration\" id=\"myTable\">\r\n                                                <thead>\r\n                                                    <tr>\r\n                                                    <th> Login </th>\r\n                                                    <th> Id do Pagamento </th>\r\n                                                    <th> Valor </th>\r\n                                                    <th> Detalhes </th>\r\n                                                    <th> Data e Hora </th>\r\n                                                    <th> Status </th>\r\n                                                    </tr>\r\n                                                </thead>\r\n                                                <tbody>\r\n                                                ";
while ($user_data = mysqli_fetch_assoc($result)) {
    if ($user_data["status"] == "Aprovado") {
        $status = "<i class='bx bxs-circle success font-small-1 mr-50'></i><span class='align-middle'>Aprovado</span>";
    } else {
        $status = "<i class='bx bxs-circle danger font-small-1 mr-50'></i><span class='align-middle'>Pendente</span>";
    }
    echo "<td>" . $user_data["login"] . "</td>";
    echo "<td>" . $user_data["idpagamento"] . "</td>";
    echo "<td>" . $user_data["valor"] . "</td>";
    echo "<td>" . $user_data["texto"] . "</td>";
    echo "<td>" . $user_data["data"] . "</td>";
    echo "<td>" . $status . "</td>";
    echo "</tr>";
}
echo "  </tbody>\r\n    </table>\r\n    </div>\r\n    </div>\r\n    </div>\r\n    \r\n\r\n                                        \r\n                    \r\n                          \r\n                                        \r\n                        \r\n    <!-- END: Content-->\r\n    <script src=\"cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js\"></script>\r\n    <script src=\"../app-assets/js/scripts/datatables/datatable.js\"></script>\r\n    <script>\r\n    \$('#myTable').DataTable({\r\n\r\n        /* traduzir somente */\r\n        \"language\": {\r\n            \"lengthMenu\": \"Mostrar _MENU_ registros por página\",\r\n            \"zeroRecords\": \"Nenhum registro encontrado\",\r\n            \"info\": \"Mostrando página _PAGE_ de _PAGES_\",\r\n            \"infoEmpty\": \"Nenhum registro disponível\",\r\n            \"infoFiltered\": \"(filtrado de _MAX_ registros no total)\",\r\n            \"search\": \"Pesquisar:\",\r\n            \"paginate\": {\r\n                \"first\": \"\",\r\n                \"last\": \"\",\r\n                \"next\": \"\",\r\n                \"previous\": \"\"\r\n            }\r\n        }\r\n    \r\n    });\r\n\r\n</script>\r\n<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n<!-- ajax -->\r\n<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js\" integrity=\"sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>\r\n";

?>