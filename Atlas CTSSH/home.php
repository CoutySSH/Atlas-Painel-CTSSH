<?php


echo "<script src=\"app-assets/sweetalert.min.js\"></script>\r\n   ";
include_once "atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT * FROM configs";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $nomepainel = $row["nomepainel"];
    $logo = $row["logo"];
    $icon = $row["icon"];
    $csspersonali = $row["corfundologo"];
}
error_reporting(0);
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}
if ($_SESSION["login"] == "admin") {
    header("Location: admin/home.php");
    exit;
}
$token = $_SESSION["token"];
$dominio = $_SERVER["HTTP_HOST"];
$sql1 = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["iduser"] . "'";
$result1 = mysqli_query($conn, $sql1);
while ($row1 = mysqli_fetch_assoc($result1)) {
    $vencimento = $row1["expira"];
    $vencimento = date("d/m/Y", strtotime($vencimento));
    $vencimentocheck = $row1["expira"];
    $_SESSION["expira"] = $vencimento;
    $_SESSION["limite"] = $row1["limite"];
    $_SESSION["tipo"] = $row1["tipo"];
    $suspenso = $row1["suspenso"];
    $tipo = $row1["tipo"];
    $_SESSION["byid"] = $row1["byid"];
}
if ($_SESSION["tipo"] == "Credito") {
    $_SESSION["tipo"] = "Seus Créditos";
    $_SESSION["expira"] = "Nunca";
} else {
    $_SESSION["tipo"] = "Seu Limite";
    if ($_SESSION["byid"] != "1") {
        $sql_suspenso = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["byid"] . "'";
        $result_suspenso = mysqli_query($conn, $sql_suspenso);
        while ($row_suspenso = mysqli_fetch_assoc($result_suspenso)) {
            $dataadmin = $row_suspenso["expira"];
        }
        if ($dataadmin < date("Y-m-d H:i:s")) {
            echo "<script>alert('Suspenso, entre em contato com o suporte para mais informações!')</script><script>window.location.href = 'logout.php';</script>";
        }
    }
}
if ($suspenso == "1") {
    echo "<script>alert('Suspenso, entre em contato com o suporte para mais informações!')</script><script>window.location.href = 'logout.php';</script>";
    exit;
}
$tokenvb = "SELECT * FROM accounts WHERE id = '" . $_SESSION["iduser"] . "'";
$resultvb = mysqli_query($conn, $tokenvb);
$rowvb = mysqli_fetch_assoc($resultvb);
$tokenvenda = $rowvb["tokenvenda"];
$accesstoken = $rowvb["accesstoken"];
$acesstokenpaghiper = $rowvb["acesstokenpaghiper"];
$sql2 = "SELECT * FROM atribuidos WHERE byid = '" . $_SESSION["iduser"] . "'";
$result2 = mysqli_query($conn, $sql2);
$totalrevenda = mysqli_num_rows($result2);
$sql3 = "SELECT * FROM ssh_accounts WHERE byid = '" . $_SESSION["iduser"] . "'";
$result3 = mysqli_query($conn, $sql3);
$totalusuarios = mysqli_num_rows($result3);
$slq3 = "SELECT sum(valor) AS valor  FROM pagamentos where byid='" . $_SESSION["iduser"] . "' and status='Aprovado'";
$result3 = mysqli_query($conn, $slq3);
$row3 = mysqli_fetch_assoc($result3);
$totalvendido = $row3["valor"];
$totalvendido = number_format($totalvendido, 2, ",", ".");
date_default_timezone_set("America/Sao_Paulo");
$data = date("d/m/Y");
if (isset($_SESSION["login"]) === "admin") {
    header("location: ../admin/home.php");
}
$slq4 = "SELECT sum(valor) AS valor  FROM pagamentos where byid='" . $_SESSION["iduser"] . "' and status='Aprovado' and data >= '" . $data . " 00:00:00' and data <= '" . $data . " 23:59:59'";
$result4 = mysqli_query($conn, $slq4);
$row4 = mysqli_fetch_assoc($result4);
$totalvendidohoje = $row4["valor"];
date_default_timezone_set("America/Sao_Paulo");
$data = date("Y-m-d H:i:s");
$slq5 = "SELECT * FROM ssh_accounts WHERE byid = '" . $_SESSION["iduser"] . "' and expira < '" . $data . "'";
$result5 = mysqli_query($conn, $slq5);
$totalvencidos = mysqli_num_rows($result5);
$sqlRevendedores = "SELECT * FROM accounts WHERE byid = '" . $_SESSION["iduser"] . "'";
$resultRevendedores = mysqli_query($conn, $sqlRevendedores);
$revendedoresIDs = [];
while ($rowRevendedor = mysqli_fetch_assoc($resultRevendedores)) {
    $revendedoresIDs[] = $rowRevendedor["id"];
}
if (!empty($revendedoresIDs)) {
    $sqlOnlineRevendedores = "SELECT * FROM ssh_accounts WHERE status = 'Online' AND byid IN (" . implode(",", $revendedoresIDs) . ")";
    $resultOnlineRevendedores = mysqli_query($conn, $sqlOnlineRevendedores);
    $totalOnlineRevendedores = mysqli_num_rows($resultOnlineRevendedores);
} else {
    $totalOnlineRevendedores = 0;
}
$sql16 = "SELECT * FROM ssh_accounts WHERE status = 'Online' and byid = '" . $_SESSION["iduser"] . "'";
$result16 = mysqli_query($conn, $sql16);
$totalonline = mysqli_num_rows($result16);
$totalonline = $totalOnlineRevendedores + $totalonline;
if ($totalonline == NULL) {
    $totalonline = 0;
}
if ($totalvendido == NULL) {
    $totalvendido = 0;
}
$sql_logs = "SELECT * FROM logs WHERE byid = '" . $_SESSION["iduser"] . "'";
$result_logs = mysqli_query($conn, $sql_logs);
$total_logs = mysqli_num_rows($result_logs);
$token = $_SESSION["token"];
$dominio = $_SERVER["HTTP_HOST"];
if (isset($_SESSION["LAST_ACTIVITY"]) && 1800 < time() - $_SESSION["LAST_ACTIVITY"]) {
    session_unset();
    session_destroy();
}
$_SESSION["LAST_ACTIVITY"] = time();
$slq2 = "SELECT sum(limite) AS numusuarios  FROM ssh_accounts where byid='" . $_SESSION["iduser"] . "' ";
$result = $conn->prepare($slq2);
$result->execute();
$result->bind_result($numusuarios);
$result->fetch();
$result->close();
$slq2 = "SELECT sum(limite) AS limiteusado  FROM atribuidos where byid='" . $_SESSION["iduser"] . "' ";
$result = $conn->prepare($slq2);
$result->execute();
$result->bind_result($limiteusado);
$result->fetch();
$result->close();
$somalimite = $numusuarios + $limiteusado;
$restante = $_SESSION["limite"] - $somalimite;
echo "\r\n    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, user-scalable=0\">\r\n    <title>";
echo $nomepainel;
echo " - Painel Administrativo</title>\r\n    <link rel=\"apple-touch-icon\" href=\"";
echo $icon;
echo "\">\r\n    <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"";
echo $icon;
echo "\">\r\n    <link href=\"https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700\" rel=\"stylesheet\">\r\n\r\n    <!-- BEGIN: Vendor CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/vendors.min.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/charts/apexcharts.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/extensions/dragula.min.css\">\r\n    <!-- END: Vendor CSS-->\r\n\r\n    <!-- BEGIN: Theme CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap-extended.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/colors.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/components.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/semi-dark-layout.css\">\r\n    <!-- END: Theme CSS-->\r\n\r\n    <!-- BEGIN: Page CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/core/menu/menu-types/vertical-menu.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/pages/dashboard-analytics.css\">\r\n    <!-- END: Page CSS-->\r\n\r\n    <!-- BEGIN: Custom CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../atlas-assets/css/style.css\">\r\n    <!-- END: Custom CSS-->\r\n\r\n</head>\r\n<style>\r\n        ";
echo $csspersonali;
echo "    </style>\r\n    \r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\" >\r\n<style>\r\n  .back-button {\r\n  position: fixed;\r\n  bottom: 20px;\r\n  right: 20px;\r\n  background-color: #007bff;\r\n  color: #fff;\r\n  border-radius: 50%;\r\n  width: 50px;\r\n  height: 50px;\r\n  display: flex;\r\n  justify-content: center;\r\n  align-items: center;\r\n  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);\r\n  z-index: 9999;\r\n  text-decoration: none;\r\n}\r\n\r\n.arrow {\r\n  border: solid white;\r\n  border-width: 0 3px 3px 0;\r\n  display: inline-block;\r\n  padding: 3px;\r\n  transform: rotate(135deg);\r\n  -webkit-transform: rotate(135deg);\r\n}\r\n</style>\r\n";
if (isset($_SESSION["admin564154156"])) {
    echo "<form method=\"post\" action=\"home.php\">\r\n  <button type=\"submit\" name=\"voltaradmin\" class=\"back-button btn btn-outline-primary\">\r\n    <span class=\"arrow\"></span>\r\n  </button>\r\n</form>\r\n";
}
echo "\r\n";
if (isset($_POST["voltaradmin"]) && isset($_SESSION["admin564154156"])) {
    $sqladmin = "SELECT * FROM accounts WHERE id = '1'";
    $resultadmin = $conn->query($sqladmin);
    $rowadmin = $resultadmin->fetch_assoc();
    $_SESSION["login"] = $rowadmin["login"];
    $_SESSION["senha"] = $rowadmin["senha"];
    $_SESSION["iduser"] = $rowadmin["id"];
    echo "<script>window.location.href='admin/home.php';</script>";
}
echo "\r\n    <!-- BEGIN: Header-->\r\n    <div class=\"header-navbar-shadow\"></div>\r\n    <nav class=\"header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top navbar-dark\">\r\n        <div class=\"navbar-wrapper\">\r\n            <div class=\"navbar-container content\">\r\n                <div class=\"navbar-collapse\" id=\"navbar-mobile\">\r\n                    <div class=\"mr-auto float-left bookmark-wrapper d-flex align-items-center\">\r\n                        <ul class=\"nav navbar-nav\">\r\n                            <li class=\"nav-item mobile-menu d-xl-none mr-auto\"><a class=\"nav-link nav-menu-main menu-toggle hidden-xs\" href=\"#\"><i class=\"ficon bx bx-menu\"></i></a></li>\r\n                        </ul>\r\n                       \r\n                    </div>\r\n                    <li class=\"nav-item dropdown d-none d-lg-block\">\r\n                      <!-- botao para voltar pro admin -->\r\n                      \r\n\r\n                <a class=\"btn btn-outline-success\" href=\"atlas/criarteste.php\">+ Teste Rapido</a>\r\n              </li>\r\n                    <ul class=\"nav navbar-nav float-right\">\r\n                       \r\n                        \r\n                        \r\n                             \r\n                        </li>\r\n                        <li class=\"dropdown dropdown-user nav-item\"><a class=\"dropdown-toggle nav-link dropdown-user-link\" href=\"#\" data-toggle=\"dropdown\">\r\n                                <div class=\"user-nav d-sm-flex d-none\"><span class=\"user-name\">";
echo $_SESSION["login"];
echo "</span></div><span><div class=\"avatar bg-success mr-1\">\r\n                                            <div class=\"avatar-content\">\r\n                                            ";
$nome = $_SESSION["login"];
$primeira_letra = $nome[0];
echo $primeira_letra;
echo "                                            </div>\r\n                                        </div>\r\n                            </a>\r\n                            <div class=\"dropdown-menu dropdown-menu-right pb-0\"><a class=\"dropdown-item\" href=\"atlas/editconta.php\"><i class=\"bx bx-user mr-50\"></i> Conta</a>\r\n                                <div class=\"dropdown-divider mb-0\"></div><a class=\"dropdown-item\" href=\"../logout.php\"><i class=\"bx bx-power-off mr-50\"></i> Sair</a>\r\n                            </div>\r\n                        </li>\r\n                    </ul>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </nav>\r\n    <br>\r\n    <!-- END: Header-->\r\n\r\n\r\n    <!-- BEGIN: Main Menu-->\r\n    <div class=\"main-menu menu-fixed menu-dark menu-accordion menu-shadow\" data-scroll-to-active=\"true\">\r\n        <div class=\"navbar-header\">\r\n            <ul class=\"nav navbar-nav flex-row\">\r\n                <li class=\"nav-item mr-auto\"><a class=\"navbar-brand\" href=\"../home.php\">\r\n                <style>\r\n                    .logo {\r\n                      width: 170px;\r\n\r\n                    }\r\n                  </style>\r\n                  <center>\r\n                        <img class=\"logo\" src=\"";
echo $logo;
echo "\" /></center>\r\n                        <!-- <h2 class=\"brand-text mb-0\"><img class=\"logo\" src=\"";
echo $logo;
echo "\" /></h2> -->\r\n                    </a></li>\r\n                <li class=\"nav-item nav-toggle\"><a class=\"nav-link modern-nav-toggle pr-0\" data-toggle=\"collapse\"><i class=\"bx bx-x d-block d-xl-none font-medium-4 primary\"></i><i class=\"toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary\" data-ticon=\"bx-disc\"></i></a></li>\r\n            </ul>\r\n        </div>\r\n        <div class=\"shadow-bottom\"></div>\r\n        <div class=\"main-menu-content\">\r\n            <ul class=\"navigation navigation-main\" id=\"main-menu-navigation\" data-menu=\"menu-navigation\" data-icon-style=\"lines\">\r\n                <li class=\" nav-item\"><a href=\"../home.php\"><i class=\"menu-livicon\" data-icon=\"desktop\"></i><span class=\"menu-title\" data-i18n=\"Dashboard\">Pagina Inicial</span></a>\r\n\r\n                </li>\r\n                <li class=\" navigation-header\"><span>Usuarios</span>\r\n                </li>\r\n                <li class=\" nav-item\"><a href=\"#\"><i class=\"menu-livicon\" data-icon=\"user\"></i><span class=\"menu-title\">Gerenciar Usuarios</span></a>\r\n                <ul class=\"menu-content\">\r\n                        <li><a href=\"atlas/criarusuario.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Criar Usuario</span></a>\r\n                        </li>\r\n                        <li><a href=\"atlas/criarteste.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Criar Teste</span></a>\r\n                        </li>\r\n                        <li><a href=\"atlas/listarusuarios.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Lista de Usuarios</span></a>\r\n                        </li>\r\n                        <li><a href=\"atlas/listaexpirados.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Lista de Expirados</span></a>\r\n                        </li>\r\n                        <li><a href=\"atlas/onlines.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Lista de Onlines</span></a>\r\n                        </li>\r\n                    </ul>\r\n                </li>\r\n                <li class=\" nav-item\"><a href=\"#\"><i class=\"menu-livicon\" data-icon=\"users\"></i><span class=\"menu-title\">Revendedores</span></a>\r\n                    <ul class=\"menu-content\">\r\n                        <li><a href=\"atlas/criarrevenda.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\" >Criar Revenda</span></a>\r\n                        </li>\r\n                        <li><a href=\"atlas/listarrevendedores.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Listar Revendedores</span></a>\r\n                        </li>\r\n                    </ul>\r\n                </li>\r\n                <li class=\" navigation-header\"><span>Pagamentos</span>\r\n                </li>\r\n                <li class=\" nav-item\"><a href=\"#\"><i class=\"menu-livicon\" data-icon=\"us-dollar\"></i><span class=\"menu-title\">Pagamentos</span></a>\r\n                <ul class=\"menu-content\">\r\n                    <li><a href=\"atlas/formaspag.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\">Configurar Pagamentos</span></a>\r\n                </li>\r\n                <li><a href=\"atlas/listadepag.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\" data-i18n=\"Typography\">Listar Seus Pagamentos</span></a>\r\n            </li>\r\n            <li><a href=\"atlas/cupons.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\" data-i18n=\"Syntax Highlighter\">Cupom de Desconto</span></a>\r\n        </li>\r\n        <li><a href=\"atlas/pagamento.php\"><i class=\"bx bx-right-arrow-alt\"></i><span class=\"menu-item\" data-i18n=\"Text Utilities\">Pagamento</span></a>\r\n    </li>\r\n\r\n</ul>\r\n</li>\r\n<li class=\" navigation-header\"><span>Logs</span>\r\n                </li>\r\n                <li class=\" nav-item\"><a href=\"atlas/logs.php\"><i class=\"menu-livicon\" data-icon=\"priority-low\"></i><span class=\"menu-title\">Logs</span></a>\r\n                </li>\r\n                <li class=\" navigation-header\"><span>Configurações</span>\r\n                <li class=\" nav-item\"><a href=\"atlas/editconta.php\"><i class=\"menu-livicon\" data-icon=\"wrench\"></i><span class=\"menu-title\">Conta</span></a>\r\n                </li>\r\n                <li class=\" nav-item\"><a href=\"../logout.php\"><i class=\"menu-livicon\" data-icon=\"morph-login2\"></i><span class=\"menu-title\" data-i18n=\"Form Validation\">Sair</span></a>\r\n                </li>\r\n                \r\n            </ul>\r\n        </div>\r\n    </div>\r\n\r\n     <!-- BEGIN: Content-->\r\n    <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <!-- Dashboard Ecommerce Starts -->\r\n        \r\n            <div class=\"row\">\r\n              <div class=\"col-12 grid-margin stretch-card\">\r\n                \r\n              </div>\r\n            </div>\r\n            <div class=\"row\">\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\" onclick=\"window.location='atlas/onlines.php';\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
echo $totalonline;
echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Onlines</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success \">\r\n                          <a href=\"onlines.php\" class=\"mdi mdi-arrow-top-right icon-item\"></a>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Usuarios Onlines</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\" onclick=\"window.location='atlas/listarrevendedores.php';\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
echo $totalrevenda;
echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Revendedores</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <a href=\"atlas/listarrevendedores.php\" class=\"mdi mdi-arrow-top-right icon-item\"></a>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Total de Revendedores</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n          \r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\" onclick=\"window.location='atlas/listarusuarios.php';\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
echo $totalusuarios;
echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Usuarios</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <a href=\"listarrevendedores.php\" class=\"mdi mdi-arrow-top-right icon-item\"></a>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Total de Usuarios</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\" >\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
echo $totalvendido;
echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">R\$</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <span class=\"mdi mdi-arrow-top-right icon-item\"></span>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Total de Vendas</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
echo $_SESSION["expira"];
echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\"></p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <a href=\"listarusuarios.php\" class=\"mdi mdi-arrow-top-right icon-item\"></a>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Vencimento</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\" onclick=\"window.location='atlas/listaexpirados.php';\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
echo $totalvencidos;
echo "</h3>  \r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Usuarios</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <span class=\"mdi mdi-arrow-top-right icon-item\"></span>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Total de Vencidos</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              <div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">";
echo $_SESSION["limite"];
echo "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Seu Limite</p>   \r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <a href=\"listarrevendedores.php\" class=\"mdi mdi-arrow-top-right icon-item\"></a>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Seu Limite Atual\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              ";
if ($_SESSION["tipo"] == "Seu Limite") {
    echo "<div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\">\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">" . $restante . "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Restante</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <span class=\"mdi mdi-arrow-top-right icon-item\"></span>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Limite Restante</h6>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n              ";
} else {
    echo "<div class=\"col-xl-3 col-sm-6 grid-margin stretch-card\">\r\n                <div class=\"card\" >\r\n                  <div class=\"card-body\">\r\n                    <div class=\"row\">\r\n                      <div class=\"col-9\">\r\n                        <div class=\"d-flex align-items-center align-self-start\">\r\n                          <h3 class=\"mb-0\">" . $total_logs . "</h3>\r\n                          <p class=\"text-success ml-2 mb-0 font-weight-medium\">Logs</p>\r\n                        </div>\r\n                      </div>\r\n                      <div class=\"col-3\">\r\n                        <div class=\"icon icon-box-success\">\r\n                          <span class=\"mdi mdi-arrow-top-right icon-item\"></span>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <h6 class=\"text-muted font-weight-normal\">Total de Logs</h6>\r\n                  </div>\r\n                </div>\r\n                </div>\r\n              </div>";
}
echo "              \r\n              ";
if ($accesstoken != "" || $acesstokenpaghiper != "") {
    echo "              <div class=\"content-body\" style=\"width: 100%; margin: 0 auto;\">\r\n                    <section id=\"divider-colors\">\r\n                            <div class=\"col-12\">\r\n                            <div class=\"card\"style=\"border: 2px solid #5A8DEF;\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Link de Compra</h4>\r\n                                </div>\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <p>\r\n                                            Use esses Links para seus clientes comprarem seus produtos.\r\n                                        </p>\r\n                                        <div class=\"divider divider-primary\">\r\n                                            <div class=\"divider-text\">Para Novos Revendedores</div>\r\n                                            <input type=\"text\" class=\"form-control\" value=\"https://";
    echo $_SERVER["HTTP_HOST"];
    echo "/revenda.php?token=";
    echo $tokenvenda;
    echo "\" readonly>\r\n                                        </div>\r\n                                        <div class=\"divider divider-warning\">\r\n                                            <div class=\"divider-text\">Link Bot Vendas</div>\r\n                                            <input type=\"text\" class=\"form-control\" value=\"https://";
    echo $_SERVER["HTTP_HOST"];
    echo "/comprar.php?token=";
    echo $tokenvenda;
    echo "\" readonly>\r\n                                        </div>\r\n                                        <form action=\"home.php\" method=\"post\">\r\n                                        <div class=\"divider divider-warning\">\r\n                                            <button class=\"btn btn-warning\" type=\"submit\" name=\"gerarlink\" id=\"gerarlink\">Gerar Novo Link</button>\r\n                                        \r\n                                        </form>\r\n\r\n                                        </div>\r\n                                        ";
    if (isset($_POST["gerarlink"])) {
        $codigo = rand(0, 0);
        $id = $_SESSION["iduser"];
        $sql = "UPDATE accounts SET tokenvenda = '" . $codigo . "' WHERE id = '" . $id . "'";
        $result = $conn->query($sql);
        echo "<meta http-equiv='refresh' content='0'>";
    }
    echo "                                </div>\r\n                            </div>\r\n                        </div>\r\n                        </div>\r\n                      </section>\r\n                    \r\n                <!-- Divider Colors Ends -->\r\n            </div>\r\n        </div>\r\n    \r\n    \r\n        ";
}
echo "        \r\n\r\n\r\n\r\n        \r\n\r\n                    \r\n                        \r\n</div>\r\n                    <div class=\"content-body\">\r\n                        \r\n                <!-- table Transactions start -->\r\n                <section id=\"table-transactions\">\r\n                    <div class=\"card\">\r\n                        <div class=\"card-header\">\r\n                            <!-- head -->\r\n                            <h5 class=\"card-title\">Pagamentos</h5>\r\n                            <!-- Single Date Picker and button -->\r\n                            <div class=\"heading-elements\">\r\n                                <ul class=\"list-inline mb-0\">\r\n                                    <input type=\"text\" class=\"form-control\" placeholder=\"Pesquisar\" aria-label=\"Pesquisar\" aria-describedby=\"button-addon2\" id=\"pesquisar\" onkeyup=\"pesquisar()\">\r\n                                </ul>\r\n                            </div>\r\n\r\n                            \r\n\r\n\r\n                        </div>\r\n                        <!-- datatable start -->\r\n                                ";
$sql = "SELECT * FROM pagamentos  where byid = '" . $_SESSION["iduser"] . "' ";
$result = $conn->query($sql);
echo "                        <div class=\"table-responsive\">\r\n                            <table id=\"table-extended-transactions\" class=\"table mb-0\">\r\n                                <thead>\r\n                                    <tr>\r\n                                        <th> Login </th>\r\n                            <th> Id do Pagamento </th>\r\n                            <th> Valor </th>\r\n                            <th> Detalhes </th>\r\n                            <th> Data e Hora </th>\r\n                            <th> Status </th>\r\n                                    </tr>\r\n                                </thead>\r\n                                <tbody>\r\n                                    ";
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
echo "                                    \r\n                                </tbody>\r\n                            </table>\r\n                        </div>\r\n                    </div>\r\n                </section>\r\n                </div>\r\n                \r\n            </div>\r\n        </div>\r\n    </div>\r\n\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <!-- BEGIN: Vendor JS-->\r\n    <script src=\"../../../app-assets/vendors/js/vendors.min.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js\"></script>\r\n    <!-- BEGIN Vendor JS-->\r\n\r\n    <!-- BEGIN: Page Vendor JS-->\r\n    <script src=\"../../../app-assets/vendors/js/charts/apexcharts.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/extensions/swiper.min.js\"></script>\r\n    <!-- END: Page Vendor JS-->\r\n\r\n    <!-- BEGIN: Theme JS-->\r\n    <script src=\"../../../app-assets/js/scripts/configs/vertical-menu-dark.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app-menu.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/components.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/footer.js\"></script>\r\n    <!-- END: Theme JS-->\r\n    <script>\r\n                             \$(document).ready(function(){\r\n                            \$(\"#pesquisar\").on(\"keyup\", function() {\r\n                            var value = \$(this).val().toLowerCase();\r\n                            \$(\"#table-extended-transactions tr\").filter(function() {\r\n                            \$(this).toggle(\$(this).text().toLowerCase().indexOf(value) > -1)\r\n                            });\r\n                            });\r\n                            });\r\n                                                    </script>\r\n    <!-- BEGIN: Page JS-->\r\n    <script src=\"../../../app-assets/js/scripts/pages/dashboard-ecommerce.js\"></script>\r\n    <!-- END: Page JS-->\r\n\r\n</body>\r\n<!-- END: Body-->\r\n\r\n</html>\r\n<script>\r\nsetInterval(() => {\r\n  fetch('admin/suspenderauto.php', {\r\n    method: 'POST',\r\n  })\r\n    .then(response => {\r\n      // Tratar a resposta, se necessário\r\n    })\r\n    .catch(error => {\r\n      // Tratar o erro, se necessário\r\n    });\r\n}, 10000); // 10000 milissegundos = 10 segundos\r\n</script>";

?>