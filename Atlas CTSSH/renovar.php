<?php


echo "\r\n";
error_reporting(0);
session_start();
include_once "atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    echo $conn->connect_error;
}
echo "\r\n\r\n\r\n<!DOCTYPE html>\r\n<html lang=\"pt-br\">\r\n  <head>\r\n    <!-- Required meta tags -->\r\n    <meta charset=\"utf-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">\r\n    ";
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
echo "\">\r\n    <link href=\"https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700\" rel=\"stylesheet\">\r\n\r\n\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/vendors.min.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap-extended.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/colors.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/components.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/semi-dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/core/menu/menu-types/vertical-menu.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/pages/authentication.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../atlas-assets/css/style.css\">\r\n\r\n</head>\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page blank-page\" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"1-column\" data-layout=\"dark-layout\">\r\n    <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"auth-login\" class=\"row flexbox-container\">\r\n                    <div class=\"col-xl-8 col-11\">\r\n                        \r\n                            \r\n                               \r\n                                    <div class=\"card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center\">\r\n                                        <div class=\"card-header pb-1\">\r\n                                            <div class=\"card-title\">\r\n                                                <h4 class=\"text-center mb-2\">";
echo $nomepainel;
echo " - Renovação</h4>\r\n                                            </div>\r\n                                        </div>\r\n                                        ";
if (isset($_POST["submit"])) {
    $login = mysqli_real_escape_string($conn, $_POST["login"]);
    $senha = mysqli_real_escape_string($conn, $_POST["senha"]);
    if (strpos($login, "'") != false || strpos($senha, "'") != false) {
        echo "<div class='alert alert-danger alert-dismissible mb-2' role='alert'>\r\n    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\r\n      <span aria-hidden='true'>&times;</span>\r\n      </button>\r\n      <center>\r\n      <strong>Erro!</strong> Caracteres inválidos detectados.\r\n      </div>\r\n      ";
    } else {
        $sql = "SELECT * FROM ssh_accounts WHERE login = ? AND senha = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $login, $senha);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (0 < mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION["id"] = $row["id"];
            $_SESSION["login"] = $row["login"];
            $_SESSION["senha"] = $row["senha"];
            $_SESSION["byid"] = $row["byid"];
            $_SESSION["limite"] = $row["limite"];
            $_SESSION["expira"] = $row["expira"];
            $_SESSION["categoria"] = $row["categoriaid"];
            echo "<div class='alert alert-success alert-dismissible mb-2' role='alert'>\r\n      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\r\n        <span aria-hidden='true'>&times;</span>\r\n        </button>\r\n        <center>\r\n        <strong>Sucesso!</strong> Crendenciais Corretas, Redirecionando...\r\n        </div>\r\n        <meta http-equiv='refresh' content='2; url=usuario/index.php'>\r\n        ";
        } else {
            echo "<div class='alert alert-danger alert-dismissible mb-2' id='alert' role='alert'>\r\n    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\r\n      <span aria-hidden='true'>&times;</span>\r\n    </button>\r\n    <center>\r\n    <strong>Erro!</strong> Login ou Senha Incorretos!\r\n  </div><script>setTimeout(function(){ \$('#alert').alert('close'); }, 3000);</script>";
        }
    }
}
echo "                                        <div class=\"card-content\">\r\n                                            <div class=\"card-body\">\r\n                                                <div class=\"divider\">\r\n                                                    <div class=\"divider-text text-uppercase text-muted\"><small>Renovação Usuario</small>\r\n                                                    \r\n                                                    </div>\r\n                                                    <p class=\"card-description\">Preencha os campos abaixo para renovar seu usuario.</p>\r\n                                                </div>\r\n                                                <form action=\"renovar.php\" method=\"POST\">\r\n                                                    <div class=\"form-group mb-50\">\r\n                                                        <label class=\"text-bold-600\">Login</label>\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"login\" placeholder=\"Seu Login\"></div>\r\n                                                    <div class=\"form-group\">\r\n                                                        <label class=\"text-bold-600\">Senha</label>\r\n                                                        <input type=\"password\" class=\"form-control\" name=\"senha\" placeholder=\"Sua Senha\">\r\n                                                    </div>\r\n                                                    <div class=\"form-group d-flex flex-md-row flex-column justify-content-between align-items-center\">\r\n                                                        <div class=\"text-left\">\r\n                                                            <div></div>\r\n                                                        </div>\r\n                                                    </div>\r\n                                                    <button type=\"submit\" class=\"btn btn-primary glow w-100 position-relative\" name=\"submit\">Entrar<i id=\"icon-arrow\" class=\"bx bx-right-arrow-alt\"></i></button>\r\n                                                </form>\r\n                                                <hr>\r\n\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                </div>\r\n                                \r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                </section>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <script src=\"../../../app-assets/vendors/js/vendors.min.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js\"></script>\r\n    <script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/configs/vertical-menu-dark.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app-menu.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/components.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/footer.js\"></script>\r\n</body>\r\n</html>";

?>