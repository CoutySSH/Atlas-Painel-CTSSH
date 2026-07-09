<?php


echo "\r\n";
error_reporting(0);
session_start();
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
include "headeradmin2.php";
$sql = "SELECT * FROM accounts WHERE login != 'admin'";
$result = $conn->query($sql);
$sql2 = "SELECT * FROM atribuidos";
$result2 = $conn->query($sql2);
date_default_timezone_set("America/Sao_Paulo");
$hoje = date("dmY");
echo "<script src=\"https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js\"></script>\r\n\r\n<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css\" integrity=\"sha512-9O9Sd6Ia1+A0+KwUO1eUg0Fyb3J6UdTo68joKgY9A20+RzI2HfIQK8pk6FyUdxUGpIq3oUItrW8jYVGf9GYZRg==\" crossorigin=\"anonymous\" />\r\n<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css\" integrity=\"sha512-HFtTtyTjlELm+B62zspZ8PqKmzvDmCdjLJl/dyK2TlT1Tkbz2eNmv1Gsb8BLYgjKv7/9FFylhL9X8KbW36BvDw==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" />\r\n<!-- JavaScript do jQuery -->\r\n<script src=\"https://code.jquery.com/jquery-3.6.0.min.js\" integrity=\"sha384-50RcK1E6jgEnV9P+A5fjaqV6Om4ZK7PO0De/4+i4eEI4GgkKTU1hvvB6KLpU5ij5\" crossorigin=\"anonymous\"></script>\r\n\r\n<!-- JavaScript do Bootstrap -->\r\n</head>\r\n \r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\">\r\n\r\n\r\n  <script>\r\n    /* se a tela for maior */\r\n    if (window.innerWidth < 678) {\r\n      document.getElementById(\"widgets-Statistics\").style.display = \"none\";\r\n      alert(\"A tela está muito pequena para exibir os gráficos, por favor, aumente a tela!\");\r\n    }\r\n    \$(document).ready(function(){\r\n});\r\n    </script>\r\n            \r\n\r\n                \r\n<div class=\"app-content content\">\r\n    <div class=\"content-overlay\">\r\n      \r\n    </div>\r\n    \r\n    <div class=\"content-wrapper\">\r\n      \r\n      <div class=\"container-xxl flex-grow-1 container-p-y\">\r\n        \r\n        <section id=\"basic-datatable\">\r\n            <div class=\"row\">\r\n              \r\n             \r\n                <div class=\"col-12\">\r\n                  \r\n                    <div class=\"card\">\r\n                      \r\n                        <div class=\"card-header\">\r\n                            <h4 class=\"card-title\">Lista de Revendedores</h4>\r\n                        </div>\r\n\r\n                        <script>\r\n\r\n\r\nif (window.innerWidth < 678) {\r\n\r\n    document.write('<div class=\"alert alert-warning\" role=\"alert\"> <strong>Atenção!</strong> Mova para lado para Fazer Alguma Ação! </div>');\r\n    window.setTimeout(function() {\r\n        \$(\".alert\").fadeTo(500, 0).slideUp(500, function(){\r\n            \$(this).remove(); \r\n        });\r\n    }, 3000);\r\n}\r\n\r\n</script>\r\n\r\n\r\n                        <div class=\"card-content\">\r\n                            <div class=\"card-body card-dashboard\">\r\n                                <!-- nao mostar o sroll -->\r\n                                <div class=\"table-responsive\" style=\" overflow: auto; overflow-y: hidden;\">\r\n                                    <table class=\"table zero-configuration\" id=\"myTable\">\r\n                                                <thead>\r\n                                                    <tr>\r\n                                                   \r\n                                                    <th> Login </th>\r\n                                                    <th> Senha </th>\r\n                                                    <th> Modo </th>\r\n                                                    <th> Categoria </th>\r\n                                                    <th> Limite </th>\r\n                                                    <th> Validade </th>\r\n                                                    <th> Status </th>\r\n                                                    <th> Acões </th>\r\n                                                    </tr>\r\n                                                </thead>\r\n                                                <tbody>\r\n                                                ";
while ($user_data = mysqli_fetch_assoc($result)) {
    $sql2 = "SELECT * FROM atribuidos  WHERE userid = '" . $user_data["id"] . "' ";
    $result2 = $conn->query($sql2);
    $user_data2 = mysqli_fetch_assoc($result2);
    $expira = $user_data2["expira"];
    $expira = date("d/m/Y", strtotime($expira2));
    $user_data["expira"] = $expira2;
    $expira2 = $user_data2["expira"];
    $expira2 = date("d/m/Y", strtotime($expira2));
    $user_data2["expira"] = $expira2;
    $tipomodal = $user_data2["tipo"];
    $expira = $user_data2["expira"];
    $limite = $user_data2["limite"];
    echo "<tr>";
    echo "<td>" . $user_data["login"] . "</td>";
    echo "<td>" . $user_data["senha"] . "</td>";
    if ($user_data2["tipo"] == "Credito") {
        $expira = "Nunca";
    }
    echo "<td>" . $user_data2["tipo"] . "</td>";
    echo "<td>" . $user_data2["categoriaid"] . "</td>";
    date_default_timezone_set("America/Sao_Paulo");
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
    echo "<td>" . $limite . "</td>";
    echo "<td>" . $expira . "</td>";
    if ($user_data2["suspenso"] == "0") {
        echo "<td><label class='badge badge-success mr-1 mb-1' style='color: white;'>Ativo</label></td>";
    } else {
        echo "<td><label class='badge badge-danger' style='color: white;'>Suspenso</label></td>";
    }
    echo "<td>\r\n                            \r\n                            <div class='btn-group'>\r\n                              <button type='button' class='btn btn-primary dropdown-toggle dropdown-toggle-split' id='dropdownMenuReference' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' data-reference='parent'>\r\n                                <span class='sr-only'>Toggle Dropdown</span>Acões\r\n                              </button>\r\n                              <style>\r\n                              .dropdown-item:hover{\r\n                                \r\n                                color: white;\r\n                              }\r\n                              </style>\r\n                              <div class='dropdown-menu' aria-labelledby='dropdownMenuReference'>\r\n                                <a class='dropdown-item' onclick='editar(" . $user_data["id"] . ")'>Editar</a>\r\n                                <a class='dropdown-item' href='javascript:deletar(" . $user_data["id"] . ")'>Deletar</a>\r\n                                ";
    if ($user_data2["tipo"] != "Credito") {
        echo "<a class='dropdown-item' href='javascript:renovar(" . $user_data["id"] . ")'>Renovar</a>";
    }
    echo "\r\n                                <a class='dropdown-item' href='javascript:suspender(" . $user_data["id"] . ")'>Suspender</a>\r\n                                <a class='dropdown-item' href='javascript:reativar(" . $user_data["id"] . ")'>Reativar</a>\r\n                                <a class='dropdown-item' href='visualizar.php?id=" . $user_data["id"] . "'>Visualizar</a>\r\n                                <a class='dropdown-item' href='javascript:puxa(" . $user_data["id"] . ")'>Puxa Revenda</a>\r\n                                <form method='post' action='listarrevendedores.php'>\r\n                                <button type='submit' name='entrarrevenda' class='dropdown-item' value='" . $user_data["id"] . "'>Entrar na Conta</button>\r\n                                </form>\r\n                              </div>\r\n                          </td>";
    echo "</tr>";
}
if (isset($_POST["entrarrevenda"])) {
    $_SESSION["identrarrevenda"] = $_POST["entrarrevenda"];
    echo "<script>alert('Entrando na conta do revendedor');</script><script>window.location.href='entrarrevenda.php';</script>";
}
echo "\r\n                          \r\n                     \r\n            <!-- modal editar -->\r\n \r\n\r\n            <script>\r\n      \r\n         function editar(\$id) {\r\n         /* redireciona para outra pagina  */\r\n          window.location.href = \"editarrevenda.php?id=\"+\$id;\r\n        }\r\n\r\n    function renovar(id) {\r\n                              swal({\r\n                                title: 'Tem certeza?',\r\n                                text: 'Você não poderá reverter isso!',\r\n                                icon: 'warning',\r\n                                buttons: true,\r\n                                dangerMode: true,\r\n                              })\r\n                              .then((willDelete) => {\r\n                                if (willDelete) {\r\n                                  swal('Poof! Renovando Revendedor!', {\r\n                                    icon: 'success',\r\n                                    //entra no\r\n                                  });\r\n                                  window.location.href = 'renovarrevenda.php?id='+id;\r\n                                } else {\r\n                                  swal('Seu Revendedor está seguro!');\r\n                                }\r\n                              });\r\n                            }\r\n                     function puxa(id) {\r\n                              swal({\r\n                                title: 'Tem certeza? Isso Irá Puxar o Revendedor para sua Conta!',\r\n                                text: 'Você não poderá reverter isso!',\r\n                                icon: 'warning',\r\n                                buttons: true,\r\n                                dangerMode: true,\r\n                              })\r\n                              .then((willDelete) => {\r\n                                if (willDelete) {\r\n                                  swal('Poof! Puxando Revendedor!', {\r\n                                    icon: 'success',\r\n                                    //entra no\r\n                                  });\r\n                                  window.location.href = 'puxarevenda.php?id='+id;\r\n                                } else {\r\n                                  swal('Seu Revendedor está seguro!');\r\n                                }\r\n                              });\r\n                            }       \r\n    function reativar(id) {\r\n      swal({\r\n                                title: 'Tem certeza?',\r\n                                text: 'Você não poderá reverter isso!',\r\n                                icon: 'warning',\r\n                                buttons: true,\r\n                                dangerMode: true,\r\n                              })\r\n                              .then((willDelete) => {\r\n                                if (willDelete) {\r\n                                  swal('Poof! Reativando Revendedor!', {\r\n                                    icon: 'success',\r\n                                  });\r\n                                  window.location.href = 'reativarrevenda.php?id='+id;\r\n                                } else {\r\n                                  swal('Seu Revendedor está seguro!');\r\n                                }\r\n                              });\r\n                            }\r\n    function suspender(id) {\r\n      swal({\r\n                                title: 'Tem certeza?',\r\n                                text: 'Você não poderá reverter isso!',\r\n                                icon: 'warning',\r\n                                buttons: true,\r\n                                dangerMode: true,\r\n                              })\r\n                              .then((willDelete) => {\r\n                                if (willDelete) {\r\n                                  swal('Poof! Suspender Revendedor!', {\r\n                                    icon: 'success',\r\n                                  });\r\n                                  window.location.href = 'suspenderrevenda.php?id='+id;\r\n                                } else {\r\n                                  swal('Seu Revendedor está seguro!');\r\n                                }\r\n                              });\r\n                            }\r\nfunction deletar(id) {\r\n  swal({\r\n                                title: 'Tem certeza?',\r\n                                text: 'Você não poderá reverter isso!',\r\n                                icon: 'warning',\r\n                                buttons: true,\r\n                                dangerMode: true,\r\n                              })\r\n                              .then((willDelete) => {\r\n                                if (willDelete) {\r\n                                  swal('Poof! Deletando Revendedor!', {\r\n                                    icon: 'success',\r\n                                  });\r\n                                  window.location.href = 'excluirrevenda.php?id='+id;\r\n                                } else {\r\n                                  swal('Seu Revendedor está seguro!');\r\n                                }\r\n                              });\r\n                            }\r\n\r\n  </script>\r\n \r\n\r\n  </tbody>\r\n    </table>\r\n    </div>\r\n    \r\n    \r\n    </div>\r\n    </div>\r\n    \r\n\r\n                                        \r\n                    \r\n                          \r\n                                        \r\n                        \r\n    <!-- END: Content-->\r\n    <script src=\"cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js\"></script>\r\n    <script src=\"../app-assets/js/scripts/datatables/datatable.js\"></script>\r\n    <script>\r\n    \$('#myTable').DataTable({\r\n\r\n        /* traduzir somente */\r\n        \"language\": {\r\n            \"lengthMenu\": \"Mostrar _MENU_ registros por página\",\r\n            \"zeroRecords\": \"Nenhum registro encontrado\",\r\n            \"info\": \"Mostrando página _PAGE_ de _PAGES_\",\r\n            \"infoEmpty\": \"Nenhum registro disponível\",\r\n            \"infoFiltered\": \"(filtrado de _MAX_ registros no total)\",\r\n            \"search\": \"Pesquisar:\",\r\n            \"paginate\": {\r\n                \"first\": \"\",\r\n                \"last\": \"\",\r\n                \"next\": \"\",\r\n                \"previous\": \"\"\r\n            }\r\n        }\r\n    \r\n    });\r\n\r\n</script>\r\n<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n<!-- ajax -->\r\n<script src=\"../../../app-assets/js/scripts/pages/bootstrap-toast.js\"></script>\r\n                       <script src=\"../../../app-assets/js/scripts/extensions/sweet-alerts.js\"></script>\r\n                       <script src=\"../app-assets/sweetalert.min.js\"></script>\r\n                       <div class=\"modal fade\" id=\"modalEditar\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalScrollableTitle\" aria-hidden=\"true\">\r\n    <div class=\"modal-dialog modal-dialog-scrollable\" role=\"document\">\r\n        <div class=\"modal-content\">\r\n            <div class=\"modal-header\">\r\n                <h5 class=\"modal-title\" id=\"exampleModalScrollableTitle\">Editar Revendedor</h5>\r\n                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\r\n                    <span aria-hidden=\"true\">&times;</span>\r\n                </button>\r\n            </div>\r\n            <div class=\"modal-body\">\r\n                <!-- Adicione aqui os campos que deseja editar -->\r\n                <label for=\"campo1\">Campo 1</label>\r\n                <input type=\"text\" class=\"form-control\" id=\"campo1\">\r\n\r\n                <label for=\"campo2\">Campo 2</label>\r\n                <input type=\"text\" class=\"form-control\" id=\"campo2\">\r\n            </div>\r\n            <div class=\"modal-footer\">\r\n                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Fechar</button>\r\n                <button type=\"button\" class=\"btn btn-primary\">Salvar mudanças</button>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>