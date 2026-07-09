<?php


error_reporting(0);
session_start();
if (file_exists("atlas/conexao.php")) {
    include "atlas/conexao.php";
    try {
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    } catch (mysqli_sql_exception $ex) {
        header("Location: install.php");
        exit;
    }
    $sql = "SELECT * FROM configs";
    $result = $conn->query($sql);
    if (0 < $result->num_rows) {
        while ($row = $result->fetch_assoc()) {
            $nomepainel = $row["nomepainel"];
            $logo = $row["logo"];
            $icon = $row["icon"];
            $csspersonali = $row["corfundologo"];
        }
    }
    echo "<!DOCTYPE html>\n<html class=\"loading\" lang=\"pt-br\" data-textdirection=\"ltr\">\n\n<head>\n    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, user-scalable=0\">\n    <meta name=\"author\" content=\"Thomas\">\n    <title>";
    echo $nomepainel;
    echo " - Login</title>\n    <link rel=\"apple-touch-icon\" href=\"";
    echo $icon;
    echo "\">\n    <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"";
    echo $icon;
    echo "\">\n    <link href=\"https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700\" rel=\"stylesheet\">\n\n\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"app-assets/vendors/css/vendors.min.css\">\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"app-assets/css/bootstrap.css\">\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"app-assets/css/bootstrap-extended.css\">\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"app-assets/css/colors.css\">\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"app-assets/css/components.css\">\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"app-assets/css/themes/dark-layout.css\">\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"app-assets/css/themes/semi-dark-layout.css\">\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"app-assets/css/core/menu/menu-types/vertical-menu.css\">\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"app-assets/css/pages/authentication.css\">\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../atlas-assets/css/style.css\">\n\n</head>\n\n\n<style>\n        ";
    echo $csspersonali;
    echo "    </style>\n<body class=\"vertical-layout vertical-menu-modern dark-layout 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page blank-page\" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"1-column\" data-layout=\"dark-layout\">\n    <div class=\"app-content content\">\n        <div class=\"content-overlay\"></div>\n        <div class=\"content-wrapper\">\n            <div class=\"content-header row\">\n            </div>\n            <div class=\"content-body\">\n                <section id=\"auth-login\" class=\"row flexbox-container\">\n                    <div class=\"col-xl-8 col-11\">\n                        <div class=\"card bg-authentication mb-0\" style=\"border-radius: 20px;\">\n                            <div class=\"row m-0\">\n                                <div class=\"col-md-6 col-12 px-0\">\n                                    <div class=\"card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center\">\n                                        <div class=\"card-header pb-1\">\n                                            <div class=\"card-title\">\n                                                <center>\n                                                <img style=\"width: 180px; align-content: center;\" class=\"animated2\" src=\"";
    echo $logo;
    echo "\" alt=\"logo\">\n                                                </center>\n                                            </div>\n                                        </div>\n                                        ";
    if (isset($_POST["submit"])) {
        $login = mysqli_real_escape_string($conn, $_POST["login"]);
        $senha = mysqli_real_escape_string($conn, $_POST["senha"]);
        if (strpos($login, "'") != false || strpos($senha, "'") != false) {
            echo "<div class='alert alert-danger alert-dismissible mb-2' role='alert'>\n    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n      <span aria-hidden='true'>&times;</span>\n      </button>\n      <center>\n      <strong>Erro!</strong> Caracteres inválidos detectados.\n      </div>\n      ";
        } else {
            $sql = "SELECT * FROM accounts WHERE login = ? AND senha = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $login, $senha);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (0 < mysqli_num_rows($result)) {
                $row = mysqli_fetch_assoc($result);
                if ($row["id"] == 1) {
                    $_SESSION["iduser"] = $row["id"];
                    $_SESSION["login"] = $row["login"];
                    $_SESSION["senha"] = $row["senha"];
                    echo "<div class='alert alert-success alert-dismissible mb-2' role='alert'>\n      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n        <span aria-hidden='true'>&times;</span>\n        </button>\n        <center>\n        <strong>Sucesso!</strong> Crendenciais Corretas, Redirecionando...\n        </div>\n        <script>window.location.href='admin/home.php';</script>\n        ";
                } else {
                    $_SESSION["iduser"] = $row["id"];
                    $_SESSION["login"] = $row["login"];
                    $_SESSION["senha"] = $row["senha"];
                    echo "<div class='alert alert-success alert-dismissible mb-2' role='alert'>\n      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n        <span aria-hidden='true'>&times;</span>\n        </button>\n        <center>\n        <strong>Sucesso!</strong> Crendenciais Corretas, Redirecionando...\n        </div>\n        <script>window.location.href='home.php';</script>\n        ";
                }
            } else {
                echo "<div class='alert alert-danger alert-dismissible mb-2' role='alert'>\n    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n      <span aria-hidden='true'>&times;</span>\n    </button>\n    <center>\n    <strong>Erro!</strong> Login ou Senha Incorretos!\n  </div>";
            }
        }
    }
    echo "                                        <div class=\"card-content\">\n                                            <div class=\"card-body\">\n                                                <div class=\"divider divider-primary\">\n                                                    <div class=\"divider-text text-uppercase text-muted\" style=\"font-size: 18px;\"><small>Insira Suas Credenciais</small>\n                                                    </div>\n                                                </div>\n                                                <form action=\"index.php\" method=\"POST\">\n                                                    <div class=\"form-group mb-50\">\n                                                    <center>\n                                                        <label class=\"text-bold-600\">Login</label>\n                                                        <input type=\"text\" class=\"form-control\" name=\"login\" placeholder=\"Seu Login\"></div>\n                                                    <div class=\"form-group\">\n                                                        <center>\n                                                        <label class=\"text-bold-600\">Senha</label>\n                                                        <input type=\"password\" class=\"form-control\" name=\"senha\" placeholder=\"Sua Senha\">\n                                                    </div>\n                                                    <div class=\"form-group d-flex flex-md-row flex-column justify-content-between align-items-center\">\n                                                        <div class=\"text-left\">\n                                                            <div></div>\n                                                        </div>\n                                                    </div>\n                                                    <button type=\"submit\" class=\"btn btn-primary glow w-100 position-relative\" style=\"border-radius: 40px;\" name=\"submit\">Entrar<i id=\"icon-arrow\" class=\"bx bx-right-arrow-alt\"></i></button>\n                                                </form>\n                                                <hr>\n\n                                            </div>\n                                        </div>\n                                    </div>\n                                </div>\n                                <style>\n.animated {\n    animation: fade-in 1s ease-out;\n}\n\n@keyframes fade-in {\n    0% {\n        opacity: 0;\n    }\n    100% {\n        opacity: 1;\n    }\n}\n/* inputs */\ninput[type=text],\ninput[type=password] {\n    border: 1px solid #ccc;\n    border-radius: 40px;\n    box-sizing: border-box;\n    width: 100%;\n    padding: 12px 20px;\n    margin: 8px 0;\n}   \n\n\n.animated2 {\n    animation: fade-in 1s ease-out;\n}\n\n@keyframes fade-in {\n    0% {\n        opacity: 0;\n    }\n    100% {\n        opacity: 1;\n    }\n}\n\n\n\n\n\n\n\n                                </style>\n                                <div class=\"col-md-6 d-md-block d-none text-center align-self-center p-3\">\n                                    <div class=\"card-content\">\n                                        <img class=\"img-fluid animated\" src=\"login.png\" alt=\"branding logo\">\n                                    </div>\n                                </div>\n                            </div>\n                        </div>\n                    </div>\n                </section>\n            </div>\n        </div>\n    </div>\n    <script src=\"app-assets/vendors/js/vendors.min.js\"></script>\n    <script src=\"app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js\"></script>\n    <script src=\"app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js\"></script>\n    <script src=\"app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js\"></script>\n    <script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>\n    <script src=\"app-assets/js/scripts/configs/vertical-menu-dark.js\"></script>\n    <script src=\"app-assets/js/core/app-menu.js\"></script>\n    <script src=\"app-assets/js/core/app.js\"></script>\n    <script src=\"app-assets/js/scripts/components.js\"></script>\n    <script src=\"app-assets/js/scripts/footer.js\"></script>\n    <script>\n        fetch('admin/notific.php', {\n  method: 'POST', \n})\n  .then(response => {\n  })\n  .catch(error => {\n  });\n\n\n    </script>\n\n</body>\n</html>";
} else {
    header("Location: install.php");
    exit;
}

?>