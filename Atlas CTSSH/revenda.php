<?php


session_start();
error_reporting(0);
include_once "atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$_GET["token"] = anti_sql($_GET["token"]);
$_SESSION["tokenrevenda"] = $_GET["token"];
if (isset($_SESSION["tokenrevenda"])) {
    $token = $_SESSION["tokenrevenda"];
    $sqltoken = "ALTER TABLE accounts ADD COLUMN IF NOT EXISTS tokenvenda TEXT NOT NULL DEFAULT '0'";
    mysqli_query($conn, $sqltoken);
    $sql = "SELECT * FROM accounts WHERE tokenvenda = '" . $token . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (0 < mysqli_num_rows($result)) {
        $_SESSION["valorrevenda"] = $row["valorrevenda"];
    } else {
        echo "<script>alert('LINK INVÁLIDO!');</script>";
        exit;
    }
}
if ($_SESSION["valorrevenda"] == 0) {
    echo "<script>alert('Não Cadrastado!');</script>";
    exit;
}
echo "\r\n";
$sql = "SELECT * FROM configs";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$nomepainel = $row["nomepainel"];
$icon = $row["icon"];
echo "\r\n<!DOCTYPE html>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, user-scalable=0\">\r\n    <title>";
echo $nomepainel;
echo " - Planos</title>\r\n    <link rel=\"apple-touch-icon\" href=\"";
echo $icon;
echo "\">\r\n    <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"";
echo $icon;
echo "\">\r\n    <link href=\"https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700\" rel=\"stylesheet\">\r\n\r\n    <!-- BEGIN: Vendor CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/vendors.min.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/charts/apexcharts.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/extensions/dragula.min.css\">\r\n    <!-- END: Vendor CSS-->\r\n\r\n    <!-- BEGIN: Theme CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap-extended.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/colors.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/components.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/semi-dark-layout.css\">\r\n    <!-- END: Theme CSS-->\r\n\r\n    <!-- BEGIN: Page CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/core/menu/menu-types/vertical-menu.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/pages/dashboard-analytics.css\">\r\n    <!-- END: Page CSS-->\r\n\r\n    <!-- BEGIN: Custom CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../atlas-assets/css/style.css\">\r\n    <!-- END: Custom CSS-->\r\n\r\n</head>\r\n\r\n\r\n            \r\n\r\n  <!-- Pricing Plans -->\r\n\r\n  <body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\" >\r\n      <h2 class=\"text-center mb-3 mt-0 mt-md-4\">Planos Para Revendedores</h2>\r\n      <p class=\"text-center\"> Escolha o plano que melhor se encaixa no seu perfil e comece a vender agora mesmo!</p>\r\n\r\n      <form action='revenda.php?token=";
echo $_SESSION["tokenrevenda"];
echo "' method='POST'>\r\n      <div class=\"row mx-4 gy-3\">\r\n        <!-- Starter -->\r\n        <div class=\"col-xl mb-lg-0 lg-4\">\r\n          <div class=\"card border shadow-none\">\r\n            <div class=\"card-body\">\r\n              <h5 class=\"text-start text-uppercase\">Inicial</h5>\r\n\r\n              <div class=\"text-center position-relative mb-4 pb-1\">\r\n                <div class=\"mb-2 d-flex\">\r\n                  <h1 class=\"price-toggle text-primary price-yearly mb-0\">R\$ ";
echo $_SESSION["valorrevenda"] * 10;
echo "</h1>\r\n                  <sub class=\"h5 text-muted pricing-duration mt-auto mb-2\">/mês</sub>\r\n                </div>\r\n              </div>\r\n\r\n              <p>Plano inicial para quem está começando.</p>\r\n\r\n              <hr>\r\n\r\n              <ul class=\"list-unstyled pt-2 pb-1\">\r\n                <li class=\"mb-2\">\r\n                  <span class=\"badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2\">\r\n                    <i style='color: #fff;' class=\"bx bx-check bx-xs\"></i>\r\n                  </span>\r\n                  Limites de 10 usuários\r\n                </li>\r\n                <li class=\"mb-2\">\r\n                  <span class=\"badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2\">\r\n                    <i style='color: #fff;' class=\"bx bx-check bx-xs\"></i>\r\n                  </span>\r\n                    Painel de Controle\r\n                </li>\r\n              </ul>\r\n              <input type=\"hidden\" name=\"tokenrevenda\" value=\"";
echo $_SESSION["tokenrevenda"];
echo "\">\r\n              <button type=\"submit\" name=\"plano10\" class=\"btn btn-primary d-grid w-100\">Comprar</button>\r\n            </div>\r\n          </div>\r\n        </div>\r\n        ";
session_start();
if (isset($_POST["plano10"])) {
    $_SESSION["plano"] = 10;
    $_SESSION["tokenrevenda"] = $_POST["tokenrevenda"];
    echo "<script>location.href='revenda/formulariocompra.php';</script>";
}
echo "        \r\n        <!-- Exclusive -->\r\n        <div class=\"col-xl mb-lg-0 lg-4\">\r\n          <div class=\"card border border-2 border-primary\">\r\n            <div class=\"card-body\">\r\n              <div class=\"d-flex justify-content-between flex-wrap mb-3\">\r\n                <h5 class=\"text-start text-uppercase mb-0\">Intermediário</h5>\r\n                <span style='color: #fff;' class=\"badge bg-primary rounded-pill\">+ Vendido</span>\r\n              </div>\r\n\r\n              <div class=\"text-center position-relative mb-4 pb-1\">\r\n                <div class=\"mb-2 d-flex\">\r\n                  <h1 class=\"price-toggle text-primary price-yearly mb-0\">R\$";
echo $_SESSION["valorrevenda"] * 20;
echo "</h1>\r\n                  <sub class=\"h5 text-muted pricing-duration mt-auto mb-2\">/mês</sub>\r\n                </div>\r\n              </div>\r\n              <p>Plano intermediário para quem já tem uma base de clientes.</p>\r\n\r\n              <hr>\r\n\r\n              <ul class=\"list-unstyled pt-2 pb-1\">\r\n              <li class=\"mb-2\">\r\n                  <span class=\"badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2\">\r\n                    <i style='color: #fff;' class=\"bx bx-check bx-xs\"></i>\r\n                  </span>\r\n                  Limites de 20 usuários\r\n                </li>\r\n                <li class=\"mb-2\">\r\n                  <span class=\"badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2\">\r\n                    <i style='color: #fff;' class=\"bx bx-check bx-xs\"></i>\r\n                  </span>\r\n                    Painel de Controle\r\n                </li>\r\n              </ul>\r\n              <input type=\"hidden\" name=\"tokenrevenda\" value=\"";
echo $_SESSION["tokenrevenda"];
echo "\">\r\n              <button type=\"submit\" name=\"plano20\" class=\"btn btn-primary d-grid w-100\">Comprar</button>\r\n            </div>\r\n          </div>\r\n        </div>\r\n        ";
if (isset($_POST["plano20"])) {
    $_SESSION["tokenrevenda"] = $_POST["tokenrevenda"];
    $_SESSION["plano"] = 20;
    echo "<script>location.href='revenda/formulariocompra.php'</script>";
}
echo "\r\n        <!-- Enterprise -->\r\n        <div class=\"col-xl mb-lg-0 lg-4\">\r\n          <div class=\"card border shadow-none\">\r\n            <div class=\"card-body\">\r\n              <h5 class=\"text-start text-uppercase\">Avançado</h5>\r\n\r\n              <div class=\"text-center position-relative mb-4 pb-1\">\r\n                <div class=\"mb-2 d-flex\">\r\n                  <h1 class=\"price-toggle text-primary price-yearly mb-0\">R\$";
echo $_SESSION["valorrevenda"] * 30;
echo "</h1>\r\n                  <sub class=\"h5 text-muted pricing-duration mt-auto mb-2\">/mês</sub>\r\n                </div></div>\r\n              <p>Plano avançado para quem já possui muitos clientes.</p>\r\n\r\n              <hr>\r\n\r\n              <ul class=\"list-unstyled pt-2 pb-1\">\r\n              <li class=\"mb-2\">\r\n                  <span class=\"badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2\">\r\n                    <i style='color: #fff;' class=\"bx bx-check bx-xs\"></i>\r\n                  </span>\r\n                  Limites de 30 usuários\r\n                </li>\r\n\r\n                <li class=\"mb-2\">\r\n                  <span class=\"badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2\">\r\n                    <i style='color: #fff;' class=\"bx bx-check bx-xs\"></i>\r\n                  </span>\r\n                    Painel de Controle\r\n                </li>\r\n              </ul>\r\n              <input type=\"hidden\" name=\"tokenrevenda\" value=\"";
echo $_SESSION["tokenrevenda"];
echo "\">\r\n              <button type=\"submit\" name=\"plano30\" class=\"btn btn-primary d-grid w-100\">Comprar</button>\r\n            </div>\r\n          </div>\r\n        </div>\r\n        ";
if (isset($_POST["plano30"])) {
    $_SESSION["tokenrevenda"] = $_POST["tokenrevenda"];
    $_SESSION["plano"] = 30;
    echo "<script>location.href='revenda/formulariocompra.php'</script>";
}
echo "      </div>\r\n    </div>\r\n</form>\r\n  </div>\r\n  <!--/ Pricing Plans -->\r\n  </div>\r\n  </body>\r\n\r\n    <script src=\"../../../app-assets/vendors/js/vendors.min.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/configs/vertical-menu-dark.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app-menu.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/components.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/footer.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/forms/number-input.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/datatables.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/buttons.html5.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/buttons.print.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/pdfmake.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/vfs_fonts.js\"></script>\r\n \r\n</body>\r\n</html>";
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