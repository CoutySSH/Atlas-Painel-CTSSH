<?php


error_reporting(0);
session_start();
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$login = $_SESSION["login"];
require_once "../vendor/pix/autoload.php";
echo "\r\n";
set_time_limit(0);
ignore_user_abort(true);
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$expiracaopix = $_SESSION["expiracaopix"];
include "header2.php";
$valor = $_SESSION["valoradd"];
echo " <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n<div class=\"col-md-6 col-12\">\r\n<script>\r\n    \$(document).ready(function(){\r\n\r\n        \$(\"#criado\").modal('show');\r\n    });\r\n    \r\n</script>\r\n<head>\r\n  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css\" integrity=\"sha512-9O9Sd6Ia1+A0+KwUO1eUg0Fyb3J6UdTo68joKgY9A20+RzI2HfIQK8pk6FyUdxUGpIq3oUItrW8jYVGf9GYZRg==\" crossorigin=\"anonymous\" />\r\n</head>\r\n <div class=\"modal fade\" id=\"criado\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalScrollableTitle\" aria-hidden=\"true\">\r\n     <div class=\"modal-dialog modal-dialog-scrollable\" role=\"document\">\r\n         <div class=\"modal-content\">\r\n             <!-- title modal -->\r\n             \r\n             <script>\r\n                 function copyDivToClipboard() {\r\n                     let textoCopiado = document.getElementById(\"qrcode\");\r\n                            textoCopiado.select();\r\n                            textoCopiado.setSelectionRange(0, 99999)\r\n                            document.execCommand(\"copy\");\r\n                            alert(\"Copiado com Sucesso!\");\r\n                     \r\n                    }\r\n                    </script>\r\n                    <script>\r\n    function atualizarTempoRestante() {\r\n        var agora = new Date();\r\n        var expira = new Date('";
echo $expiracaopix;
echo "');\r\n        var diferenca = expira - agora;\r\n        var minutos = Math.floor((diferenca / 1000) / 60);\r\n        var segundos = Math.floor((diferenca / 1000) % 60);\r\n\r\n        if (diferenca > 0) {\r\n            document.getElementById('tempo-restante').innerHTML = 'Tempo restante : ' + minutos + 'm ' + segundos + 's';\r\n        } else {\r\n            document.getElementById('tempo-restante').innerHTML = 'Tempo expirado';\r\n        }\r\n    }\r\n\r\n    setInterval(atualizarTempoRestante, 1000);\r\n</script>\r\n                                                    <div class=\"bg-alert modal-header\">\r\n                                                        <h5 style=\"text-align: center;\">N° Pedido: ";
echo $_SESSION["payment_id"];
echo "</h5>\r\n                                                        <h5 class=\"modal-title\" id=\"exampleModalScrollableTitle\"></h5>\r\n                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\r\n                                                            <i class=\"bx bx-x\"></i>\r\n                                                        </button>\r\n                                                    </div>\r\n                                                    <div class=\"modal-body\" id=\"divToCopy\">\r\n                                                    <div class=\"alert alert-alert\" role=\"alert\" style=\"text-align: center; font-size: 18px;\">\r\n                                                       <div class=\"divider divider-success\">\r\n                                                            \r\n                                                        <strong class=\"divider-text\" style=\"font-size: 20px;\">INFORMAÇÕES</strong>\r\n                                                        </div>\r\n                                                        <p>Valor a Pagar: ";
echo $valor;
echo " R\$</p>\r\n                                                        <p>Após Efetuar o Pagamento Aguarde o Pagamento ser Concluido</p>\r\n                                                        \r\n\r\n                                                        <img style=\"width: 160px;\" class=\"qr_code\" src=\"data:image/png;base64,";
echo $_SESSION["qr_code_base64"];
echo "\">\r\n                                                        <hr>\r\n                                                        <input type=\"text\" name=\"texto\" id=\"qrcode\" class=\"form-control\" value=\"";
echo $_SESSION["qr_code"];
echo "\">\r\n                                                        <br>\r\n                                                        <div id=\"tempo-restante\" style=\"text-align: center; font-size: 18px;\"></div>\r\n                                                    </div>\r\n                                                    <button type=\"button\" class=\"btn btn-primary\" onclick=\"copyDivToClipboard()\">Copiar</button>\r\n                                                    <button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location.href='pagamento.php'\">Voltar</button>    \r\n                                                    </div>\r\n                                                    <p style=\"text-align: center;\">";
echo implode(", ", $sucess_servers);
echo "</p>\r\n                                                    <div class=\"modal-footer\">\r\n                                                    <div class=\"btn-group dropup mr-1 mb-1\">\r\n                                    </div>\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        <script>\r\n                            //mostra toast\r\n                            \$(document).ready(function() {\r\n                                \$(\"#toast-toggler\").click();\r\n                            });\r\n\r\n                        </script>\r\n\r\n                       \r\n \r\n                       <script src=\"../../../app-assets/js/scripts/pages/bootstrap-toast.js\"></script>\r\n                       <script src=\"../app-assets/sweetalert.min.js\"></script>\r\n\r\n\r\n    <!-- End custom js for this page -->\r\n  </body>\r\n</html>\r\n<script>\r\n    /* ao clicar fora do modal  */\r\n    \$(document).on('click', function(e) {\r\n        if (\$(e.target).is('#criado')) {\r\n            window.location.href = \"pagamento.php\";    \r\n        }\r\n    }); \r\n</script>\r\n\r\n\r\n\r\n\r\n\r\n\r\n</body>\r\n</html>\r\n   ";

?>