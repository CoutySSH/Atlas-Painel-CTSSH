<?php


echo "<!DOCTYPE html>\r\n<html lang=\"pt-br\">\r\n  <head>\r\n    <!-- Required meta tags -->\r\n    <meta charset=\"utf-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">\r\n    ";
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
include "header2.php";
date_default_timezone_set("America/Sao_Paulo");
$data = date("Y-m-d H:i:s");
$slq = "SELECT * FROM ssh_accounts WHERE byid = '" . $_SESSION["iduser"] . "' and expira < '" . $data . "'";
$result = mysqli_query($conn, $slq);
echo "<script src=\"https://code.jquery.com/jquery-3.5.1.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js\"></script>\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\">\r\n<div class=\"app-content content\">\r\n    <div class=\"content-overlay\"></div>\r\n    <div class=\"content-wrapper\">\r\n        <section id=\"basic-datatable\">\r\n            <div class=\"row\">\r\n             \r\n                <div class=\"col-12\">\r\n                    <div class=\"card\">\r\n                        <div class=\"card-header\">\r\n                            <h4 class=\"card-title\">Lista de Expirados</h4>\r\n                 \r\n                        </div>\r\n                        <script>\r\n\r\n\r\nif (window.innerWidth < 678) {\r\n\r\n    document.write('<div class=\"alert alert-warning\" role=\"alert\"> <strong>Atenção!</strong> Mova para lado para Fazer Alguma Ação! </div>');\r\n    window.setTimeout(function() {\r\n        \$(\".alert\").fadeTo(500, 0).slideUp(500, function(){\r\n            \$(this).remove(); \r\n        });\r\n    }, 3000);\r\n}\r\n\r\n</script>\r\n<div class=\"card-content\">\r\n                            <div class=\"card-body card-dashboard\">\r\n                                <!-- nao mostar o sroll -->\r\n                                <a class=\"btn btn-danger btn-rounded btn-fw\" onclick=\"excluirTodos()\">Excluir Todos</a>\r\n                                <script>\r\n                                    function excluirTodos() {\r\n                                       \r\n                                        swal({\r\n                                  title: \"Tem certeza?\",\r\n                                  text: \"Uma vez deletado, você não poderá recuperar esses Usuarios!\",\r\n                                  icon: \"warning\",\r\n                                  buttons: true,\r\n                                  dangerMode: true,\r\n                                })\r\n                                .then((willDelete) => {\r\n                                  if (willDelete) {\r\n                                    swal(\"Os Usuarios foram deletados com sucesso!\", {\r\n                                      icon: \"success\",\r\n                                    });\r\n                                    window.location.href = \"deleteexpirados.php\";\r\n                                  } else {\r\n                                    swal(\"Os Usuarios não foram deletados!\");\r\n                                  }\r\n                                });\r\n                                    }\r\n                                </script>\r\n                                <div class=\"table-responsive\" style=\" overflow: auto; overflow-y: hidden;\">\r\n                                    <table class=\"table zero-configuration\" id=\"myTable\">\r\n                                                <thead>\r\n                                                    <tr>\r\n                                                        <th>Usuario</th>\r\n                                                        <th>Senha</th>\r\n                                                        <th>Limite</th>\r\n                                                        <th>categoria</th>\r\n                                                        <th>Validade</th>\r\n                                                        <th>Status</th>\r\n                                                        <th>Notas</th>\r\n                                                        <th>Editar</th>\r\n                                                    </tr>\r\n                                                </thead>\r\n                                                <tbody>\r\n                                                ";
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $login = $row["login"];
        $senha = $row["senha"];
        $limite = $row["limite"];
        $validade = $row["expira"];
        $status = $row["status"];
        $deviceid = $row["deviceid"];
        $deviceativo = $row["deviceativo"];
        $categoria = $row["categoriaid"];
        $suspenso = $row["mainid"];
        $notas = $row["lastview"];
        $data = date("Y-m-d");
        $diferenca = strtotime($validade) - strtotime($data);
        $dias = floor($diferenca / 86400);
        if ($dias < 0) {
            $dias = "Expirado";
        } else {
            $dias = $dias . " Restantes";
        }
        if ($deviceativo == "1") {
            $deviceativo = "Sim";
        } else {
            $deviceativo = "Não";
        }
        if ($deviceid == "") {
            $deviceid = "Nenhum";
        }
        echo "<tr>\r\n                                                        <td>" . $login . "</td>\r\n                                                        <td>" . $senha . "</td>\r\n                                                        <td>" . $limite . "</td>\r\n                                                        <td>" . $categoria . "</td>\r\n                                                        <td>" . $dias . "</td>\r\n                                                        ";
        if ($suspenso == "Suspenso") {
            echo "<td class='text-danger'>Suspenso</td>";
        } else {
            if ($status == "Online") {
                echo "<td class='text-success'>Online</td>";
            } else {
                echo "<td class='text-alert'>Offline</td>";
            }
        }
        echo "<td>" . $notas . "</td>";
        echo "\r\n                                                        <td><div class=\"btn-group mb-1\">\r\n                                                        <div class=\"dropdown\">\r\n                                                            <button class=\"btn btn-primary dropdown-toggle mr-1\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">\r\n                                                                Ações\r\n                                                            </button>\r\n                                                            <div class=\"dropdown-menu\">\r\n                                                               \r\n                                                                <a class=\"dropdown-item\" onclick=\"excluir(" . $id . ")\">Excluir</a>\r\n                                                            </div>\r\n                                                        </div>\r\n                                                    </div></td>\r\n                                                    </tr>";
    }
}
echo "<script>\r\nfunction excluir(\$id) {\r\n        /* confirma */\r\n        swal({\r\n        title: \"Tem certeza?\",\r\n        text: \"Você deseja excluir o usuário?\",\r\n        icon: \"warning\",\r\n        buttons: true,\r\n        dangerMode: true,\r\n        })\r\n        .then((willDelete) => {\r\n        if (willDelete) {\r\n            \$.ajax({\r\n                url: 'excluiruser.php?id='+\$id,\r\n                type: 'GET',\r\n                success: function(data){\r\n                    if (data == 'excluido') {\r\n                        /* ao clicar atualiza pagina */\r\n                        swal(\"Sucesso!\", \"Usuário excluido com sucesso!\", \"success\").then(function() {\r\n                            location.reload();\r\n                        });\r\n                    }else{\r\n                        swal(\"Erro!\", \"Erro ao excluir usuário!\", \"error\");\r\n                    }\r\n                }\r\n            });\r\n        } else {\r\n            swal(\"Cancelado!\");\r\n        }\r\n        });\r\n    }\r\n  </script>\r\n  \r\n  </tbody>\r\n    </table>\r\n    </div>\r\n    </div>\r\n    </div>\r\n    \r\n\r\n                                        \r\n                    \r\n                          \r\n                                        \r\n                        \r\n    <!-- END: Content-->\r\n    <script src=\"cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js\"></script>\r\n    <script src=\"../app-assets/js/scripts/datatables/datatable.js\"></script>\r\n    <script>\r\n    \$('#myTable').DataTable({\r\n\r\n        /* traduzir somente */\r\n        \"language\": {\r\n            \"lengthMenu\": \"Mostrar _MENU_ registros por página\",\r\n            \"zeroRecords\": \"Nenhum registro encontrado\",\r\n            \"info\": \"Mostrando página _PAGE_ de _PAGES_\",\r\n            \"infoEmpty\": \"Nenhum registro disponível\",\r\n            \"infoFiltered\": \"(filtrado de _MAX_ registros no total)\",\r\n            \"search\": \"Pesquisar:\",\r\n            \"paginate\": {\r\n                \"first\": \"\",\r\n                \"last\": \"\",\r\n                \"next\": \"\",\r\n                \"previous\": \"\"\r\n            }\r\n        }\r\n    \r\n    });\r\n\r\n</script>\r\n<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n<!-- ajax -->\r\n<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js\" integrity=\"sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>\r\n";

?>