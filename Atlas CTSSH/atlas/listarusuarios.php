<?php


echo "\r\n";
error_reporting(0);
session_start();
include "conexao.php";
include "header2.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$sql = "SELECT * FROM ssh_accounts WHERE byid = '" . $_SESSION["iduser"] . "' ORDER BY expira ASC";
$result = $conn->query($sql);
$sql44 = "SELECT * FROM configs";
$result44 = $conn->query($sql44);
while ($row44 = $result44->fetch_assoc()) {
    $deviceativo = $row44["deviceativo"];
}
if ($deviceativo == "1") {
    $limpardeviceids = "<button type=\"button\" class=\"btn btn-outline-danger btn-lg btn-block\" onclick=\"limpardeviceids()\">Limpar Device IDs</button>";
}
echo "  \r\n   <script>\r\n  function limpardeviceids() {\r\n    swal({\r\n    title: \"Tem certeza?\",\r\n    text: \"Você não poderá reverter isso!\",\r\n    icon: \"warning\",\r\n    buttons: true,\r\n    dangerMode: true,\r\n  })\r\n  .then((willDelete) => {\r\n    if (willDelete) {\r\n      window.location.href = \"limpardeviceids.php\";\r\n    } else {\r\n      swal(\"Cancelado!\");\r\n    }\r\n  });\r\n  }\r\n  </script>\r\n      <script>\r\n    \r\n  \r\n</script>\r\n<script src=\"https://code.jquery.com/jquery-3.5.1.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js\"></script>\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\">\r\n<div class=\"app-content content\">\r\n    <div class=\"content-overlay\"></div>\r\n    <div class=\"content-wrapper\">\r\n        <section id=\"basic-datatable\">\r\n            <div class=\"row\">\r\n             \r\n                <div class=\"col-12\">\r\n                    <div class=\"card\">\r\n                        <div class=\"card-header\">\r\n                            <h4 class=\"card-title\">Lista de Usuarios</h4>\r\n                        </div>\r\n                        <script>\r\nif (window.innerWidth < 678) {\r\n\r\n    document.write('<div class=\"alert alert-warning\" role=\"alert\"> <strong>Atenção!</strong> Mova para lado para Fazer Alguma Ação! </div>');\r\n    window.setTimeout(function() {\r\n        \$(\".alert\").fadeTo(500, 0).slideUp(500, function(){\r\n            \$(this).remove(); \r\n        });\r\n    }, 3000);\r\n}\r\n</script>\r\n                        <div class=\"card-content\">\r\n                            <div class=\"card-body card-dashboard\">\r\n                                \r\n                                <!-- nao mostar o sroll -->\r\n                                <div class=\"table-responsive\" style=\" overflow: auto; overflow-y: hidden;\">\r\n                                    <table class=\"table zero-configuration\" id=\"myTable\">\r\n                                                <thead>\r\n                                                    <tr>\r\n                                                        <th>Usuario</th>\r\n                                                        <th>Limite</th>\r\n                                                        <th>categoria</th>\r\n                                                        <th>Validade</th>\r\n                                                        <th>Status</th>\r\n                                                        <th>Notas</th>\r\n                                                        <th>Editar</th>\r\n                                                    </tr>\r\n                                                </thead>\r\n                                                <tbody>\r\n                                                ";
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
        $expira = $row["expira"];
        $expira = date("d/m/Y", strtotime($expira));
        $expira2 = $expira;
        $sql2 = "SELECT * FROM onlines WHERE usuario = '" . $login . "'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $usando = $row2["quantidade"];
        if ($expira2 == date("d/m/Y", strtotime("+5 days"))) {
            $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 5 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("+4 days"))) {
            $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 4 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("+3 days"))) {
            $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 3 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("+2 days"))) {
            $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 2 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("+1 days"))) {
            $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 1 dias</label>";
        }
        if ($expira2 == date("d/m/Y")) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expira Hoje</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-1 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou ontem</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-2 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou anteontem</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-3 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 3 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-4 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 4 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-5 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 5 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-6 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 6 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-7 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 7 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-8 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 8 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-9 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 9 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-10 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 10 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-11 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 11 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-12 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 12 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-13 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 13 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-14 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 14 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-15 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 15 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-16 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 16 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-17 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 17 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-18 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 18 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-19 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 19 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-20 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 20 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-21 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 21 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-22 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 22 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-23 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 23 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-24 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 24 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-25 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 25 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-26 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 26 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-27 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 27 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-28 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 28 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-29 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 29 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-30 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 30 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-31 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 31 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-32 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 32 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-33 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 33 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-34 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 34 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-35 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 35 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-36 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 36 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-37 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 37 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-38 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 38 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-39 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 39 dias</label>";
        }
        if ($expira2 == date("d/m/Y", strtotime("-40 days"))) {
            $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 40 dias</label>";
        }
        if ($deviceativo == "1") {
            $deviceativo = "Sim";
        } else {
            $deviceativo = "Não";
        }
        if ($deviceid == "") {
            $deviceid = "Nenhum";
        }
        echo "<tr>\r\n                                                        <td>" . $login . "</td>\r\n                                                        ";
        if ($usando == 0) {
            echo "<td>" . $limite . "</td>";
        } else {
            if ($limite < $usando) {
                echo "<td><label class='badge badge-danger' style='color: #fff;'> " . $usando . " / " . $limite . " </label></td>";
            } else {
                echo "<td><label class='badge badge-success' style='color: #fff;'> " . $usando . " / " . $limite . " </label></td>";
            }
        }
        echo "\r\n                                                        <td>" . $categoria . "</td>\r\n                                                        <td>" . $expira . "</td>\r\n                                                        ";
        switch ($suspenso) {
            case "Suspenso":
                echo "<td class='text-danger'>Suspenso</td>";
                break;
            case "Limite Ultrapassado":
                echo "<td class='text-danger'>Limite Ultrapassado</td>";
                break;
            default:
                if ($status == "Online") {
                    echo "<td class='text-success'>Online</td>";
                } else {
                    echo "<td class='text-alert'>Offline</td>";
                }
                echo "<td>" . $notas . "</td>";
                echo "\r\n                                                        <td><div class=\"btn-group mb-1\">\r\n                                                        <div class=\"dropdown\">\r\n                                                            <button class=\"btn btn-primary dropdown-toggle mr-1\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">\r\n                                                                Ações\r\n                                                            </button>\r\n                                                            <div class=\"dropdown-menu\">\r\n                                                                <a class=\"dropdown-item\" href=\"editarlogin.php?id=" . $id . "\">Editar</a>\r\n                                                                <a class=\"dropdown-item\" onclick=\"limpardeviceids(" . $id . ")\">Limpar Device ID</a>\r\n                                                                <a class=\"dropdown-item\" onclick=\"renovardias(" . $id . ")\">Renovar Dias</a>\r\n                                                                <a class=\"dropdown-item\" onclick=\"reativar(" . $id . ")\">Reativar</a>\r\n                                                                <a class=\"dropdown-item\" onclick=\"suspender(" . $id . ")\">Suspender</a>\r\n                                                                <a class=\"dropdown-item\" onclick=\"excluir(" . $id . ")\">Excluir</a>\r\n                                                            </div>\r\n                                                        </div>\r\n                                                    </div>\r\n                                                    </td>\r\n                                                    </tr>";
        }
    }
}
echo "                                                <script>\r\n    function limpardeviceids(\$id) {\r\n    /* confirma */\r\n    swal({\r\n    title: \"Tem certeza?\",\r\n    text: \"Você deseja limpar o Device ID do usuário?\",\r\n    icon: \"warning\",\r\n    buttons: true,\r\n    dangerMode: true,\r\n    })\r\n  .then((willDelete) => {\r\n    if (willDelete) {\r\n      /* faz uma requisiçao com ajax */\r\n        \$.ajax({\r\n            url: 'deviceid.php?id='+\$id,\r\n            type: 'GET',\r\n            success: function(data){\r\n               if (data == 'deletado com sucesso') {\r\n                swal(\"Sucesso!\", \"Device ID limpo com sucesso!\", \"success\");\r\n               }else{\r\n                swal(\"Erro!\", \"Erro ao limpar Device ID!\", \"error\");\r\n               }\r\n            }\r\n        });\r\n    } else {\r\n      swal(\"Cancelado!\");\r\n    }\r\n  });\r\n  }\r\n    function renovardias(\$id) {\r\n        /* confirma */\r\n        swal({\r\n        title: \"Tem certeza?\",\r\n        text: \"Você deseja renovar os dias do usuário?\",\r\n        icon: \"warning\",\r\n        buttons: true,\r\n        dangerMode: true,\r\n        })\r\n        .then((willDelete) => {\r\n        if (willDelete) {\r\n            \$.ajax({\r\n                url: 'renovardias.php?id='+\$id,\r\n                type: 'GET',\r\n                success: function(data){\r\n                    if (data == 'Renovado com Sucesso!') {\r\n                        /* ao clicar atualiza pagina */\r\n                        swal(\"Sucesso!\", \"Dias renovados com sucesso!\", \"success\").then(function() {\r\n                            location.reload();\r\n                        });\r\n                    }else{\r\n                        swal(\"Erro!\", \"Erro ao renovar dias!\", \"error\");\r\n                    }\r\n                }\r\n            });\r\n        } else {\r\n            swal(\"Cancelado!\");\r\n        }\r\n        });\r\n    }\r\n    function reativar(\$id) {\r\n        /* confirma */\r\n        swal({\r\n        title: \"Tem certeza?\",\r\n        text: \"Você deseja reativar o usuário?\",\r\n        icon: \"warning\",\r\n        buttons: true,\r\n        dangerMode: true,\r\n        })\r\n        .then((willDelete) => {\r\n        if (willDelete) {\r\n            \$.ajax({\r\n                url: 'reativar.php?id='+\$id,\r\n                type: 'GET',\r\n                success: function(data){\r\n                    if (data == 'reativado com sucesso') {\r\n                        /* ao clicar atualiza pagina */\r\n                        swal(\"Sucesso!\", \"Usuário reativado com sucesso!\", \"success\").then(function() {\r\n                            location.reload();\r\n                        });\r\n                    }else{\r\n                        swal(\"Erro!\", \"Erro ao reativar usuário!\", \"error\");\r\n                    }\r\n                }\r\n            });\r\n        } else {\r\n            swal(\"Cancelado!\");\r\n        }\r\n        });\r\n    }\r\n    function suspender(\$id) {\r\n        /* confirma */\r\n        swal({\r\n        title: \"Tem certeza?\",\r\n        text: \"Você deseja suspender o usuário?\",\r\n        icon: \"warning\",\r\n        buttons: true,\r\n        dangerMode: true,\r\n        })\r\n        .then((willDelete) => {\r\n        if (willDelete) {\r\n            \$.ajax({\r\n                url: 'suspender.php?id='+\$id,\r\n                type: 'GET',\r\n                success: function(data){\r\n                    if (data == 'erro no servidor') {\r\n                        /* ao clicar atualiza pagina */\r\n                        swal(\"Erro!\", \"Erro no servidor, verifique se o servidor está online ou se a senha está correta!\", \"error\");\r\n                    }else{\r\n\r\n                    if (data == 'suspenso com sucesso') {\r\n                        /* ao clicar atualiza pagina */\r\n                        swal(\"Sucesso!\", \"Usuário suspenso com sucesso!\", \"success\").then(function() {\r\n                            location.reload();\r\n                        });\r\n                    }else{\r\n                        swal(\"Erro!\", \"Erro ao suspender usuário!\", \"error\");\r\n                    }\r\n                }\r\n                }\r\n            });\r\n        } else {\r\n            swal(\"Cancelado!\");\r\n        }\r\n    });\r\n}\r\nfunction excluir(\$id) {\r\n        /* confirma */\r\n        swal({\r\n        title: \"Tem certeza?\",\r\n        text: \"Você deseja excluir o usuário?\",\r\n        icon: \"warning\",\r\n        buttons: true,\r\n        dangerMode: true,\r\n        })\r\n        .then((willDelete) => {\r\n        if (willDelete) {\r\n            \$.ajax({\r\n                url: 'excluiruser.php?id='+\$id,\r\n                type: 'GET',\r\n                success: function(data){\r\n                    if (data == 'excluido') {\r\n                        /* ao clicar atualiza pagina */\r\n                        swal(\"Sucesso!\", \"Usuário excluido com sucesso!\", \"success\").then(function() {\r\n                            location.reload();\r\n                        });\r\n                    }else{\r\n                        swal(\"Erro!\", \"Erro ao excluir usuário!\", \"error\");\r\n                    }\r\n                }\r\n            });\r\n        } else {\r\n            swal(\"Cancelado!\");\r\n        }\r\n        });\r\n    }\r\n  </script>\r\n  </tbody>\r\n    </table>\r\n    </div>\r\n    </div>\r\n    </div>\r\n    \r\n\r\n                                        \r\n                    \r\n                          \r\n                                        \r\n                        \r\n    <!-- END: Content-->\r\n    <script src=\"cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js\"></script>\r\n    <script src=\"../app-assets/js/scripts/datatables/datatable.js\"></script>\r\n    <script>\r\n    \$('#myTable').DataTable({\r\n\r\n        /* traduzir somente */\r\n        \"language\": {\r\n            \"lengthMenu\": \"Mostrar _MENU_ registros por página\",\r\n            \"zeroRecords\": \"Nenhum registro encontrado\",\r\n            \"info\": \"Mostrando página _PAGE_ de _PAGES_\",\r\n            \"infoEmpty\": \"Nenhum registro disponível\",\r\n            \"infoFiltered\": \"(filtrado de _MAX_ registros no total)\",\r\n            \"search\": \"Pesquisar:\",\r\n            \"paginate\": {\r\n                \"first\": \"\",\r\n                \"last\": \"\",\r\n                \"next\": \"\",\r\n                \"previous\": \"\"\r\n            }\r\n        }\r\n    \r\n    });\r\n\r\n</script>\r\n<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n<!-- ajax -->\r\n<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js\" integrity=\"sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>\r\n";

?>