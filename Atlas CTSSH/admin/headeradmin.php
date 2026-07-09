<?php


echo "<!DOCTYPE html>\r\n<html class=\"loading\" lang=\"pt-br\" data-textdirection=\"ltr\">\r\n\r\n<head>\r\n  \r\n";
error_reporting(0);
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../index.php");
    exit;
}
include_once "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if ($_SESSION["login"] == "admin") {
    $sql = "SELECT * FROM configs WHERE id = '1'";
    $result = $conn->query($sql);
    if (0 < $result->num_rows) {
        while ($row = $result->fetch_assoc()) {
            $nomepainel = $row["nomepainel"];
            $logo = $row["logo"];
            $icon = $row["icon"];
            $csspersonali = $row["corfundologo"];
        }
    }
    $sqltoken = "ALTER TABLE accounts ADD COLUMN IF NOT EXISTS tokenvenda TEXT NOT NULL DEFAULT '0'";
    mysqli_query($conn, $sqltoken);
    $sqldeltoken = "DROP TABLE IF EXISTS limiter";
    mysqli_query($conn, $sqldeltoken);
    $sql2 = "SELECT * FROM atribuidos WHERE byid = '" . $_SESSION["iduser"] . "'";
    $result2 = mysqli_query($conn, $sql2);
    $totalrevenda = mysqli_num_rows($result2);
    $sql452 = "SELECT * FROM accounts WHERE id = '" . $_SESSION["iduser"] . "'";
    $result452 = mysqli_query($conn, $sql452);
    $row452 = mysqli_fetch_assoc($result452);
    $tokenvenda = $row452["tokenvenda"];
    $idcategoriacompra = $row452["tempo"];
    $acesstoken = $row452["accesstoken"];
    $acesstokenpaghiper = $row452["acesstokenpaghiper"];
    if ($idcategoriacompra == NULL || $idcategoriacompra == "") {
        $updatecategoria = "UPDATE accounts SET tempo = '1' WHERE id = '" . $_SESSION["iduser"] . "'";
        mysqli_query($conn, $updatecategoria);
    }
    $sqltoken2 = "ALTER TABLE pagamentos\r\nADD COLUMN IF NOT EXISTS formadepag TEXT DEFAULT '1',\r\nADD COLUMN IF NOT EXISTS tokenpaghiper TEXT DEFAULT NULL";
    mysqli_query($conn, $sqltoken2);
    $sql3 = "SELECT * FROM ssh_accounts WHERE byid = '" . $_SESSION["iduser"] . "'";
    $result3 = mysqli_query($conn, $sql3);
    $totalusuarios = mysqli_num_rows($result3);
    $sql44 = "SELECT * FROM ssh_accounts";
    $result44 = mysqli_query($conn, $sql44);
    $totalusuariosglobal = mysqli_num_rows($result44);
    $slq3 = "SELECT sum(valor) AS valor  FROM pagamentos where byid='" . $_SESSION["iduser"] . "' and status='Aprovado'";
    $result3 = mysqli_query($conn, $slq3);
    $row3 = mysqli_fetch_assoc($result3);
    $totalvendido = $row3["valor"];
    $totalvendido = number_format($totalvendido, 2, ",", ".");
    $sql2 = "SELECT * FROM atribuidos";
    $result2 = mysqli_query($conn, $sql2);
    $totalrevendedores = mysqli_num_rows($result2);
    $sql4 = "SELECT * FROM servidores";
    $result4 = mysqli_query($conn, $sql4);
    $totalservidores = mysqli_num_rows($result4);
    $sql5 = "SELECT * FROM logs";
    $result5 = mysqli_query($conn, $sql5);
    $totallogs = mysqli_num_rows($result5);
    date_default_timezone_set("America/Sao_Paulo");
    $data = date("d/m/Y");
    $slq4 = "SELECT sum(valor) AS valor  FROM pagamentos where byid='" . $_SESSION["iduser"] . "' and status='Aprovado' and data >= '" . $data . " 00:00:00' and data <= '" . $data . " 23:59:59'";
    $result4 = mysqli_query($conn, $slq4);
    $row4 = mysqli_fetch_assoc($result4);
    $totalvendidohoje = $row4["valor"];
    $data = date("Y-m-d H:i:s");
    $slq5 = "SELECT * FROM ssh_accounts WHERE byid = '" . $_SESSION["iduser"] . "' and expira < '" . $data . "'";
    $result5 = mysqli_query($conn, $slq5);
    $totalvencidos = mysqli_num_rows($result5);
    $sql16 = "SELECT * FROM ssh_accounts WHERE status = 'Online'";
    $result16 = mysqli_query($conn, $sql16);
    $row16 = mysqli_fetch_assoc($result16);
    $totalonline = mysqli_num_rows($result16);
    if ($totalonline == NULL) {
        $totalonline = 0;
    }
    if ($totalvendido == NULL) {
        $totalvendido = 0;
    }
    $sqltoken = "ALTER TABLE accounts\r\n    ADD COLUMN IF NOT EXISTS acesstokenpaghiper TEXT DEFAULT NULL,\r\n    ADD COLUMN IF NOT EXISTS formadepag TEXT DEFAULT NULL,\r\n    ADD COLUMN IF NOT EXISTS tokenpaghiper TEXT DEFAULT NULL";
    mysqli_query($conn, $sqltoken);
    $sqltoken2 = "ALTER TABLE pagamentos\r\nADD COLUMN IF NOT EXISTS formadepag TEXT DEFAULT '1',\r\nADD COLUMN IF NOT EXISTS tokenpaghiper TEXT DEFAULT NULL";
    mysqli_query($conn, $sqltoken2);
    $sqltoken3 = "ALTER TABLE atribuidos\r\nADD COLUMN IF NOT EXISTS valormensal TEXT DEFAULT NULL";
    mysqli_query($conn, $sqltoken3);
    $sqltoken4 = "ALTER TABLE ssh_accounts\r\nADD COLUMN IF NOT EXISTS valormensal TEXT DEFAULT NULL";
    mysqli_query($conn, $sqltoken4);
    mysqli_query($conn, $sqltoken);
    date_default_timezone_set("America/Sao_Paulo");
    $data = date("d-m-Y H:i:s", strtotime("-1 hour"));
    $slq5 = "DELETE FROM pagamentos WHERE status = 'Aguardando Pagamento' and data < '" . $data . "'";
    $result5 = mysqli_query($conn, $slq5);
    echo "    ";
    echo "    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, user-scalable=0\">\r\n    <title>";
    echo $nomepainel;
    echo " - Painel Administrativo</title>\r\n    <link rel=\"apple-touch-icon\" href=\"";
    echo $icon;
    echo "\">\r\n    <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"";
    echo $icon;
    echo "\">\r\n    <link href=\"https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700\" rel=\"stylesheet\">\r\n\r\n    <!-- BEGIN: Vendor CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/vendors.min.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/charts/apexcharts.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/extensions/dragula.min.css\">\r\n    <!-- END: Vendor CSS-->\r\n\r\n    <!-- BEGIN: Theme CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap-extended.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/colors.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/components.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/semi-dark-layout.css\">\r\n    <!-- END: Theme CSS-->\r\n\r\n    <!-- BEGIN: Page CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/core/menu/menu-types/vertical-menu.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/pages/dashboard-analytics.css\">\r\n    <!-- END: Page CSS-->\r\n\r\n    <!-- BEGIN: Custom CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../atlas-assets/css/style.css\">\r\n    <!-- END: Custom CSS-->\r\n\r\n\r\n</head>\r\n  <style>\r\n        ";
    echo $csspersonali;
    echo "    </style>\r\n    \r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\" >\r\n\r\n    <!-- BEGIN: Header-->\r\n    <div class=\"header-navbar-shadow\"></div>\r\n    <nav class=\"header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top navbar-dark\">\r\n        <div class=\"navbar-wrapper\">\r\n            <div class=\"navbar-container content\">\r\n                <div class=\"navbar-collapse\" id=\"navbar-mobile\">\r\n                    <div class=\"mr-auto float-left bookmark-wrapper d-flex align-items-center\">\r\n                        <ul class=\"nav navbar-nav\">\r\n                            <li class=\"nav-item mobile-menu d-xl-none mr-auto\"><a class=\"nav-link nav-menu-main menu-toggle hidden-xs\" href=\"#\"><i class=\"ficon bx bx-menu\"></i></a></li>\r\n                        </ul>\r\n                        <div class=\"header-msg\">\r\n\t\t\t\t\t\t<h7 class=\"header-title\">\r\n\t\t\t\t\t\t<marquee class=\"dhr-marquee\" direction=\"left\">Seja Bem Vindo ao ";
    echo $nomepainel;
    echo "</marquee></h7>\r\n\t\t\t\t\t\t</div> <!-- FIM -->\r\n                       \r\n                    </div>\r\n                    <li class=\"nav-item dropdown d-none d-lg-block\">\r\n            <a class=\"btn btn-outline-success\" href=\"criarteste.php\">+ Teste Rapido</a>\r\n          </li>\r\n                    <ul class=\"nav navbar-nav float-right\">    \r\n                        </li>\r\n                        <li class=\"dropdown dropdown-user nav-item\"><a class=\"dropdown-toggle nav-link dropdown-user-link\" href=\"#\" data-toggle=\"dropdown\">\r\n                                <div class=\"user-nav d-sm-flex d-none\"><span class=\"user-name\">";
    echo $_SESSION["login"];
    echo "</span></div><span><div class=\"avatar bg-success mr-1\">\r\n                                            <div class=\"avatar-content\">\r\n                                            ";
    $nome = $_SESSION["login"];
    $primeira_letra = $nome[0];
    echo $primeira_letra;
    echo "                                            </div>\r\n                                        </div>\r\n                            </a>\r\n                            <div class=\"dropdown-menu dropdown-menu-right pb-0\"><a class=\"dropdown-item\" href=\"editconta.php\"><i class=\"bx bx-user mr-50\"></i> Conta</a>\r\n                                <div class=\"dropdown-divider mb-0\"></div><a class=\"dropdown-item\" href=\"../logout.php\"><i class=\"bx bx-power-off mr-50\"></i> Sair</a>\r\n                            </div>\r\n                        </li>\r\n                    </ul>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </nav>\r\n    <br>\r\n    <!-- END: Header-->\r\n\r\n\r\n    <!-- BEGIN: Main Menu-->\r\n    <div class=\"main-menu menu-fixed menu-dark menu-accordion menu-shadow\" data-scroll-to-active=\"true\">\r\n        <div class=\"navbar-header\">\r\n            <ul class=\"nav navbar-nav flex-row\">\r\n                <li class=\"nav-item mr-auto\"><a class=\"navbar-brand\" href=\"home.php\">\r\n                  <style>\r\n                    .logo {\r\n                      width: 170px;\r\n\r\n                    }\r\n                  </style>\r\n                  <center>\r\n                        <img class=\"logo\" src=\"";
    echo $logo;
    echo "\" /></center>\r\n                        <!-- <h2 class=\"brand-text mb-0\"><img class=\"logo\" src=\"";
    echo $logo;
    echo "\" /></h2> -->\r\n                    </a></li>\r\n                <li class=\"nav-item nav-toggle\"><a class=\"nav-link modern-nav-toggle pr-0\" data-toggle=\"collapse\"><i class=\"bx bx-x d-block d-xl-none font-medium-4 primary\"></i><i class=\"toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary\" data-ticon=\"bx-disc\"></i></a></li>\r\n            </ul>\r\n        </div>\r\n        <div class=\"shadow-bottom\"></div>\r\n        <div class=\"main-menu-content\">\r\n            <ul class=\"navigation navigation-main\" id=\"main-menu-navigation\" data-menu=\"menu-navigation\" data-icon-style=\"lines\">\r\n                <li class=\" nav-item\"><a href=\"home.php\"><i class=\"menu-livicon\" data-icon=\"desktop\"></i><span class=\"menu-title\" data-i18n=\"Dashboard\">Pagina Inicial</span></a>\r\n\r\n                </li>\r\n                <li class=\" navigation-header\"><span>Usuarios</span>\r\n                </li>\r\n                <li class=\" nav-item\"><a href=\"#\"><i class=\"menu-livicon\" data-icon=\"user\"></i><span class=\"menu-title\">Gerenciar Usuarios</span></a>\r\n                <ul class=\"menu-content\">\r\n                        <li><a href=\"criarusuario.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Criar Usuario</span></a>\r\n                        </li>\r\n                        <li><a href=\"criarteste.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Criar Teste</span></a>\r\n                        </li>\r\n                        <li><a href=\"listarusuarios.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Lista de Usuarios</span></a>\r\n                        </li>\r\n                        <li><a href=\"listaexpirados.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Lista de Expirados</span></a>\r\n                        </li>\r\n                        <li><a href=\"onlines.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Lista de Onlines</span></a>\r\n                        </li>\r\n                        <li><a href=\"limiter.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Listar Limiter</span></a>\r\n                        </li>\r\n                    </ul>\r\n                </li>\r\n                <li class=\" nav-item\"><a href=\"#\"><i class=\"menu-livicon\" data-icon=\"users\"></i><span class=\"menu-title\">Revendedores</span></a>\r\n                    <ul class=\"menu-content\">\r\n                        <li><a href=\"criarrevenda.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\" >Criar Revenda</span></a>\r\n                        </li>\r\n                        <li><a href=\"listarrevendedores.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Listar Revendedores</span></a>\r\n                        </li>\r\n                        <li><a href=\"listartodosrevendedores.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Listar Todos Revendedores</span></a>\r\n                        </li>\r\n                    </ul>\r\n                </li>\r\n                <li class=\" navigation-header\"><span>Pagamentos</span>\r\n                </li>\r\n                <li class=\" nav-item\"><a href=\"#\"><i class=\"menu-livicon\" data-icon=\"us-dollar\"></i><span class=\"menu-title\">Pagamentos</span></a>\r\n                <ul class=\"menu-content\">\r\n                    <li><a href=\"formaspag.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Configurar Pagamentos</span></a>\r\n                </li>\r\n                <li><a href=\"listadepag.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\" data-i18n=\"Typography\">Listar Seus Pagamentos</span></a>\r\n            </li>\r\n            <li><a href=\"listadetodospag.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\" data-i18n=\"Text Utilities\">Listar Todos Pagamentos</span></a>\r\n        </li>\r\n        <li><a href=\"cupons.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\" data-i18n=\"Syntax Highlighter\">Cupom de Desconto</span></a>\r\n    </li>\r\n\r\n</ul>\r\n</li>\r\n<li class=\" navigation-header\"><span>Servidores</span>\r\n                </li>\r\n<li class=\" nav-item\"><a href=\"servidores.php\"><i class=\"menu-livicon\" data-icon=\"cpu\"></i><span class=\"menu-title\">Servidores</span></a>\r\n</li>\r\n\r\n\r\n</a>\r\n\r\n\r\n\r\n<li class=\" navigation-header\"><span>Logs</span>\r\n                </li>\r\n                <li class=\" nav-item\"><a href=\"logs.php\"><i class=\"menu-livicon\" data-icon=\"priority-low\"></i><span class=\"menu-title\">Logs</span></a>\r\n                </li>\r\n\r\n                \r\n                \r\n                \r\n                <li class=\" navigation-header\"><span>Configurações</span>\r\n                <li class=\" nav-item\"><a href=\"editconta.php\"><i class=\"menu-livicon\" data-icon=\"wrench\"></i><span class=\"menu-title\">Conta</span></a>\r\n                </li>\r\n                </li>\r\n                \r\n                <li class=\" nav-item\"><a href=\"configpainel.php\"><i class=\"menu-livicon\" data-icon=\"settings\"></i><span class=\"menu-title\">Editar Painel</span></a>\r\n                </li>\r\n                <li class=\" nav-item\"><a href=\"editorpainel.php\"><i class=\"menu-livicon\" data-icon=\"brush\"></i><span class=\"menu-title\">Editor Css</span></a>\r\n                </li>\r\n                \r\n                <li class=\" nav-item\"><a href=\"checkuserconf.php\"><i class=\"menu-livicon\" data-icon=\"unlink\"></i><span class=\"menu-title\" data-i18n=\"Form Wizard\">CheckUser</span></a>\r\n                </li>\r\n                <li class=\" nav-item\"><a href=\"../logout.php\"><i class=\"menu-livicon\" data-icon=\"morph-login2\"></i><span class=\"menu-title\" data-i18n=\"Form Validation\">Sair</span></a>\r\n                </li>\r\n                \r\n            </ul>\r\n        </div>\r\n    </div>\r\n    \r\n    <!-- BEGIN: Content-->\r\n    <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <!-- Dashboard Ecommerce Starts -->\r\n        \r\n            <div class=\"row\">\r\n              <div class=\"col-12 grid-margin stretch-card\">\r\n                \r\n              </div>\r\n            </div>\r\n            <style>\r\n            .card-body {\r\n              border: 1px solid #ebedf2;\r\n              border-radius: 0.25rem;\r\n  \r\n  }\r\n</style>\r\n            <div class=\"row\">\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\" onclick=\"redirecionaroonlines()\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
    echo $totalonline;
    echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Onlines</p>\r\n                        </div>\r\n                        <script>\r\nfunction redirecionaroonlines() {\r\n    window.location.href = \"onlines.php\";\r\n}\r\n</script>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success \">\r\n                          <a href=\"onlines.php\" class=\"mdi mdi-arrow-top-right icon-item\"></a>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Usuarios Onlines</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              \r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">Versão</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">1.0.2</p>\r\n                          <p style='color: #ffff; margin-left: 20px; margin-bottom: 1px;  font-size: 12px;'></p>\r\n                        </div>\r\n                        \r\n                        <button type=\"button\" class=\"badge rounded-pill bg-success\" onclick=\"atualizar()\">ATUALIZAR</button>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success \">\r\n                        <i class=\"mdi mdi-format-vertical-align-bottom\"></i>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    \r\n                  </div>\r\n                </div>\r\n              </div>\r\n              ";
    $_SESSION["senhaatualizar"] = gerar_token();
    $domain = $_SERVER["HTTP_HOST"];
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") {
        $protocol = "https://";
    } else {
        $protocol = "http://";
    }
    echo "              <script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js\" integrity=\"sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>\r\n\r\n              <script src=\"../app-assets/sweetalert.min.js\"></script>\r\n            <script>\r\n            function atualizar() {\r\n    var senhaatualizar = \"";
    echo $_SESSION["senhaatualizar"];
    echo "\";\r\n    var domain = \"";
    echo $domain;
    echo "\";\r\n\r\n    // Exibe uma mensagem de confirmação antes de atualizar\r\n    swal({\r\n        title: \"Deseja realmente atualizar?\",\r\n        text: \"Ao clicar em OK, os dados serão atualizados.\",\r\n        icon: \"warning\",\r\n        buttons: [\"Cancelar\", \"OK\"],\r\n        dangerMode: true,\r\n    }).then(function (confirm) {\r\n        if (confirm) {\r\n            // O usuário confirmou a atualização, envie a solicitação AJAX\r\n            \$.ajax({\r\n                url: '";
    echo $protocol;
    echo $domain;
    echo "/atualizar.php',\r\n                type: 'POST',\r\n                data: {\r\n                    senhaatualizar: senhaatualizar,\r\n                    domain: domain\r\n                },\r\n                success: function (data) {\r\n                    if (data) {\r\n                        // Mostra a mensagem de sucesso\r\n                        swal(\"Atualizado com sucesso!\", \"Clique em OK para atualizar a página!\", \"success\").then(function () {\r\n                            location.reload();\r\n                        });\r\n                    } else {\r\n                        // Mostra a mensagem de erro\r\n                        swal(\"Erro na atualização!\", \"Houve um erro ao atualizar os dados.\", \"error\");\r\n                    }\r\n                },\r\n                error: function () {\r\n                    // Mostra a mensagem de erro em caso de falha na requisição AJAX\r\n                    swal(\"Erro na atualização!\", \"Houve um erro na requisição.\", \"error\");\r\n                }\r\n            });\r\n        }\r\n    });\r\n}\r\n</script>\r\n\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\" onclick=\"redirecionarrevendedores()\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <script>\r\nfunction redirecionarrevendedores() {\r\n    window.location.href = \"listarrevendedores.php\";\r\n}\r\n</script>\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
    echo $totalrevenda;
    echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Revendedores</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <a href=\"listarrevendedores.php\" class=\"mdi mdi-arrow-top-right icon-item\"></a>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Revendedores do Admin</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n              <div class=\"card\" >\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
    echo $totalusuariosglobal;
    echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Global</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <span class=\"mdi mdi-arrow-top-right icon-item\"></span>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Todos Usuarios</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\" onclick=\"redirecionarusuarios()\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <script>\r\nfunction redirecionarusuarios() {\r\n    window.location.href = \"listarusuarios.php\";\r\n}\r\n</script>\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
    echo $totalusuarios;
    echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Usuarios</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <a href=\"listarusuarios.php\" class=\"mdi mdi-arrow-top-right icon-item\"></a>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Seus Usuarios</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\" onclick=\"redirecionarservidores()\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <script>\r\nfunction redirecionarservidores() {\r\n    window.location.href = \"servidores.php\";\r\n}\r\n</script>\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
    echo $totalservidores;
    echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Servidores</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <span class=\"mdi mdi-arrow-top-right icon-item\"></span>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Total de Servidores</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\" onclick=\"redirecionarrevendedores()\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <script>\r\nfunction redirecionarrevendedores() {\r\n    window.location.href = \"listarrevendedores.php\";\r\n}\r\n</script>\r\n                            <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
    echo $totalrevendedores;
    echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Revendedores</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <a href=\"listarrevendedores.php\" class=\"mdi mdi-arrow-top-right icon-item\"></a>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Revendedores no Painel</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
    echo $totalvendido;
    echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">R\$</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <span class=\"mdi mdi-arrow-top-right icon-item\"></span>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Total Vendido</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              \r\n              </div>\r\n              \r\n              ";
    if ($acesstoken != "" || $acesstokenpaghiper != "") {
        echo "              <div class=\"content-body\">\r\n                    <section id=\"divider-colors\">\r\n                            <div class=\"col-12\">\r\n                            <div class=\"card\"style=\"border: 2px solid #5A8DEF;\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Link de Compra</h4>\r\n                                </div>\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <p>\r\n                                            Use esses Links para seus clientes comprarem seus produtos.\r\n                                        </p>\r\n                                        <div class=\"divider divider-primary\">\r\n                                            <div class=\"divider-text\">Para Novos Revendedores</div>\r\n                                            <input type=\"text\" class=\"form-control\" value=\"https://";
        echo $_SERVER["HTTP_HOST"];
        echo "/revenda.php?token=";
        echo $tokenvenda;
        echo "\" readonly>\r\n                                        </div>\r\n                                        <div class=\"divider divider-primary\">\r\n                                            <div class=\"divider-text\">Link Bot Vendas</div>\r\n                                            <input type=\"text\" class=\"form-control\" value=\"https://";
        echo $_SERVER["HTTP_HOST"];
        echo "/comprar.php?token=";
        echo $tokenvenda;
        echo "\" readonly>\r\n                                        </div>\r\n                                              <form action=\"headeradmin.php\" method=\"post\">\r\n                                        <div class=\"divider divider-warning\">\r\n                                            <button class=\"btn btn-warning\" type=\"submit\" name=\"gerarlink\" id=\"gerarlink\">Gerar Novo Link</button>\r\n                                        </div>\r\n                                        <div class=\"divider divider-success\">\r\n                                            <div class=\"divider-text\">Id da Categoria Para Compra Automatica</div>\r\n                                            <input type=\"text\" class=\"form-control\" name=\"categoriacompra\" value=\"";
        echo $idcategoriacompra;
        echo "\">\r\n                                        </div>\r\n                                        <div class=\"divider divider-warning\">\r\n                                            <button class=\"btn btn-warning\" type=\"submit\" name=\"salvarcate\" id=\"salvarcate\">Salvar Categoria</button>\r\n                                        \r\n                                        </form>\r\n\r\n                                        </div>\r\n                                        ";
        if (isset($_POST["gerarlink"])) {
            $codigo = rand(0, 0);
            $id = $_SESSION["iduser"];
            $categoriacompra = $_POST["categoriacompra"];
            $sql = "UPDATE accounts SET tokenvenda = '" . $codigo . "', tempo = '" . $categoriacompra . "' WHERE id = '" . $id . "'";
            $result = $conn->query($sql);
            echo "<meta http-equiv='refresh' content='0'>";
        }
        if (isset($_POST["salvarcate"])) {
            $id = $_SESSION["iduser"];
            $categoriacompra = $_POST["categoriacompra"];
            $sql = "UPDATE accounts SET tempo = '" . $categoriacompra . "' WHERE id = '" . $id . "'";
            $result = $conn->query($sql);
            echo "<meta http-equiv='refresh' content='0'>";
        }
        echo "                                </div>\r\n                            </div>\r\n                        </div>\r\n                        </div>\r\n                </section>\r\n                <!-- Divider Colors Ends -->\r\n            </div>\r\n        </div>\r\n        ";
    }
    echo "\r\n\r\n\r\n        \r\n\r\n                    \r\n                        \r\n                <!-- table Transactions start -->\r\n                <section id=\"table-transactions\">\r\n                    <div class=\"card\">\r\n                        <div class=\"card-header\">\r\n                            <!-- head -->\r\n                            <h5 class=\"card-title\">Servidores</h5>\r\n                            <!-- Single Date Picker and button -->\r\n                            <div class=\"heading-elements\">\r\n                                <ul class=\"list-inline mb-0\">\r\n                                    <input type=\"text\" class=\"form-control\" placeholder=\"Pesquisar\" aria-label=\"Pesquisar\" aria-describedby=\"button-addon2\" id=\"pesquisar\" onkeyup=\"pesquisar()\">\r\n                                </ul>\r\n                            </div>\r\n\r\n                            \r\n\r\n\r\n                        </div>\r\n                        <!-- datatable start -->\r\n                                ";
    $sql = "SELECT * FROM servidores ";
    $result = $conn->query($sql);
    echo "                        <div class=\"table-responsive\">\r\n                            <table id=\"table-extended-transactions\" class=\"table mb-0\">\r\n                                <thead>\r\n                                    <tr>\r\n                                        <th>Nome</th>\r\n                                        <th>Categoria</th>\r\n                                        <th>ip</th>\r\n                                        <th>tamanho</th>\r\n                                        <th>Onlines</th>\r\n                                    </tr>\r\n                                </thead>\r\n                                <tbody>\r\n                                    ";
    while ($user_data = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td class='text-bold-600'>" . $user_data["nome"] . "</td>";
        echo "<td>" . $user_data["subid"] . "</td>";
        echo "<td>" . $user_data["ip"] . "</td>";
        echo "<td>" . $user_data["serverram"] . " RAM/ " . $user_data["servercpu"] . " CPU</td>";
        echo "<td>" . $user_data["onlines"] . "</td>";
        echo "</tr>";
    }
    echo "                                    \r\n                                </tbody>\r\n                            </table>\r\n                        </div>\r\n                    </div>\r\n                </section>\r\n            </div>\r\n        </div>\r\n    </div></div>\r\n\r\n            </div>\r\n        </div>\r\n    </div>\r\n\r\n    </div>\r\n    <div class=\"sidenav-overlay\"></div>\r\n    <div class=\"drag-target\"></div>\r\n\r\n    <script src=\"../../../app-assets/vendors/js/vendors.min.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/configs/vertical-menu-dark.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app-menu.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/components.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/footer.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/forms/number-input.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/datatables.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/buttons.html5.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/buttons.print.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/pdfmake.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/vfs_fonts.js\"></script>\r\n</body>\r\n<script>\r\nsetInterval(() => {\r\n  fetch('suspenderauto.php', {\r\n    method: 'POST',\r\n  })\r\n    .then(response => {\r\n      // Tratar a resposta, se necessário\r\n    })\r\n    .catch(error => {\r\n      // Tratar o erro, se necessário\r\n    });\r\n}, 10000); // 10000 milissegundos = 10 segundos\r\n</script>\r\n\r\n\r\n</html>";
} else {
    echo "<script>alert('Você não tem permissão para acessar essa página!');window.location.href='../logout.php';</script>";
    exit;
}
function gerar_token()
{
    $tokenvenda = md5(uniqid(rand(), true));
    return $tokenvenda;
}

?>