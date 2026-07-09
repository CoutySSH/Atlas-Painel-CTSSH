<?php


include "conexao.php";
include "header2.php";
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
$slq4 = "SELECT sum(limite) AS limiteusado  FROM ssh_accounts where byid='" . $_SESSION["iduser"] . "' ";
$result = $conn->prepare($slq4);
$result->execute();
$result->bind_result($limiterevendausado);
$result->fetch();
$result->close();
$limiteusado = $limiterevendausado + $limiteusado;
$restante = $_SESSION["limite"] - $limiteusado;
$_SESSION["restante"] = $restante;
$_SESSION["limiteusado"] = $limiteusado;
$sql5 = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["iduser"] . "'";
$sql5 = $conn->query($sql5);
$row = $sql5->fetch_assoc();
$validade = $row["expira"];
$categoria = $row["categoriaid"];
$_SESSION["limite"] = $row["limite"];
$_SESSION["tipodeconta"] = $row["tipo"];
$tipo = $_SESSION["tipodeconta"];
if ($tipo == "Credito") {
    $tipo = "Restam " . $_SESSION["limite"] . " Creditos";
} else {
    $tipo = "Seu limite usado é de " . $limiteusado . " Logins de " . $_SESSION["limite"] . "";
}
date_default_timezone_set("America/Sao_Paulo");
$hoje = date("Y-m-d H:i:s");
if ($_SESSION["tipodeconta"] != "Credito") {
    if ($validade < $hoje) {
        echo "<script>alert('Sua conta está vencida')</script><script>window.location.href = '../home.php'</script>";
        unset($_POST["criaruser"]);
        unset($_POST["usuariofin"]);
        unset($_POST["senhafin"]);
        unset($_POST["validadefin"]);
    }
}
echo "\r\n\r\n<div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui você pode criar revendedores.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\">\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Novo Revendedor</h4>\r\n                                </div>\r\n                                <div id=\"alerta\">\r\n                                </div>\r\n                                \r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <form class=\"form form-horizontal\" action=\"criarrevenda.php\" method=\"POST\">\r\n                                            <div class=\"form-body\">\r\n                                            <button type=\"button\" class=\"btn btn-primary mr-1 mb-1\" onclick=\"gerar()\">Gerar Aleatorio</button>\r\n                                                <div class=\"row\">\r\n                                                    \r\n\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Usuario</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"usuariorevenda\" placeholder=\"Usuario\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Senha</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"senharevenda\" placeholder=\"Senha\">\r\n                                                    </div>\r\n                                                   \r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Limite</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"number\" class=\"form-control\" value=\"1\" min=\"1\" name=\"limiterevenda\" />\r\n                                                    </div>\r\n\r\n                                                <script>\r\n                                                    function mostrar() {\r\n                                                        var tipo = document.getElementById(\"credivalid\").value;\r\n                                                        if (tipo == \"Validade\") {\r\n                                                            document.getElementById(\"validade\").style.display = \"block\";\r\n                                                        } else {\r\n                                                            document.getElementById(\"validade\").style.display = \"none\";\r\n                                                        }\r\n                                                    }\r\n                                                </script>\r\n                                                ";
if ($_SESSION["tipodeconta"] != "Credito") {
    echo "\r\n                                                <div class=\"col-md-12 form-group\">\r\n                                                    <div id=\"validade\">\r\n                                                        <div class=\"row\">\r\n                                                            <div class=\"col-md-4\">\r\n                                                                <label>Validade em Dias</label>\r\n                                                            </div>\r\n                                                            <div class=\"col-md-8\">\r\n                                                            <input type=\"number\" class=\"form-control\" value=\"30\" min=\"1\" name=\"validaderevenda\" />\r\n                                                            </div>\r\n                                                        </div>\r\n                                                    </div>\r\n                                                </div>";
}
echo "                                                <div class=\"col-12 col-md-8 offset-md-4 form-group\">\r\n                                                    <fieldset>\r\n                                                        \r\n                                                    </fieldset>\r\n                                                    ";
if ($_SESSION["tipodeconta"] != "Credito") {
    echo "<code>Limite Restante: " . $restante . " de " . $_SESSION["limite"] . "</code>";
}
echo "                                                </div>\r\n                                                <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                    <button type=\"submit\" class=\"btn btn-primary mr-1 mb-1\" name=\"submit\">Criar</button>\r\n                                                    <button type=\"reset\" class=\"btn btn-light-secondary mr-1 mb-1\">Cancelar</button>\r\n                                                </div>\r\n                                                </div>\r\n                                            </div>\r\n                                            \r\n                                        </form>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        \r\n                        <script>\r\n                            function gerar() {\r\n                                var usuario = document.getElementsByName(\"usuariorevenda\")[0];\r\n                                var senha = document.getElementsByName(\"senharevenda\")[0];\r\n                                var limite = document.getElementsByName(\"limitefin\")[0];\r\n                                var validade = document.getElementsByName(\"validadefin\")[0];\r\n                                var caracteres = \"abcdefghijklmnopqrstuvwxyz\";\r\n                                var caracteres_senha = \"abcdefghijklmnopqrstuvwxyz\";\r\n                                var usuario_gerado = caracteres.charAt(Math.floor(Math.random() * 26));\r\n                                var senha_gerada = caracteres_senha.charAt(Math.floor(Math.random() * 26));\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    usuario_gerado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));\r\n                                }\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    senha_gerada += caracteres_senha.charAt(Math.floor(Math.random() * caracteres_senha.length));\r\n                                }\r\n                                usuario.value = usuario_gerado;\r\n                                senha.value = senha_gerada;\r\n                                var alerta = document.getElementById(\"alerta\");\r\n                                alerta.innerHTML = \"<div class='alert alert-success' role='alert'>Usuario e Senha Aleatorio Gerado!</div>\";\r\n                                setTimeout(function() {\r\n                                    \$('.alert').fadeOut();\r\n                                }, 1000);\r\n                                \r\n\r\n                            }\r\n                        </script> <script src=\"../app-assets/js/scripts/forms/number-input.js\"></script>\r\n                         <!--scrolling content Modal -->\r\n <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n<div class=\"col-md-6 col-12\">\r\n\r\n<head>\r\n  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css\" integrity=\"sha512-9O9Sd6Ia1+A0+KwUO1eUg0Fyb3J6UdTo68joKgY9A20+RzI2HfIQK8pk6FyUdxUGpIq3oUItrW8jYVGf9GYZRg==\" crossorigin=\"anonymous\" />\r\n</head>\r\n <div class=\"modal fade\" id=\"criado\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalScrollableTitle\" aria-hidden=\"true\">\r\n                                            <div class=\"modal-dialog modal-dialog-scrollable\" role=\"document\">\r\n                                                <div class=\"modal-content\">\r\n                                                    \r\n                                                <script>\r\n                      function copyDivToClipboard() {\r\n                        var range = document.createRange();\r\n                        range.selectNode(document.getElementById(\"divToCopy\"));\r\n                        window.getSelection().removeAllRanges(); // clear current selection\r\n                        window.getSelection().addRange(range); // to select text\r\n                        document.execCommand(\"copy\");\r\n                        window.getSelection().removeAllRanges();// to deselect\r\n                        //alert\r\n                        swal(\"Copiado!\", \"\", \"success\");\r\n\r\n                      }\r\n                    </script>\r\n                                                    <div class=\"bg-alert modal-header\">\r\n                                                        <h5 class=\"modal-title\" id=\"exampleModalScrollableTitle\"></h5>\r\n                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\r\n                                                            <i class=\"bx bx-x\"></i>\r\n                                                        </button>\r\n                                                    </div>\r\n                                                    <style>\r\n                                                        p {\r\n                                                            margin-bottom: 8px;\r\n                                                            }\r\n                                                    </style>\r\n                                                   \r\n                                                    <div class=\"modal-body\" id=\"divToCopy\">\r\n                                                    <div class=\"alert alert-alert\" role=\"alert\" style=\"text-align: center; font-size: 18px;\">\r\n                                                       <div class=\"divider divider-success\">\r\n                                                        <strong class=\"divider-text\" style=\"font-size: 20px;\">🎉 Revendedor Criado 🎉</strong>\r\n                                                        </div>\r\n                                                        <p>🔎 Usuario: ";
echo $_POST["usuariorevenda"];
echo "</p>\r\n                                                        <p>🔑 Senha: ";
echo $_POST["senharevenda"];
echo "</p>\r\n                                                        <p>🎯 Validade: ";
echo $_SESSION["validaderevenda"];
echo "</p>\r\n                                                        <p>🕟 Limite: ";
echo $_POST["limiterevenda"];
echo " </p>\r\n                                                        <p>💥 Obrigado por usar nossos serviços!</p>\r\n                                                        ";
$dominio = $_SERVER["HTTP_HOST"];
echo "<p>🔗 Link do Painel: <a href='https://" . $dominio . "/'>https://" . $dominio . "/</a></p>";
echo "                                                        <div class=\"divider divider-success\">\r\n                                                            <p><strong class=\"divider-text\" style=\"font-size: 20px;\"></strong></p>\r\n                                                            </div>\r\n                                                            \r\n                                                        </div>\r\n                                                    </div>\r\n                                                    <div class=\"modal-footer\">\r\n                                                    <div class=\"btn-group dropup mr-1 mb-1\">\r\n                                                        <style>\r\n                                                            button {\r\n                                                                /* espaço entre os botoes */\r\n                                                                margin-right: 5px;\r\n                                                                }\r\n                                                        </style>\r\n                                        <button type=\"button\" class=\"btn btn-primary dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">\r\n                                            Copiar\r\n                                        </button>\r\n                                        <div class=\"dropdown-menu\">\r\n                                            <a class=\"dropdown-item\" onclick=\"copyDivToClipboard()\">Copiar</a>\r\n                                            <a class=\"dropdown-item\" onclick=\"shareOnWhatsApp()\">Compartilhar no Whatsapp</a>\r\n                                            <a class=\"dropdown-item\" onclick=\"copytotelegram()\">Compartilhar no Telegram</a>\r\n                                        </div>\r\n                                        <button type=\"button\" class=\"btn btn-light-secondary\" data-dismiss=\"modal\">\r\n                                            <i class=\"bx bx-x d-block d-sm-none\"></i>\r\n                                            <span class=\"d-none d-sm-block\">Lista de Usuarios</span>\r\n                                        </button>\r\n                                        \r\n                                    </div>\r\n                                                        <!-- botao de copiar whatsapp  https://cdn.discordapp.com/attachments/968040569769181194/1077873044296585216/whatsapp.png-->\r\n                                                        \r\n\r\n<script>\r\nfunction shareOnWhatsApp() {\r\n  var text = \"🎉 Revendedor Criado! 🎉\\n\" + \r\n             \"🔎 Usuario: ";
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
echo "\\n\" +\r\n        \"💥 Obrigado por usar nossos serviços!\\n\\n\" +\r\n        '\" \"';\r\n\r\n    var encodedText = encodeURIComponent(text);\r\n    var telegramUrl = \"https://t.me/share/url?url=\" + encodedText;\r\n\r\n    window.open(telegramUrl);\r\n}\r\n</script>\r\n\r\n\r\n\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        <script>\r\n\r\n\r\n                        </script>\r\n\r\n                       \r\n \r\n                       <script src=\"../../../app-assets/js/scripts/pages/bootstrap-toast.js\"></script>\r\n                       <script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
if (isset($_POST["submit"])) {
    if ($_SESSION["tipodeconta"] == "Credito") {
        if (empty($_POST["usuariorevenda"]) || empty($_POST["senharevenda"]) || empty($_POST["limiterevenda"]) || $_SESSION["limite"] < $_POST["limiterevenda"]) {
            echo "<script>swal('Erro ao Criar, Verifique as informações ou se você possui limite!').then(function() {window.location.href = 'criarrevenda.php';});</script>";
            exit;
        }
        $_SESSION["validaderevenda"] = $_POST["validaderevenda"];
    } else {
        if (empty($_POST["usuariorevenda"]) || empty($_POST["senharevenda"]) || empty($_POST["limiterevenda"]) || empty($_POST["validaderevenda"]) || $restante < $_POST["limiterevenda"] || $_POST["limiterevenda"] < 1) {
            echo "<script>swal('Erro ao Criar, Verifique as informações ou se você possui limite!').then(function() {window.location.href = 'criarrevenda.php';});</script>";
            exit;
        }
    }
    $sql7 = "SELECT * FROM accounts";
    $result7 = mysqli_query($conn, $sql7);
    while ($row = $result7->fetch_assoc()) {
        if ($row["login"] == $_POST["usuariorevenda"]) {
            echo "<script>swal('Erro ao Criar, Usuario ja existe!').then(function() {window.location.href = 'criarrevenda.php';});</script>";
            exit;
        }
    }
    $credivalid = $_POST["credivalid"];
    date_default_timezone_set("America/Sao_Paulo");
    $datahoje = date("d-m-Y H:i:s");
    $sql10 = "INSERT INTO logs (revenda, byid, validade, texto, userid) VALUES ('" . $_SESSION["login"] . "', '" . $_SESSION["byid"] . "', '" . $datahoje . "', 'Criou o Revendedor " . $_POST["usuariorevenda"] . "', '" . $_SESSION["iduser"] . "')";
    $result10 = mysqli_query($conn, $sql10);
    $slq5 = "INSERT INTO accounts (login, senha, byid) VALUES ('" . $_POST["usuariorevenda"] . "', '" . $_POST["senharevenda"] . "', '" . $_SESSION["iduser"] . "')";
    $result5 = mysqli_query($conn, $slq5);
    $sql6 = "SELECT id FROM accounts WHERE login = '" . $_POST["usuariorevenda"] . "'";
    $result6 = mysqli_query($conn, $sql6);
    while ($row = $result6->fetch_assoc()) {
        $idrevenda = $row["id"];
    }
    $_POST["validaderevenda"] = $_POST["validaderevenda"] * 86400;
    $_POST["validaderevenda"] = $_POST["validaderevenda"] + time();
    $_POST["validaderevenda"] = date("Y-m-d H:i:s", $_POST["validaderevenda"]);
    $sql3 = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["iduser"] . "'";
    $result3 = mysqli_query($conn, $sql3);
    $row3 = mysqli_fetch_assoc($result3);
    $limiteatual = $row3["limite"];
    $limiteatual = $limiteatual - $_POST["limiterevenda"];
    if ($_SESSION["tipodeconta"] == "Credito") {
        $sql = "UPDATE atribuidos SET limite = '" . $limiteatual . "' WHERE userid = '" . $_SESSION["iduser"] . "'";
        $result = mysqli_query($conn, $sql);
    }
    if ($_SESSION["tipodeconta"] == "Credito") {
        $credivalid = "Credito";
        $sql7 = "INSERT INTO atribuidos (categoriaid, userid, byid, limite, tipo) VALUES ('" . $categoria . "', '" . $idrevenda . "', '" . $_SESSION["iduser"] . "', '" . $_POST["limiterevenda"] . "', '" . $credivalid . "')";
        $result7 = mysqli_query($conn, $sql7);
        $_SESSION["validaderevenda"] = "Nunca";
    } else {
        $credivalid = "Validade";
        $sql7 = "INSERT INTO atribuidos (categoriaid, userid, byid, limite, tipo, expira) VALUES ('" . $categoria . "', '" . $idrevenda . "', '" . $_SESSION["iduser"] . "', '" . $_POST["limiterevenda"] . "', '" . $credivalid . "', '" . $_POST["validaderevenda"] . "')";
        $result7 = mysqli_query($conn, $sql7);
    }
    $dominiopainel = $_SERVER["HTTP_HOST"];
    echo "<script>\$('#criado').modal('show');</script>";
}

?>