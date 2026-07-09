<?php


echo "\r\n";
error_reporting(0);
session_start();
include_once "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    echo $conn->connect_error;
}
$sql = "SELECT * FROM accounts WHERE id = '" . $_SESSION["byid"] . "'";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $_SESSION["tokenaccess"] = $row["accesstoken"];
        $_SESSION["valorusuario"] = $row["valorusuario"];
        $_SESSION["acesstokenpaghiper"] = $row["acesstokenpaghiper"];
    }
}
if (isset($_POST["cupom"])) {
    isset($_POST["cupom"]);
    isset($_SESSION["cupom"]);
}
$sql = "SELECT * FROM configs";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $nomepainel = $row["nomepainel"];
    $logopainel = $row["logo"];
    $logopainelmini = $row["icon"];
}
if ($_SESSION["tokenaccess"] == "" && $_SESSION["acesstokenpaghiper"] == "") {
    echo "<script>alert('Revendedor não Cadrastado!');</script><script>window.location.href = '../renovar.php';</script>";
    exit;
}
$_SESSION["valor"] = $_SESSION["valorusuario"] * $_SESSION["limite"];
$_SESSION["vencimento"] = date("d/m/Y", strtotime($_SESSION["expira"]));
$login = $_SESSION["login"];
$senha = $_SESSION["senha"];
$sql = "SELECT * FROM ssh_accounts WHERE login = '" . $login . "' AND senha = '" . $senha . "'";
$result = mysqli_query($conn, $sql);
if (0 < mysqli_num_rows($result)) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION["id"] = $row["id"];
    $_SESSION["login"] = $row["login"];
    $_SESSION["senha"] = $row["senha"];
    $_SESSION["byid"] = $row["byid"];
    $_SESSION["limite"] = $row["limite"];
    $_SESSION["expira"] = $row["expira"];
    $_SESSION["categoria"] = $row["categoriaid"];
    $valormensal = $row["valormensal"];
}
$sql = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["byid"] . "'";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $_SESSION["limitedono"] = $row["limite"];
        $_SESSION["tipo"] = $row["tipo"];
    }
}
if ($_SESSION["byid"] == 1) {
    $_SESSION["limitedono"] = 0;
}
if ($_SESSION["limitedono"] < $_SESSION["limite"]) {
    echo "<script>alert('Revendedor não Tem Limite!');</script><script>window.location.href = '../renovar.php';</script>";
    exit;
}
$_SESSION["valor"] = $_SESSION["valorusuario"] * $_SESSION["limite"];
if ($valormensal != "" && $valormensal != 0) {
    $_SESSION["valor"] = $valormensal;
    $_SESSION["valormensal"] = $valormensal;
}
echo "\r\n\r\n\r\n\r\n<!DOCTYPE html>\r\n<html lang=\"pt-br\">\r\n  <head>\r\n    <!-- Required meta tags -->\r\n    <meta charset=\"utf-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">\r\n    ";
$sql = "SELECT * FROM configs";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $nomepainel = $row["nomepainel"];
        $logo = $row["logo"];
        $icon = $row["icon"];
    }
}
echo "<!DOCTYPE html>\r\n<html class=\"loading\" lang=\"pt-br\" data-textdirection=\"ltr\">\r\n\r\n<head>\r\n    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, user-scalable=0\">\r\n    <meta name=\"author\" content=\"Thomas\">\r\n    <title>";
echo $nomepainel;
echo " - Renovação</title>\r\n    <link rel=\"apple-touch-icon\" href=\"";
echo $icon;
echo "\">\r\n    <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"";
echo $icon;
echo "\">\r\n    <link href=\"https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700\" rel=\"stylesheet\">\r\n\r\n\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/vendors.min.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap-extended.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/colors.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/components.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/semi-dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/core/menu/menu-types/vertical-menu.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/pages/authentication.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../atlas-assets/css/style.css\">\r\n    <script src=\"../app-assets/sweetalert.min.js\"></script>\r\n\r\n</head>\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page blank-page\" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"1-column\" data-layout=\"dark-layout\">\r\n    <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"auth-login\" class=\"row flexbox-container\">\r\n                    <div class=\"col-xl-8 col-11\">\r\n                        \r\n                            \r\n                               \r\n                                    <div class=\"card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center\">\r\n                                        <div class=\"card-header pb-1\">\r\n                                            <div class=\"card-title\">\r\n                                                <h4 class=\"text-center mb-2\">Seja Bem Vindo(a) ";
echo $_SESSION["login"];
echo "</h4>\r\n                                            </div>\r\n                                        </div>\r\n                                        <div class=\"card-content\">\r\n                                            <div class=\"card-body\">\r\n                                            <div class=\"col-xl mb-lg-0 lg-4\">\r\n          <div class=\"card border border-2 border-primary\">\r\n            <div class=\"card-body\">\r\n              <div class=\"d-flex justify-content-between flex-wrap mb-3\">\r\n              </div>\r\n\r\n              <div class=\"text-center position-relative mb-4 pb-1\">\r\n                <div class=\"mb-2 d-flex\">\r\n                  </div>\r\n                  <h1 class=\"price-toggle text-primary price-yearly mb-0\" style=\"text-align: center;\">";
echo $_SESSION["valor"];
echo " R\$ Mensal </h1>\r\n              </div>\r\n              <center>\r\n              <p>Renove seu plano para continuar usando nossos serviços.</p>\r\n              </center>\r\n              <hr>\r\n\r\n              <ul class=\"list-unstyled pt-2 pb-1\">\r\n                <li class=\"mb-2\">\r\n                  <span class=\"badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2\">\r\n                    <i class=\"bx bx-check bx-xs\"></i>\r\n                  </span>\r\n                  O Seu Limite é ";
echo $_SESSION["limite"];
echo "                </li>\r\n                <li class=\"mb-2\">\r\n                  <span class=\"badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2\">\r\n                    <i class=\"bx bx-check bx-xs\"></i>\r\n                  </span>\r\n                  O Seu Vencimento é ";
echo $_SESSION["vencimento"];
echo "                </li>\r\n                <center>\r\n                <p>Tem um Cupom?</p>\r\n                </center>        \r\n                <form action=\"index.php\" method=\"POST\">\r\n                        <input type=\"text\" name=\"cupom\" placeholder=\"Codigo do Cupom\" class=\"form-control\">\r\n              </ul>\r\n              \r\n              <input type=\"submit\" class=\"btn btn-primary d-grid w-100\" value=\"Renovar\"></input>\r\n            </div>\r\n            </form>\r\n          </div>\r\n        </div>\r\n        ";
if (isset($_POST["cupom"])) {
    $cupom = $_POST["cupom"];
    $_SESSION["cupom"] = $cupom;
    $sql = "SELECT * FROM cupons WHERE cupom = '" . $cupom . "'";
    $result = mysqli_query($conn, $sql);
    if (0 < mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION["cupom"] = $row["cupom"];
        $_SESSION["desconto"] = $row["desconto"];
        echo "<script>swal('Cupom Aplicado Com Sucesso!', 'Desconto de " . $_SESSION["desconto"] . "%', 'success');</script>";
        echo "<script>setTimeout(\"location.href = 'processando.php';\",1500);</script>";
        exit;
    }
    echo "<script>window.location.href = 'processando.php';</script>";
}
echo "\r\n                                                <hr>\r\n\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                </div>\r\n                                \r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                </section>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <script src=\"../../../app-assets/vendors/js/vendors.min.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js\"></script>\r\n    <script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/configs/vertical-menu-dark.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app-menu.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/components.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/footer.js\"></script>\r\n</body>\r\n</html>";

?>