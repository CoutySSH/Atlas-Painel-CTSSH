<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
include "../atlas/conexao.php";
include "headeradmin2.php";
error_reporting(0);
session_start();
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$sql = "SELECT limite FROM atribuidos WHERE userid = '" . $_SESSION["iduser"] . "'";
$result = $conn->prepare($sql);
$result->execute();
$result->bind_result($limiteatual);
$result->fetch();
$result->close();
$slq2 = "SELECT sum(limite) AS limiteusado  FROM atribuidos where byid='" . $_SESSION["iduser"] . "' ";
$result = $conn->prepare($slq2);
$result->execute();
$result->bind_result($limiteusado);
$result->fetch();
$result->close();
$sql3 = "SELECT * FROM atribuidos WHERE byid = '" . $_SESSION["iduser"] . "'";
$sql3 = $conn->prepare($sql3);
$sql3->execute();
$sql3->store_result();
$num_rows = $sql3->num_rows;
$numerodereven = $num_rows;
$sql4 = "SELECT * FROM ssh_accounts WHERE byid = '" . $_SESSION["iduser"] . "'";
$sql4 = $conn->prepare($sql4);
$sql4->execute();
$sql4->store_result();
$num_rows = $sql4->num_rows;
$numusuarios = $num_rows;
$limiteusado = $limiteusado + $numusuarios;
$restante = $_SESSION["limite"] - $limiteusado;
$_SESSION["restante"] = $restante;
$_SESSION["limiteusado"] = $limiteusado;
echo "\r\n<div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui você pode criar revendedores.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\">\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Novo Revendedor</h4>\r\n                                </div>\r\n                                <div id=\"alerta\">\r\n                                </div>\r\n                                \r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <form class=\"form form-horizontal\" action=\"criarrevenda.php\" method=\"POST\">\r\n                                            <div class=\"form-body\">\r\n                                            <button type=\"button\" class=\"btn btn-primary mr-1 mb-1\" onclick=\"gerar()\">Gerar Aleatorio</button>\r\n                                                <div class=\"row\">\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Selecionar categoria</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <select class=\"form-control\" name=\"categoria\">\r\n                                                        ";
$sql = "SELECT * FROM categorias";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='" . $row["subid"] . "'>" . $row["nome"] . "</option>";
}
echo "                                                        </select>\r\n                                                    </div>\r\n\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Usuario</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"usuariorevenda\" placeholder=\"Usuario\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Senha</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"senharevenda\" placeholder=\"Senha\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                <label>Selecione o Modo</label>\r\n                                            </div>\r\n                                            <div class=\"col-md-8 form-group\">\r\n                                                <select class=\"form-control select2-size-sm\" name=\"credivalid\" id=\"credivalid\" onchange=\"mostrar()\">\r\n                                                    <option value=\"Validade\">Validade</option>\r\n                                                    <option value=\"Credito\">Credito</option>\r\n                                                </select>\r\n                                            </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Limite</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"number\" class=\"form-control\" value=\"1\" min=\"1\" name=\"limiterevenda\" />\r\n                                                    </div>\r\n\r\n                                                <script>\r\n                                                    function mostrar() {\r\n                                                        var tipo = document.getElementById(\"credivalid\").value;\r\n                                                        if (tipo == \"Validade\") {\r\n                                                            document.getElementById(\"validade\").style.display = \"block\";\r\n                                                        } else {\r\n                                                            document.getElementById(\"validade\").style.display = \"none\";\r\n                                                        }\r\n                                                    }\r\n                                                </script>\r\n                                                <div class=\"col-md-12 form-group\">\r\n                                                    <div id=\"validade\">\r\n                                                        <div class=\"row\">\r\n                                                            <div class=\"col-md-4\">\r\n                                                                <label>Validade em Dias</label>\r\n                                                            </div>\r\n                                                            <div class=\"col-md-8\">\r\n                                                            <input type=\"number\" class=\"form-control\" value=\"30\" min=\"1\" name=\"validaderevenda\" />\r\n                                                            </div>\r\n                                                        </div>\r\n                                                    </div>\r\n                                                </div>\r\n                                                <div class=\"col-md-4\">\r\n                                                        <label>Numero Whatsapp (NUMERO IGUAL AO WHATSAPP)</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"whatsapp\" placeholder=\"+5511999999999\">\r\n                                                    </div>\r\n                                                <div class=\"col-12 col-md-8 offset-md-4 form-group\">\r\n                                                    <fieldset>\r\n                                                        \r\n                                                    </fieldset>\r\n                                                </div>\r\n                                                <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                    <button type=\"submit\" class=\"btn btn-primary mr-1 mb-1\" name=\"submit\">Criar</button>\r\n                                                    <button type=\"reset\" class=\"btn btn-light-secondary mr-1 mb-1\">Cancelar</button>\r\n                                                </div>\r\n                                                </div>\r\n                                            </div>\r\n                                            \r\n                                        </form>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        \r\n                        <script>\r\n                            function gerar() {\r\n                                var usuario = document.getElementsByName(\"usuariorevenda\")[0];\r\n                                var senha = document.getElementsByName(\"senharevenda\")[0];\r\n                                var limite = document.getElementsByName(\"limitefin\")[0];\r\n                                var validade = document.getElementsByName(\"validadefin\")[0];\r\n                                var caracteres = \"abcdefghijklmnopqrstuvwxyz\";\r\n                                var caracteres_senha = \"abcdefghijklmnopqrstuvwxyz\";\r\n                                var usuario_gerado = caracteres.charAt(Math.floor(Math.random() * 26));\r\n                                var senha_gerada = caracteres_senha.charAt(Math.floor(Math.random() * 26));\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    usuario_gerado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));\r\n                                }\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    senha_gerada += caracteres_senha.charAt(Math.floor(Math.random() * caracteres_senha.length));\r\n                                }\r\n                                usuario.value = usuario_gerado;\r\n                                senha.value = senha_gerada;\r\n                                var alerta = document.getElementById(\"alerta\");\r\n                                alerta.innerHTML = \"<div class='alert alert-success' role='alert'>Usuario e Senha Aleatorio Gerado!</div>\";\r\n                                setTimeout(function() {\r\n                                    \$('.alert').fadeOut();\r\n                                }, 1000);\r\n                                \r\n\r\n                            }\r\n                        </script> <script src=\"../app-assets/js/scripts/forms/number-input.js\"></script>\r\n                         <!--scrolling content Modal -->\r\n <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n<div class=\"col-md-6 col-12\">\r\n\r\n<head>\r\n  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css\" integrity=\"sha512-9O9Sd6Ia1+A0+KwUO1eUg0Fyb3J6UdTo68joKgY9A20+RzI2HfIQK8pk6FyUdxUGpIq3oUItrW8jYVGf9GYZRg==\" crossorigin=\"anonymous\" />\r\n</head>\r\n <div class=\"modal fade\" id=\"criado\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalScrollableTitle\" aria-hidden=\"true\">\r\n                                            <div class=\"modal-dialog modal-dialog-scrollable\" role=\"document\">\r\n                                                <div class=\"modal-content\">\r\n                                                    \r\n                                                <script>\r\n                      function copyDivToClipboard() {\r\n                        var range = document.createRange();\r\n                        range.selectNode(document.getElementById(\"divToCopy\"));\r\n                        window.getSelection().removeAllRanges(); // clear current selection\r\n                        window.getSelection().addRange(range); // to select text\r\n                        document.execCommand(\"copy\");\r\n                        window.getSelection().removeAllRanges();// to deselect\r\n                        //alert\r\n                        swal(\"Copiado!\", \"\", \"success\");\r\n\r\n                      }\r\n                    </script>\r\n                                                    <div class=\"bg-alert modal-header\">\r\n                                                        <h5 class=\"modal-title\" id=\"exampleModalScrollableTitle\"></h5>\r\n                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\r\n                                                            <i class=\"bx bx-x\"></i>\r\n                                                        </button>\r\n                                                    </div>\r\n                                                    <style>\r\n                                                        p {\r\n                                                            margin-bottom: 8px;\r\n                                                            }\r\n                                                    </style>\r\n                                                    <div class=\"modal-body\" id=\"divToCopy\">\r\n                                                    <div class=\"alert alert-alert\" role=\"alert\" style=\"text-align: center; font-size: 18px;\">\r\n                                                       <div class=\"divider divider-success\">\r\n                                                            \r\n                                                        <strong class=\"divider-text\" style=\"font-size: 20px;\">🎉 Revendedor Criado 🎉</strong>\r\n                                                        </div>\r\n                                                        <p>🔎 Usuario: ";
echo $_POST["usuariorevenda"];
echo "</p>\r\n                                                        <p>🔑 Senha: ";
echo $_POST["senharevenda"];
echo "</p>\r\n                                                        ";
if ($_POST["credivalid"] == "Validade") {
    echo "<p>🎯 Validade: " . $_POST["validaderevenda"] . " Dias</p>";
}
echo "                                                        <p>🕟 Limite: ";
echo $_POST["limiterevenda"];
echo " </p>\r\n                                                        <p>💥 Obrigado por usar nossos serviços!</p>\r\n                                                        ";
$dominio = $_SERVER["HTTP_HOST"];
echo "<p>🔗 Link do Painel: <a href='https://" . $dominio . "/'>https://" . $dominio . "/</a></p>";
echo "                                                        <div class=\"divider divider-success\">\r\n                                                            <p><strong class=\"divider-text\" style=\"font-size: 20px;\"></strong></p>\r\n                                                            </div>\r\n                                                            \r\n                                                        </div>\r\n                                                    </div>\r\n                                                    <div class=\"modal-footer\">\r\n                                                    <div class=\"btn-group dropup mr-1 mb-1\">\r\n                                                        <style>\r\n                                                            button {\r\n                                                                /* espaço entre os botoes */\r\n                                                                margin-right: 5px;\r\n                                                                }\r\n                                                        </style>\r\n                                        <button type=\"button\" class=\"btn btn-primary dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">\r\n                                            Copiar\r\n                                        </button>\r\n                                        <div class=\"dropdown-menu\">\r\n                                            <a class=\"dropdown-item\" onclick=\"copyDivToClipboard()\">Copiar</a>\r\n                                            <a class=\"dropdown-item\" onclick=\"shareOnWhatsApp()\">Compartilhar no Whatsapp</a>\r\n                                            <a class=\"dropdown-item\" onclick=\"copytotelegram()\">Compartilhar no Telegram</a>\r\n                                        </div>\r\n                                        <a href=\"listarrevendedores.php\" class=\"btn btn-light-secondary\">\r\n  <i class=\"bx bx-x d-block d-sm-none\"></i>\r\n  <span class=\"d-none d-sm-block\">Lista de Revendedores</span>\r\n</a>\r\n\r\n                                        \r\n                                    </div>\r\n                                                        <!-- botao de copiar whatsapp  https://cdn.discordapp.com/attachments/968040569769181194/1077873044296585216/whatsapp.png-->\r\n                                                        \r\n\r\n<script>\r\nfunction shareOnWhatsApp() {\r\n  var text = \"🎉 Revendedor Criado! 🎉\\n\" + \r\n             \"🔎 Usuario: ";
echo $_SESSION["usuariofin"];
echo "\\n\" +\r\n             \"🔑 Senha: ";
echo $_SESSION["senhafin"];
echo "\\n\" +\r\n             \"🎯 Validade: ";
echo $_SESSION["validadefin"];
echo "\\n\" +\r\n             \"🕟 Limite: ";
echo $_SESSION["limitefin"];
echo "\\n\" +\r\n             \"💥 Obrigado por usar nossos serviços!\\n\\n\" +\r\n              '';\r\n                                                   \r\n             \r\n\r\n  var encodedText = encodeURIComponent(text);\r\n  var whatsappUrl = \"https://api.whatsapp.com/send?text=\" + encodedText;\r\n  \r\n  window.open(whatsappUrl);\r\n}\r\n</script>\r\n<script>\r\nfunction copytotelegram() {\r\n    /* monoespaçado */\r\nvar text = \"🎉 Revendedor Criado! 🎉\\n\" +\r\n        \"🔎 Usuario: ";
echo $_SESSION["usuariofin"];
echo "\\n\" +\r\n        \"🔑 Senha: ";
echo $_SESSION["senhafin"];
echo "\\n\" +\r\n        \"🎯 Validade: ";
echo $_SESSION["validadefin"];
echo "\\n\" +\r\n        \"🕟 Limite: ";
echo $_SESSION["limitefin"];
echo "\\n\" +\r\n        \"💥 Obrigado por usar nossos serviços!\\n\\n\" +\r\n        '\" \"';\r\n\r\n    var encodedText = encodeURIComponent(text);\r\n    var telegramUrl = \"https://t.me/share/url?url=\" + encodedText;\r\n\r\n    window.open(telegramUrl);\r\n}\r\n</script>\r\n\r\n\r\n\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        <script>\r\n\r\n\r\n                        </script>\r\n\r\n                       \r\n \r\n                       <script src=\"../../../app-assets/js/scripts/pages/bootstrap-toast.js\"></script>\r\n                       <script src=\"../../../app-assets/js/scripts/extensions/sweet-alerts.js\"></script>\r\n                       <script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
if (isset($_POST["submit"])) {
    $_SESSION["usuariofin"] = $_POST["usuariorevenda"];
    $_SESSION["senhafin"] = $_POST["senharevenda"];
    $_SESSION["limitefin"] = $_POST["limiterevenda"];
    $_SESSION["validadefin"] = $_POST["validaderevenda"];
    $categoria = $_POST["categoria"];
    $credivalid = $_POST["credivalid"];
    $sql7 = "SELECT * FROM accounts ";
    $result7 = mysqli_query($conn, $sql7);
    if (empty($_POST["usuariorevenda"]) || empty($_POST["senharevenda"]) || empty($_POST["limiterevenda"])) {
        echo "<script>var alerta = document.getElementById('alerta'); alerta.innerHTML = '<div class=\\'alert alert-danger\\' role=\\'alert\\'>Preencha todos os campos!</div>';setTimeout(function() { \$('.alert').fadeOut(); }, 2000);</script>";
        exit;
    }
    while ($row = $result7->fetch_assoc()) {
        if ($row["login"] == $_POST["usuariorevenda"]) {
            echo "<script>var alerta = document.getElementById('alerta'); alerta.innerHTML = '<div class=\\'alert alert-danger\\' role=\\'alert\\'>Revendedor ja existe!</div>';setTimeout(function() { \$('.alert').fadeOut(); }, 2000);</script>";
            exit;
        }
    }
    $whatsapp = $_POST["whatsapp"];
    $slq5 = "INSERT INTO accounts (login, senha, byid, whatsapp) VALUES ('" . $_POST["usuariorevenda"] . "', '" . $_POST["senharevenda"] . "', '" . $_SESSION["iduser"] . "', '" . $whatsapp . "')";
    $result5 = mysqli_query($conn, $slq5);
    $sql6 = "SELECT id FROM accounts WHERE login = '" . $_POST["usuariorevenda"] . "'";
    $result6 = mysqli_query($conn, $sql6);
    while ($row = $result6->fetch_assoc()) {
        $idrevenda = $row["id"];
    }
    $usuarioreven = $_POST["usuariorevenda"];
    $validade = $_POST["validaderevenda"];
    $_POST["validaderevenda"] = $_POST["validaderevenda"] * 86400;
    $_POST["validaderevenda"] = $_POST["validaderevenda"] + time();
    $_POST["validaderevenda"] = date("Y-m-d H:i:s", $_POST["validaderevenda"]);
    if ($credivalid == "Credito") {
        $valllid = "Nunca";
        $sql7 = "INSERT INTO atribuidos (userid, byid, limite, categoriaid, tipo) VALUES ('" . $idrevenda . "', '" . $_SESSION["iduser"] . "', '" . $_POST["limiterevenda"] . "', '" . $categoria . "', '" . $credivalid . "')";
        $result7 = mysqli_query($conn, $sql7);
    } else {
        $valllid = $validade;
        $sql7 = "INSERT INTO atribuidos (userid, byid, limite, expira, categoriaid, tipo) VALUES ('" . $idrevenda . "', '" . $_SESSION["iduser"] . "', '" . $_POST["limiterevenda"] . "', '" . $_POST["validaderevenda"] . "', '" . $categoria . "', '" . $credivalid . "')";
        $result7 = mysqli_query($conn, $sql7);
    }
    $dominioserver = "apiwhats.atlaspainel.com.br";
    $sqlwhats = "SELECT * FROM whatsapp WHERE ativo = '1'";
    $resultwhats = mysqli_query($conn, $sqlwhats);
    $rowwhats = mysqli_fetch_assoc($resultwhats);
    $tokenwpp = $rowwhats["token"];
    $sessaowpp = $rowwhats["sessao"];
    $ativewpp = $rowwhats["ativo"];
    if ($ativewpp == "1") {
        $mensagens = "SELECT * FROM mensagens WHERE ativo = 'ativada' AND funcao = 'criarrevenda'";
        $resultmensagens = mysqli_query($conn, $mensagens);
        $rowmensagens = mysqli_fetch_assoc($resultmensagens);
        $mensagem = $rowmensagens["mensagem"];
        if (!empty($mensagem)) {
            $mensagem = strip_tags($mensagem);
            $mensagem = str_replace("<br>", "\n", $mensagem);
            $mensagem = str_replace("<br><br>", "\n", $mensagem);
            $numerowpp = $_POST["whatsapp"];
            $numerowpp = str_replace(" ", "", $numerowpp);
            $numerowpp = str_replace("-", "", $numerowpp);
            $dominio = $_SERVER["HTTP_HOST"];
            $mensagem = str_replace("{login}", $usuarioreven, $mensagem);
            $mensagem = str_replace("{usuario}", $usuarioreven, $mensagem);
            $mensagem = str_replace("{senha}", $_POST["senharevenda"], $mensagem);
            $mensagem = str_replace("{validade}", $valllid, $mensagem);
            $mensagem = str_replace("{limite}", $_POST["limiterevenda"], $mensagem);
            $mensagem = str_replace("{dominio}", $dominio, $mensagem);
            $mensagem = addslashes($mensagem);
            $mensagem = json_encode($mensagem);
            $mensagem = str_replace("\"", "", $mensagem);
            echo "<script>\r\n                                            var enviado = false;\r\n                                            var phoneNumber = '" . $numerowpp . "';\r\n                                            const message = '" . $mensagem . "';\r\n                                        \r\n                                            const data = {\r\n                                            phone: phoneNumber,\r\n                                            isGroup: false,\r\n                                            message: message\r\n                                            };\r\n                                            const urlsend = 'https://" . $dominioserver . "/api/" . $sessaowpp . "/send-message';\r\n                                            const headerssend = {\r\n                                            accept: '*/*',\r\n                                            Authorization: 'Bearer " . $tokenwpp . "',\r\n                                            'Content-Type': 'application/json'\r\n                                            };\r\n                                        \r\n                                            const enviar = () => {\r\n                                            if (!enviado) { // Verifica se a mensagem ainda não foi enviada\r\n                                                enviado = true; // Define a variável como true para evitar novo envio\r\n                                        \r\n                                                \$.ajax({\r\n                                                url: urlsend,\r\n                                                type: 'POST',\r\n                                                data: JSON.stringify(data),\r\n                                                headers: headerssend,\r\n                                                success: function(response) {\r\n                                                    console.log(response);\r\n                                                    if (response.status == 'success') {\r\n                                                    // Exiba uma mensagem de sucesso ou faça qualquer outra ação necessária\r\n                                                    } else {\r\n                                                    // Trate o erro de envio da mensagem\r\n                                                    }\r\n                                                },\r\n                                                error: function(error) {\r\n                                                    console.error('Erro ao enviar mensagem:', error);\r\n                                                }\r\n                                                });\r\n                                            }\r\n                                            };\r\n                                            enviar();\r\n                                        </script>";
        }
    }
    date_default_timezone_set("America/Sao_Paulo");
    $datahoje = date("d-m-Y H:i:s");
    $sql10 = "INSERT INTO logs (revenda, validade, texto, userid) VALUES ('" . $_SESSION["login"] . "', '" . $datahoje . "', 'Criou o Revendedor " . $usuarioreven . " com " . $validade . " dias', '" . $_SESSION["iduser"] . "')";
    $result10 = mysqli_query($conn, $sql10);
    echo "<script>\$('#criado').modal('show');</script>";
}

?>