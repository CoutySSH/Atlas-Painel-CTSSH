<?php


echo "\r\n";
error_reporting(0);
session_start();
ignore_user_abort(true);
set_time_limit(0);
include_once "../atlas/conexao.php";
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "Net/SSH2.php";
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
        $_SESSION["tokenaccess"] = $row["accesstoken"];
        $_SESSION["formadepag"] = $row["formadepag"];
        $_SESSION["email"] = $row["contato"];
        $_SESSION["nome"] = $row["nome"];
        $_SESSION["acesstokenpaghiper"] = $row["acesstokenpaghiper"];
        $_SESSION["tokenpaghiper"] = $row["tokenpaghiper"];
    }
}
date_default_timezone_set("America/Sao_Paulo");
$datahoje = date("Y-m-d H:i:s");
if ($_SESSION["expira"] < $datahoje) {
    $_SESSION["expira"] = $datahoje;
}
$_SESSION["vencimento"] = date("d/m/Y", strtotime($_SESSION["expira"]));
$data = date("Y-m-d H:i:s", strtotime("+31 days", strtotime($_SESSION["expira"])));
$login = $_SESSION["login"];
$limites = $_SESSION["limite"];
$novadata = $data;
$novadata = date("Y-m-d H:i:s", strtotime($novadata));
$data = date("Y-m-d");
$diferenca = strtotime($novadata) - strtotime($data);
$dias = floor($diferenca / 86400);
$diasrestante = $dias;
$sql = "SELECT * FROM configs";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $nomepainel = $row["nomepainel"];
    $logopainel = $row["logo"];
    $logopainelmini = $row["icon"];
}
if ($_SESSION["formadepag"] == 1) {
    $expiracaopix = $_SESSION["expiracaopix"];
    echo "<script>\r\n        function atualizarTempoRestante() {\r\n            var agora = new Date();\r\n            var expira = new Date('" . $expiracaopix . "');\r\n            var diferenca = expira - agora;\r\n            var minutos = Math.floor((diferenca / 1000) / 60);\r\n            var segundos = Math.floor((diferenca / 1000) % 60);\r\n    \r\n            if (diferenca > 0) {\r\n                document.getElementById('tempo-restante').innerHTML = 'Tempo restante : ' + minutos + 'm ' + segundos + 's';\r\n            } else {\r\n                document.getElementById('tempo-restante').innerHTML = 'Tempo expirado';\r\n            }\r\n        }\r\n    \r\n        setInterval(atualizarTempoRestante, 1000);\r\n    </script>";
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
$expiracaopix = $_SESSION["expiracaopix"];
echo "<!DOCTYPE html>\r\n<html class=\"loading\" lang=\"pt-br\" data-textdirection=\"ltr\">\r\n\r\n<head>\r\n    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, user-scalable=0\">\r\n    <meta name=\"author\" content=\"Thomas\">\r\n    <title>";
echo $nomepainel;
echo " - Renovação</title>\r\n    <link rel=\"apple-touch-icon\" href=\"";
echo $icon;
echo "\">\r\n    <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"";
echo $icon;
echo "\">\r\n    <link href=\"https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700\" rel=\"stylesheet\">\r\n\r\n\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/vendors.min.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap-extended.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/colors.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/components.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/semi-dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/core/menu/menu-types/vertical-menu.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/pages/authentication.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../atlas-assets/css/style.css\">\r\n    <script src=\"../app-assets/sweetalert.min.js\"></script>\r\n\r\n</head>\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page blank-page\" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"1-column\" data-layout=\"dark-layout\">\r\n    <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"auth-login\" class=\"row flexbox-container\">\r\n                    <div class=\"col-xl-8 col-11\">\r\n                        \r\n                            \r\n                               \r\n                                    <div class=\"card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center\">\r\n                                        <div class=\"card-header pb-1\">\r\n                                            <div class=\"card-title\">\r\n                                                <h4 class=\"text-center mb-2\">N° Pedido: ";
echo $_SESSION["payment_id"];
echo "</h4>\r\n                                            </div>\r\n                                        </div>\r\n                                        <div class=\"card-content\">\r\n                                            <div class=\"card-body\">\r\n                                            <div class=\"col-xl mb-lg-0 lg-4\">\r\n          <div class=\"card border border-2 border-primary\">\r\n            <div class=\"card-body\">\r\n              <div class=\"d-flex justify-content-between flex-wrap mb-3\">\r\n              </div>\r\n\r\n              <div class=\"text-center position-relative mb-4 pb-1\">\r\n                <div class=\"mb-2 d-flex\">\r\n                  </div>\r\n                  <h1 class=\"price-toggle text-primary price-yearly mb-0\" style=\"text-align: center;\">INFORMAÇÕES</h1>\r\n              </div>\r\n              <center>\r\n              <p>Valor a Pagar: ";
echo $_SESSION["valor"];
echo " R\$</p>\r\n              <img style=\"width: 160px;\" class=\"qr_code\" src=\"data:image/png;base64,";
echo $_SESSION["qr_code_base64"];
echo "\">\r\n              </center>\r\n              \r\n              <ul class=\"list-unstyled pt-2 pb-1\">\r\n              <input type=\"text\" name=\"texto\" id=\"texto\" class=\"form-control\" value=\"";
echo $_SESSION["qr_code"];
echo "\">\r\n              <hr>\r\n              <div id=\"tempo-restante\" style=\"text-align: center; font-size: 18px;\"></div>\r\n                <center>\r\n                \r\n                </center>        \r\n               \r\n              </ul>\r\n              \r\n              <button type=\"submit\" class=\"btn btn-primary d-grid w-100\" value=\"Copiar Código\" onclick=\"copiarTexto()\">Copiar Codigo</button>\r\n            </div>\r\n          </div>\r\n        </div>\r\n\r\n                                                <hr>\r\n\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                </div>\r\n                                \r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                </section>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <script src=\"../../../app-assets/vendors/js/vendors.min.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js\"></script>\r\n    <script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/configs/vertical-menu-dark.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app-menu.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/components.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/footer.js\"></script>\r\n    <script>\r\n                        function copiarTexto() {\r\n                            let textoCopiado = document.getElementById(\"texto\");\r\n                            textoCopiado.select();\r\n                            textoCopiado.setSelectionRange(0, 99999)\r\n                            document.execCommand(\"copy\");\r\n                            alert(\"Copiado com Sucesso!\");\r\n                        }\r\n\r\n                        //checar se o verify.php retornou Aprovado\r\n</script>\r\n<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js\" integrity=\"sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>\r\n\r\n<script type=\"text/javascript\">\r\n\r\n//Calling function\r\nrepeatAjax();\r\n\r\n\r\nfunction repeatAjax(){\r\njQuery.ajax({\r\n          type: \"POST\",\r\n          url: 'verifica.php',\r\n          dataType: 'text',\r\n          success: function(resp) {\r\n          \tif(resp == 'Aprovado')\r\n          \t{\r\n              \$(\".qr_code\").attr('src','https://www.pngplay.com/wp-content/uploads/2/Approved-PNG-Photos.png');\r\n          \t  window.location.replace(\"aprovado.php\");\r\n\r\n                    jQuery('.teste').html(resp);\r\n                    }\r\n\r\n          },\r\n          complete: function() {\r\n                setTimeout(repeatAjax,1000); //After completion of request, time to redo it after a second\r\n             }\r\n        });\r\n}\r\n</script>\r\n\r\n</body>\r\n</html>";

?>