<?php


error_reporting(0);
session_start();
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
mysqli_set_charset($conn, "utf8mb4");
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
include_once "headeradmin2.php";
$dominio = $_SERVER["HTTP_HOST"];
$sql = "SELECT * FROM accounts WHERE id = '" . $_SESSION["iduser"] . "'";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $accesstoken = $row["accesstoken"];
        $acesstokenpaghiper = $row["acesstokenpaghiper"];
    }
}
$sql = "SELECT * FROM configs WHERE id = '1'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$applink = $row["cortextcard"];
$validade = $_SESSION["validadefin"];
$sucess_servers = isset($_GET["sucess"]) ? explode(", ", $_GET["sucess"]) : [];
$failed_servers = isset($_GET["failed"]) ? explode(", ", $_GET["failed"]) : [];
$dominioserver = "apiwhats.atlaspainel.com.br";
$sqlwhats = "SELECT * FROM whatsapp WHERE ativo = '1'";
$resultwhats = mysqli_query($conn, $sqlwhats);
$rowwhats = mysqli_fetch_assoc($resultwhats);
$tokenwpp = $rowwhats["token"];
$sessaowpp = $rowwhats["sessao"];
$ativewpp = $rowwhats["ativo"];
if ($ativewpp == "1") {
    $mensagens = "SELECT * FROM mensagens WHERE ativo = 'ativada' AND funcao = 'criarteste'";
    $resultmensagens = mysqli_query($conn, $mensagens);
    $rowmensagens = mysqli_fetch_assoc($resultmensagens);
    $mensagem = $rowmensagens["mensagem"];
    if (!empty($mensagem)) {
        $mensagem = strip_tags($mensagem);
        $mensagem = str_replace("<br>", "\n", $mensagem);
        $mensagem = str_replace("<br><br>", "\n", $mensagem);
        $numerowpp = $_SESSION["whatsapp"];
        if (!isset($_SESSION["mensagem_enviada"])) {
            $dominio = $_SERVER["HTTP_HOST"];
            $mensagem = str_replace("{login}", $_SESSION["usuariofin"], $mensagem);
            $mensagem = str_replace("{usuario}", $_SESSION["usuariofin"], $mensagem);
            $mensagem = str_replace("{senha}", $_SESSION["senhafin"], $mensagem);
            $mensagem = str_replace("{validade}", $validade, $mensagem);
            $mensagem = str_replace("{limite}", $_SESSION["limitefin"], $mensagem);
            $mensagem = str_replace("{dominio}", $dominio, $mensagem);
            $mensagem = addslashes($mensagem);
            $mensagem = json_encode($mensagem);
            $mensagem = str_replace("\"", "", $mensagem);
            echo "<script>\r\n        var enviado = false;\r\n        var phoneNumber = '" . $numerowpp . "';\r\n        const message = '" . $mensagem . "';\r\n    \r\n        const data = {\r\n          phone: phoneNumber,\r\n          isGroup: false,\r\n          message: message\r\n        };\r\n        const urlsend = 'https://" . $dominioserver . "/api/" . $sessaowpp . "/send-message';\r\n        const headerssend = {\r\n          accept: '*/*',\r\n          Authorization: 'Bearer " . $tokenwpp . "',\r\n          'Content-Type': 'application/json'\r\n        };\r\n    \r\n        const enviar = () => {\r\n          if (!enviado) { // Verifica se a mensagem ainda não foi enviada\r\n            enviado = true; // Define a variável como true para evitar novo envio\r\n    \r\n            \$.ajax({\r\n              url: urlsend,\r\n              type: 'POST',\r\n              data: JSON.stringify(data),\r\n              headers: headerssend,\r\n              success: function(response) {\r\n                console.log(response);\r\n                if (response.status == 'success') {\r\n                  // Exiba uma mensagem de sucesso ou faça qualquer outra ação necessária\r\n                } else {\r\n                  // Trate o erro de envio da mensagem\r\n                }\r\n              },\r\n              error: function(error) {\r\n                console.error('Erro ao enviar mensagem:', error);\r\n              }\r\n            });\r\n          }\r\n        };\r\n        enviar();\r\n      </script>";
            $_SESSION["mensagem_enviada"] = true;
        }
    }
}
echo " <!--scrolling content Modal -->\r\n <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n<div class=\"col-md-6 col-12\">\r\n<script>\r\n    \$(document).ready(function(){\r\n\r\n        \$(\"#criado\").modal('show');\r\n    });\r\n</script>\r\n<head>\r\n  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css\" integrity=\"sha512-9O9Sd6Ia1+A0+KwUO1eUg0Fyb3J6UdTo68joKgY9A20+RzI2HfIQK8pk6FyUdxUGpIq3oUItrW8jYVGf9GYZRg==\" crossorigin=\"anonymous\" />\r\n</head>\r\n <div class=\"modal fade\" id=\"criado\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalScrollableTitle\" aria-hidden=\"true\">\r\n                                            <div class=\"modal-dialog modal-dialog-scrollable\" role=\"document\">\r\n                                                <div class=\"modal-content\">\r\n                                                    \r\n                                                <script>\r\n                      function copyDivToClipboard() {\r\n                        var range = document.createRange();\r\n                        range.selectNode(document.getElementById(\"divToCopy\"));\r\n                        window.getSelection().removeAllRanges(); // clear current selection\r\n                        window.getSelection().addRange(range); // to select text\r\n                        document.execCommand(\"copy\");\r\n                        window.getSelection().removeAllRanges();// to deselect\r\n                        //alert\r\n                        swal(\"Copiado!\", \"\", \"success\");\r\n\r\n                      }\r\n                    </script>\r\n                                                    <div class=\"bg-alert modal-header\">\r\n                                                        <h5 class=\"modal-title\" id=\"exampleModalScrollableTitle\"></h5>\r\n                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\r\n                                                            <i class=\"bx bx-x\"></i>\r\n                                                        </button>\r\n                                                    </div>\r\n                                                    <style>\r\n                                                        p {\r\n                                                            margin-bottom: 8px;\r\n                                                            }\r\n                                                    </style>\r\n                                                    <div class=\"modal-body\" id=\"divToCopy\">\r\n                                                    <div class=\"alert alert-alert\" role=\"alert\" style=\"text-align: center; font-size: 18px;\">\r\n                                                       <div class=\"divider divider-success\">\r\n                                                            \r\n                                                        <strong class=\"divider-text\" style=\"font-size: 20px;\">🎉 Teste Criado 🎉</strong>\r\n                                                        </div>\r\n                                                        <p>🔎 Usuario: ";
echo $_SESSION["usuariofin"];
echo "</p>\r\n                                                        <p>🔑 Senha: ";
echo $_SESSION["senhafin"];
echo "</p>\r\n                                                        <p>🎯 Validade: ";
echo $validade;
echo " Minutos</p>\r\n                                                        <p>🕟 Limite: ";
echo $_SESSION["limitefin"];
echo "</p>\r\n                                                        ";
echo "<p>" . $applink . "</p>";
if (!($accesstoken == "" && $acesstokenpaghiper == "")) {
    echo "\r\n                                                          <p>🌍Link de Renovação: https://" . $dominio . "/renovar.php</p>\r\n                                                          <p>Esse link 👆 servirá para você fazer as suas renovações</p>\r\n                                                          ";
}
echo "                                                        <div class=\"divider divider-success\">\r\n                                                            <p><strong class=\"divider-text\" style=\"font-size: 20px;\"></strong></p>\r\n                                                        </div>\r\n                                                        \r\n                                                        </div>\r\n                                                    </div>\r\n                                                    <p style=\"text-align: center;\">✔️ Criado: ";
echo implode(", ", $sucess_servers);
echo "</p>\r\n                                                    ";
if ($failed_servers[0] != "") {
    echo "\r\n                                                      <p style=\"text-align: center;\">❌ Falha: " . implode(", ", $failed_servers) . "</p>\r\n                                                      ";
}
echo "                                                    <div class=\"modal-footer\">\r\n                                                    <div class=\"btn-group dropup mr-1 mb-1\">\r\n                                                        <style>\r\n                                                            button {\r\n                                                                /* espaço entre os botoes */\r\n                                                                margin-right: 5px;\r\n                                                                }\r\n                                                        </style>\r\n                                        <button type=\"button\" class=\"btn btn-primary dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">\r\n                                            Copiar\r\n                                        </button>\r\n                                        <div class=\"dropdown-menu\">\r\n                                            <a class=\"dropdown-item\" onclick=\"copyDivToClipboard()\">Copiar</a>\r\n                                            <a class=\"dropdown-item\" onclick=\"shareOnWhatsApp()\">Compartilhar no Whatsapp</a>\r\n                                            <a class=\"dropdown-item\" onclick=\"copytotelegram()\">Compartilhar no Telegram</a>\r\n                                        </div>\r\n                                        <button type=\"button\" class=\"btn btn-light-secondary\" data-dismiss=\"modal\">\r\n                                            <i class=\"bx bx-x d-block d-sm-none\"></i>\r\n                                            <span class=\"d-none d-sm-block\">Lista de Usuarios</span>\r\n                                        </button>\r\n                                        \r\n                                    </div>\r\n                                                        <!-- botao de copiar whatsapp  https://cdn.discordapp.com/attachments/968040569769181194/1077873044296585216/whatsapp.png-->\r\n                                                        \r\n\r\n<script>\r\nfunction shareOnWhatsApp() {\r\n  var text = \"🎉 Conta Criada com Sucesso! 🎉\\n\" + \r\n             \"🔎 Usuario: ";
echo $_SESSION["usuariofin"];
echo "\\n\" +\r\n             \"🔑 Senha: ";
echo $_SESSION["senhafin"];
echo "\\n\" +\r\n             \"🎯 Validade: ";
echo $validade;
echo " Minutos\\n\" +\r\n             \"🕟 Limite: ";
echo $_SESSION["limitefin"];
echo "\\n\" +\r\n             ";
echo "\"" . $applink . "\\n\" +";
if ($accesstoken == "") {
    echo "\" \"";
} else {
    echo "\r\n              \"🌍Link de Renovação: https://" . $dominio . "/renovar.php\\n\" +\r\n                \"Esse link servirá para você fazer as suas renovações.\\n\\n\";\r\n                ";
}
echo "                                         \r\n             \r\n\r\n  var encodedText = encodeURIComponent(text);\r\n  var whatsappUrl = \"https://api.whatsapp.com/send?text=\" + encodedText;\r\n  \r\n  window.open(whatsappUrl);\r\n}\r\n</script>\r\n<script>\r\nfunction copytotelegram() {\r\n    /* monoespaçado */\r\n    var text = \"🎉 Conta Criada com Sucesso! 🎉\\n\" +\r\n        \"🔎 Usuario: ";
echo $_SESSION["usuariofin"];
echo "\\n\" +\r\n        \"🔑 Senha: ";
echo $_SESSION["senhafin"];
echo "\\n\" +\r\n        \"🎯 Validade: ";
echo $validade;
echo " Minutos\\n\" +\r\n        \"🕟 Limite: ";
echo $_SESSION["limitefin"];
echo "\\n\" +\r\n        ";
echo "\"" . $applink . "\\n\" +";
if ($accesstoken == "") {
    echo "\" \"";
} else {
    echo "\r\n              \"🌍Link de Renovação: https://" . $dominio . "/renovar.php\\n\" +\r\n                \"Esse link servirá para você fazer as suas renovações.\\n\\n\";\r\n                ";
}
echo "   \r\n\r\n    var encodedText = encodeURIComponent(text);\r\n    var telegramUrl = \"https://t.me/share/url?url=\" + encodedText;\r\n\r\n    window.open(telegramUrl);\r\n}\r\n</script>\r\n\r\n\r\n\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        <script>\r\n                            //mostra toast\r\n                            \$(document).ready(function() {\r\n                                \$(\"#toast-toggler\").click();\r\n                            });\r\n\r\n                            //se o usuario fechar o modal, ele volta para a lista de usuarios\r\n                            \$(document).ready(function() {\r\n                                \$(\"#criado\").on('hidden.bs.modal', function() {\r\n                                    window.location.href = \"listarusuarios.php\";\r\n                                });\r\n                            });\r\n\r\n                        </script>\r\n\r\n                       \r\n \r\n                       <script src=\"../../../app-assets/js/scripts/pages/bootstrap-toast.js\"></script>\r\n                       <script src=\"../app-assets/sweetalert.min.js\"></script>";

?>