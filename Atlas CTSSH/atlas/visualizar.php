<?php


error_reporting(0);
session_start();
include "../atlas/conexao.php";
include "header2.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$sql = "SELECT * FROM accounts WHERE login = '" . $_SESSION["login"] . "' AND senha = '" . $_SESSION["senha"] . "'";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $iduser = $row["id"];
    }
}
$_GET["id"] = anti_sql($_GET["id"]);
$id = $_GET["id"];
$sql = "SELECT * FROM accounts WHERE id = '" . $id . "'";
$result2 = $conn->query($sql);
if (0 < $result2->num_rows) {
    while ($row = $result2->fetch_assoc()) {
        $login = $row["login"];
    }
}
$sql = "SELECT * FROM ssh_accounts WHERE byid = '" . $id . "'";
$result = $conn->query($sql);
echo "<script src=\"https://code.jquery.com/jquery-3.5.1.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js\"></script>\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\">\r\n<div class=\"app-content content\">\r\n    <div class=\"content-overlay\"></div>\r\n    <div class=\"content-wrapper\">\r\n        <section id=\"basic-datatable\">\r\n            <div class=\"row\">\r\n             \r\n                <div class=\"col-12\">\r\n                    <div class=\"card\">\r\n                        <div class=\"card-header\">\r\n                            <h4 class=\"card-title\">Detalhes do Revendedor ";
echo $login;
echo "</h4>\r\n                        </div>\r\n                        <script>\r\n\r\n\r\nif (window.innerWidth < 678) {\r\n\r\n    document.write('<div class=\"alert alert-warning\" role=\"alert\"> <strong>Atenção!</strong> Mova para lado para Fazer Alguma Ação! </div>');\r\n    window.setTimeout(function() {\r\n        \$(\".alert\").fadeTo(500, 0).slideUp(500, function(){\r\n            \$(this).remove(); \r\n        });\r\n    }, 3000);\r\n}\r\n\r\n</script>\r\n                        <div class=\"card-content\">\r\n                            <div class=\"card-body card-dashboard\">\r\n                                <!-- nao mostar o sroll -->\r\n                                <div class=\"table-responsive\" style=\" overflow: auto; overflow-y: hidden;\">\r\n                                    <table class=\"table zero-configuration\" id=\"myTable\">\r\n                                                <thead>\r\n                                                    <tr>\r\n                                                    <th> Usuario</th>\r\n                                                    <th> Senha </th>\r\n                                                    <th> Limite </th>\r\n                                                    <th> Vencimento </th>\r\n                                                    <th> Status </th>\r\n                                                    </tr>\r\n                                                </thead>\r\n                                                <tbody>\r\n                                                ";
while ($user_data = mysqli_fetch_assoc($result)) {
    $user_data["expira"] = date("d/m/Y H:i:s", strtotime($user_data["expira"]));
    echo "<tr>";
    echo "<td>" . $user_data["login"] . "</td>";
    echo "<td>" . $user_data["senha"] . "</td>";
    echo "<td>" . $user_data["limite"] . "</td>";
    echo "<td>" . $user_data["expira"] . "</td>";
    if ($user_data["mainid"] == "Suspenso") {
        echo "<td><label class='badge badge-danger'>Suspenso</label></td>";
    } else {
        if ($user_data["status"] == "Online") {
            echo "<td><label class='badge badge-success'>Online</label></td>";
        } else {
            echo "<td><label class='badge badge-danger'>Offline</label></td>";
        }
        echo "</tr>";
    }
}
echo "  </tbody>\r\n    </table>\r\n    \r\n    </div>\r\n    </div>\r\n    </div>\r\n    <div class=\"card-body\"><br>\r\n                    <h4 class=\"card-title\">Revendedores do ";
echo $login;
echo "</h4>\r\n                    <div class=\"table-responsive\">\r\n                     \r\n                        </div>\r\n                 \r\n                    <div class=\"table-responsive\">\r\n                      <table class=\"table table-striped\">\r\n                        <thead>\r\n                          <tr>\r\n                            <th>  </th>\r\n                            <th> Usuario</th>\r\n                            <th> Senha </th>\r\n                            <th> Limite </th>\r\n                            <th> Vencimento </th>\r\n                            <th> Status </th>\r\n                          </tr>\r\n                        </thead>\r\n                        <tbody id=\"myTable\">\r\n                        ";
$sql = "SELECT * FROM accounts where byid = '" . $id . "'";
$result3 = $conn->query($sql);
$sql = "SELECT * FROM atribuidos where byid = '" . $id . "'";
$result4 = $conn->query($sql);
while (($user_data = mysqli_fetch_assoc($result3)) && ($user_data2 = mysqli_fetch_assoc($result4))) {
    $user_data2["expira"] = date("d/m/Y H:i:s", strtotime($user_data2["expira"]));
    echo "<tr><td></td>";
    echo "<td>" . $user_data["login"] . "</td>";
    echo "<td>" . $user_data["senha"] . "</td>";
    echo "<td>" . $user_data2["limite"] . "</td>";
    if ($user_data2["tipo"] == "Credito") {
        $user_data2["expira"] = "Nunca";
    }
    echo "<td>" . $user_data2["expira"] . "</td>";
    if ($user_data2["suspenso"] == "1") {
        echo "<td><label class='badge badge-danger'>Suspenso</label></td>";
    } else {
        echo "<td><label class='badge badge-success'>Ativo</label></td>";
    }
    echo "</tr>";
}
echo "\r\n                          \r\n                        </tbody>\r\n                      </table>\r\n                   \r\n                        </div>\r\n                        </div>\r\n                        \r\n                  <div class=\"card-body\">\r\n                    <h4 class=\"card-title\">Pagamentos Recebidos</h4>\r\n                    <!-- <p class=\"card-description\"> Add class <code>Usuarios</code>\r\n                    </p> -->\r\n                    <div class=\"table-responsive\">\r\n                      <table class=\"table table-striped\">\r\n                        <thead>\r\n                          <tr>\r\n                            <th> Login </th>\r\n                            <th> Id do Pagamento </th>\r\n                            <th> Valor </th>\r\n                            <th> Detalhes </th>\r\n                            <th> Data e Hora </th>\r\n                            <th> Status </th>\r\n                          </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                          ";
$sql = "SELECT * FROM pagamentos  where byid = '" . $id . "'";
$result = $conn->query($sql);
while ($user_data = mysqli_fetch_assoc($result)) {
    if ($user_data["status"] == "Aprovado") {
        $status = "<label class='badge badge-success'>Aprovado</label>";
    } else {
        $status = "<label class='badge badge-danger'>Pendente</label>";
    }
    echo "<td>" . $user_data["login"] . "</td>";
    echo "<td>" . $user_data["idpagamento"] . "</td>";
    echo "<td>" . $user_data["valor"] . "</td>";
    echo "<td>" . $user_data["texto"] . "</td>";
    echo "<td>" . $user_data["data"] . "</td>";
    echo "<td>" . $status . "</td>";
    echo "</tr>";
}
echo "                          \r\n                        </tbody>\r\n                      </table>\r\n    \r\n\r\n                                        \r\n                    \r\n                          \r\n                                        \r\n                        \r\n    <!-- END: Content-->\r\n    <script src=\"cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js\"></script>\r\n    <script src=\"../app-assets/js/scripts/datatables/datatable.js\"></script>\r\n    <script>\r\n    \$('#myTable').DataTable({\r\n\r\n        /* traduzir somente */\r\n        \"language\": {\r\n            \"lengthMenu\": \"Mostrar _MENU_ registros por página\",\r\n            \"zeroRecords\": \"Nenhum registro encontrado\",\r\n            \"info\": \"Mostrando página _PAGE_ de _PAGES_\",\r\n            \"infoEmpty\": \"Nenhum registro disponível\",\r\n            \"infoFiltered\": \"(filtrado de _MAX_ registros no total)\",\r\n            \"search\": \"Pesquisar:\",\r\n            \"paginate\": {\r\n                \"first\": \"\",\r\n                \"last\": \"\",\r\n                \"next\": \"\",\r\n                \"previous\": \"\"\r\n            }\r\n        }\r\n    \r\n    });\r\n\r\n</script>\r\n<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n<!-- ajax -->\r\n<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js\" integrity=\"sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>\r\n";
function anti_sql($input)
{
    $seg = preg_replace_callback("/(from|select|insert|delete|where|drop table|show tables|#|\\*|--|\\\\)/i", function ($match) {
        return "";
    }, $input);
    $seg = trim($seg);
    $seg = strip_tags($seg);
    $seg = addslashes($seg);
    return $seg;
}

?>