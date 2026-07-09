<?php


echo "\r\n";
session_start();
include_once "atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    echo $conn->connect_error;
}
set_include_path(get_include_path() . PATH_SEPARATOR . "lib2");
include "Net/SSH2.php";
include "vendor/event/autoload.php";
$_GET["token"] = anti_sql($_GET["token"]);
echo "\r\n\r\n\r\n<!DOCTYPE html>\r\n<html lang=\"pt-br\">\r\n  <head>\r\n    <!-- Required meta tags -->\r\n    <meta charset=\"utf-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">\r\n    ";
if (isset($_GET["token"])) {
    $_SESSION["token"] = $_GET["token"];
}
$pesquisa_revenda = "SELECT * FROM accounts WHERE tokenvenda = '" . $_SESSION["token"] . "'";
$pesquisa_revenda = $conn->query($pesquisa_revenda);
if (0 < $pesquisa_revenda->num_rows) {
    $revenda = $pesquisa_revenda->fetch_assoc();
    $valorusuario = $revenda["valorusuario"];
    $access_token = $revenda["accesstoken"];
    $login = $revenda["login"];
    $categoriaadmin = $revenda["tempo"];
    $sql = "SELECT * FROM configs";
    $result = $conn->query($sql);
    if (0 < $result->num_rows) {
        while ($row = $result->fetch_assoc()) {
            $nomepainel = $row["nomepainel"];
            $logo = $row["logo"];
            $icon = $row["icon"];
        }
    }
    if ($login == "admin") {
        $categoria = $categoriaadmin;
    } else {
        $atribuicao_cat = "SELECT * FROM atribuidos WHERE userid = '" . $revenda["id"] . "'";
        $atribuicao_cat = $conn->query($atribuicao_cat);
        if (0 < $atribuicao_cat->num_rows) {
            $atribuicao_cat = $atribuicao_cat->fetch_assoc();
            $categoria = $atribuicao_cat["categoriaid"];
        }
    }
    $usuario = gerar_senha(6, true, false, false);
    echo " \r\n\r\n<!DOCTYPE html>\r\n<html class=\"loading\" lang=\"pt-br\" data-textdirection=\"ltr\">\r\n\r\n<head>\r\n    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, user-scalable=0\">\r\n    <meta name=\"author\" content=\"Thomas\">\r\n    <title>";
    echo $nomepainel;
    echo " - Teste</title>\r\n    <link rel=\"apple-touch-icon\" href=\"";
    echo $icon;
    echo "\">\r\n    <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"";
    echo $icon;
    echo "\">\r\n    <link href=\"https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700\" rel=\"stylesheet\">\r\n\r\n\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/vendors.min.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap-extended.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/colors.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/components.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/semi-dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/core/menu/menu-types/vertical-menu.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/pages/authentication.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../atlas-assets/css/style.css\">\r\n  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css\" integrity=\"sha512-9O9Sd6Ia1+A0+KwUO1eUg0Fyb3J6UdTo68joKgY9A20+RzI2HfIQK8pk6FyUdxUGpIq3oUItrW8jYVGf9GYZRg==\" crossorigin=\"anonymous\" />\r\n\r\n</head>\r\n<script src=\"app-assets/sweetalert.min.js\"></script>\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page blank-page\" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"1-column\" data-layout=\"dark-layout\">\r\n<div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"auth-login\" class=\"row flexbox-container\">\r\n                    <div class=\"col-xl-8 col-11\">\r\n                        \r\n                            \r\n                               \r\n                                    <div class=\"card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center\">\r\n                                        <div class=\"card-header pb-1\">\r\n                                            <div class=\"card-title\">\r\n                                                <h4 class=\"text-center mb-2\">";
    echo $nomepainel;
    echo " - Teste</h4>\r\n                                            </div>\r\n                                        </div>\r\n                                        <div class=\"card-content\">\r\n                                            <div class=\"card-body\">\r\n                                                <div class=\"divider\">\r\n                                                    <div class=\"divider-text text-uppercase text-muted\"><small>Teste Gratis</small>\r\n                                                    \r\n                                                    </div>\r\n                                                    <p class=\"card-description\">Gerar um teste gratuito</p>\r\n                                                </div>\r\n                                                <div>\r\n                                                   \r\n                                                <form action=\"criarteste.php";
    if (isset($_GET["token"])) {
        echo "?token=" . $_GET["token"];
    }
    echo "\" method=\"post\">\r\n                                                    <div class=\"form-group mb-50\">\r\n                                                        <label class=\"text-bold-600\">Usuário</label>\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"login\" placeholder=\"Seu Login\" value=\"";
    echo $usuario;
    echo "\" disabled></div>\r\n                                                    <div class=\"form-group\">\r\n                                                        <label class=\"text-bold-600\">Senha</label>\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"senha\" value=\"";
    echo $usuario;
    echo "\" placeholder=\"Sua Senha\" disabled>\r\n                                                    </div>\r\n                                                    <div class=\"form-group d-flex flex-md-row flex-column justify-content-between align-items-center\">\r\n                                                        <div class=\"text-left\">\r\n                                                            <div></div>\r\n                                                        </div>\r\n                                                    </div>\r\n                                                    \r\n                                                    <button type=\"submit\" class=\"btn btn-primary glow w-100 position-relative\" name=\"submit\">Gerar Teste<i id=\"icon-arrow\" class=\"bx bx-right-arrow-alt\"></i></button>\r\n                                                </form>\r\n                                                <hr>\r\n\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                </div>\r\n                                \r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                   \r\n                </section>\r\n                ";
    if (isset($_POST["submit"])) {
        date_default_timezone_set("America/Sao_Paulo");
        $remotea = $_SERVER["HTTP_CF_CONNECTING_IP"];
        if ($remotea == "") {
            $remotea = $_SERVER["REMOTE_ADDR"];
        }
        date_default_timezone_set("America/Sao_Paulo");
        $dataLimite = strtotime("-12 hours");
        $sql = "SELECT * FROM bot WHERE app = ? AND sender = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $remotea, $_SERVER["HTTP_USER_AGENT"]);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $dataRegistro = strtotime($row["data"]);
            if ($dataLimite < $dataRegistro) {
                echo "<script>sweetAlert(\"Oops...\", \"Você já criou um teste!\", \"error\");</script>";
                exit;
            }
        }
        $sql = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
        $result = $conn->query($sql);
        $usuario = $usuario;
        $senha = $usuario;
        $limite = 1;
        $validade = 120;
        $loop = React\EventLoop\Factory::create();
        while ($user_data = mysqli_fetch_assoc($result)) {
            $tentativas = 0;
            $conectado = false;
            while ($tentativas < 3 && !$conectado) {
                $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
                if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
                    $loop->addTimer(0, function () use($ssh) {
                        $ssh->exec("clear");
                        $ssh->exec("./atlasteste.sh " . $usuario . " " . $senha . " " . $validade . " " . $limite . " > /dev/null 2>&1 &");
                        $ssh->exec("./atlasteste.sh " . $usuario . " " . $senha . " " . $validade . " " . $limite . " ");
                    });
                    $criado = true;
                    $conectado = true;
                } else {
                    $tentativas++;
                }
            }
        }
        if ($criado) {
            $_SESSION["usuariofin"] = $usuario;
            $_SESSION["jacriousuario"] = "sim";
            $_SESSION["senhafin"] = $senha;
            $_SESSION["validadefin"] = $validade;
            $_SESSION["limitefin"] = $limite;
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
            if ($ip == "") {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
            $useragent = $_SERVER["HTTP_USER_AGENT"];
            $data = date("Y-m-d H:i:s");
            $insertipuser = "INSERT INTO bot (app, sender, data) VALUES ('" . $ip . "', '" . $useragent . "', '" . $data . "')";
            $resultipuser = mysqli_query($conn, $insertipuser);
            $validadedata = date("Y-m-d H:i:s", strtotime("+" . $validade . " minutes"));
            $criadoteste = "INSERT INTO ssh_accounts (login, senha, expira, limite, byid, categoriaid, status, bycredit, mainid, lastview) VALUES ('" . $usuario . "', '" . $senha . "', '" . $validadedata . "', '" . $limite . "', '" . $revenda["id"] . "', '1', 'Offline', '0', '0', 'TESTE WHATSAPP')";
            $result9 = mysqli_query($conn, $criadoteste);
            echo "<script>window.location.href = \"criado.php?token=" . $_SESSION["token"] . "\";</script>";
        } else {
            echo "<script>alert(\"Erro ao Criar Teste\");</script>";
        }
        $loop->run();
    }
    echo "            </div>\r\n        </div>\r\n    \r\n    <script src=\"../../../app-assets/vendors/js/vendors.min.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/pages/bootstrap-toast.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/configs/vertical-menu-dark.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app-menu.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/components.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/footer.js\"></script>\r\n    \r\n</body>\r\n</html>";
} else {
    echo "<script>alert(\"Token Inválido\");</script>";
    exit;
}
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
function gerar_senha($tamanho, $maiusculas, $numeros, $simbolos)
{
    $ma = "abcdefghijklmnopqrstuvwxyz";
    $nu = "0123456789";
    $si = "!@#\$%¨&*()_+=";
    $senha = "";
    $maiusculas = $maiusculas ? "S" : "N";
    $numeros = $numeros ? "S" : "N";
    $simbolos = $simbolos ? "S" : "N";
    $caracteres = $ma;
    $caracteres .= $maiusculas == "S" ? $ma : "";
    $caracteres .= $numeros == "S" ? $nu : "";
    $caracteres .= $simbolos == "S" ? $si : "";
    $len = strlen($caracteres);
    for ($n = 1; $n < $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $senha .= $caracteres[$rand - 1];
    }
    return $senha;
}

?>