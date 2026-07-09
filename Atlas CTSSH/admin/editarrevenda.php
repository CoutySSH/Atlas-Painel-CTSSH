<?php


if (!isset($_SESSION)) {
    error_reporting(0);
    session_start();
}
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:index.php");
}
include "headeradmin2.php";
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$_GET["id"] = anti_sql($_GET["id"]);
if (!empty($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM accounts WHERE id = '" . $id . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $login = $row["login"];
    $senha = $row["senha"];
    $whatsapp = $row["whatsapp"];
}
$sql = "SELECT * FROM atribuidos WHERE userid = '" . $id . "'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$limite = $row["limite"];
$validade = $row["expira"];
$tipo = $row["tipo"];
$categoria = $row["categoriaid"];
$valormensal = $row["valormensal"];
$_SESSION["idrevenda"] = $id;
if ($tipo == "Credito") {
    $modo = "Creditos";
} else {
    $modo = "Limite";
    if ($validade < date("Y-m-d")) {
        echo "<script>alert('Essa conta está Vencida!');</script>";
    }
}
$idusuario = $id;
$validade = date("Y-m-d", strtotime($validade));
$data = date("Y-m-d");
$diferenca = strtotime($validade) - strtotime($data);
$dias = floor($diferenca / 86400);
$sql = "SELECT limite FROM atribuidos WHERE id = '" . $_SESSION["iduser"] . "'";
$result = $conn->prepare($sql);
$result->execute();
$result->bind_result($limiteatual);
$result->fetch();
$result->close();
$_SESSION["limiteatual"] = $limiteatual;
$slq2 = "SELECT sum(limite) AS limiteusado  FROM atribuidos where byid='" . $id . "' ";
$result = $conn->prepare($slq2);
$result->execute();
$result->bind_result($limiteusado);
$result->fetch();
$result->close();
$_SESSION["limiteusado"] = $limiteusado;
$sql3 = "SELECT * FROM atribuidos WHERE byid = '" . $_SESSION["iduser"] . "'";
$sql3 = $conn->prepare($sql3);
$sql3->execute();
$sql3->store_result();
$num_rows = $sql3->num_rows;
$_SESSION["numrevendedores"] = $num_rows;
$sql4 = "SELECT * FROM ssh_accounts WHERE byid = '" . $_SESSION["iduser"] . "'";
$sql4 = $conn->prepare($sql4);
$sql4->execute();
$sql4->store_result();
$num_rows = $sql4->num_rows;
$_SESSION["numusuarios"] = $num_rows;
$limiteusado = $_SESSION["limiteusado"] + $_SESSION["numusuarios"];
$_SESSION["limiteusado"] = $limiteusado;
$restante = $limiteatual - $limiteusado;
$_SESSION["restante"] = $restante;
$slq2 = "SELECT sum(limite) AS limiteusadorevenda  FROM atribuidos where byid='" . $id . "' ";
$result = $conn->prepare($slq2);
$result->execute();
$result->bind_result($limiteusadorevenda);
$result->fetch();
$result->close();
$_SESSION["limiteusadorevenda"] = $limiteusadorevenda;
$sql4 = "SELECT sum(limite) AS limiteusadousuario FROM ssh_accounts WHERE byid = '" . $id . "'";
$sql4 = $conn->prepare($sql4);
$sql4->execute();
$sql4->bind_result($limiteusadousuario);
$sql4->fetch();
$sql4->close();
$somausado = $_SESSION["limiteusadorevenda"] + $limiteusadousuario;
$_SESSION["idrevenda"] = $id;
$sql1 = "SELECT * FROM categorias";
$result1 = $conn->query($sql1);
if (0 < $result1->num_rows) {
    while ($row1 = $result1->fetch_assoc()) {
        $categorias[] = $row1;
    }
}
echo "\r\n<div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui você pode Editar o Revendedor.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\">\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Editar Revendedor</h4>\r\n                                </div>\r\n\r\n                                <div id=\"alerta\">\r\n                                </div>\r\n                                \r\n                                \r\n                                <div class=\"card-content\">\r\n                                    \r\n                                    <div class=\"card-body\">\r\n                                    <p class=\"card-description\"> Você Esta Editando o Revendedor(a)<code> ";
echo $login;
echo " </code></p>\r\n                                    <form class=\"form form-horizontal\" action=\"editarrev.php\" method=\"POST\">\r\n                                            <div class=\"form-body\">\r\n                                                ";
if ($tipo == "Credito") {
    echo "\r\n                                                     <div class=\"col-md-12 form-group\">\r\n                                                        <div id=\"validade\">\r\n                                                            <div class=\"row\">\r\n                                                                <div class=\"col-md-4\">\r\n                                                                    <label>Adicionar Creditos</label>\r\n                                                                </div>\r\n                                                                <div class=\"input-group\">\r\n                                                                    <input type=\"number\" name=\"quantidadecreditosadd\" class=\"form-control\" placeholder=\"Quantidade de creditos\" aria-describedby=\"Quantidade de creditos\">\r\n                                                                    <div class=\"input-group-append\" id=\"button-addon2\">\r\n                                                                        <button name=\"addcreditos\" class=\"btn btn-primary\" type=\"submit\">Add</button>\r\n                                                                    </div>\r\n                                                                </div>\r\n                                                            </div>\r\n                                                            \r\n                                                        </div>\r\n                                                    </div>\r\n                                                    ";
}
echo "                                                \r\n                                            </form>\r\n                                           \r\n                                                <form class=\"form form-horizontal\" action=\"editarrev.php\" method=\"POST\">\r\n                                                \r\n                                            <p class=\"card-description\">";
if ($tipo != "Credito") {
    echo "<br>Usando<code>" . $somausado . "</code>Modo " . $tipo . "</p>";
}
echo "                                           \r\n                                                <div class=\"row\">\r\n                                                  \r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Usuario</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"usuarioedit\" placeholder=\"Usuario\" value=\"";
echo $login;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Senha</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"senhaedit\" placeholder=\"Senha\" value=\"";
echo $senha;
echo "\">\r\n                                                    </div>\r\n                                           \r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>";
echo $modo;
echo "</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"number\" class=\"form-control\" value=\"";
echo $limite;
echo "\" min=\"0\" name=\"limiteedit\" />\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Numero Whatsapp (NUMERO IGUAL AO WHATSAPP)</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"whatsapp\" placeholder=\"+5511999999999\" value=\"";
echo $whatsapp;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Modo</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                                <select class=\"form-control\" name=\"modorevenda\">\r\n                                                                    <option value=\"Validade\" ";
if ($tipo == "Validade") {
    echo "selected";
}
echo ">Validade</option>\r\n                                                                    <option value=\"Credito\" ";
if ($tipo == "Credito") {
    echo "selected";
}
echo ">Credito</option>\r\n                                                                </select>\r\n                                                            </div>\r\n                                                    ";
if ($tipo == "Validade") {
    echo "<div class=\"col-md-12 form-group\">\r\n                                                        <div id=\"validade\">\r\n                                                        <div class=\"row\">\r\n                                                                <div class=\"col-md-4\">\r\n                                                                    <label>Validade em Dias</label>\r\n                                                                </div>\r\n                                                                <div class=\"col-md-8\">\r\n                                                                <input type=\"number\" class=\"form-control\" value=\"" . $dias . "\" min=\"1\" max=\"365\" name=\"validadeedit\" />\r\n                                                                </div>\r\n                                                            </div>\r\n                                                            </div>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-12 form-group\">\r\n                                                    <div class=\"row\">\r\n                                                    <div class=\"col-md-4\">\r\n                                                    <label>Valor Mensal</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8\">\r\n                                                    <input type=\"number\" class=\"form-control\" value=\"" . $valormensal . "\" name=\"valormensal\" placeholder=\"Exemplo: 10\" />\r\n                                                                </div>\r\n                                                            </div>\r\n                                                        </div>\r\n                                                    </div>\r\n\r\n                                                    ";
}
echo "                                                \r\n                                                <div class=\"col-12 col-md-8 offset-md-4 form-group\">\r\n                                                    <fieldset>\r\n                                                        \r\n                                                \r\n                                                    <p class=\"card-description\"> Categoria Atual: ";
echo $categoria;
echo " </p><!-- dropdow migra categoria -->\r\n                      <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">\r\n                        Migrar Para Categoria\r\n                      </button>\r\n                      <style type=\"text/css\">\r\n                          .dropdown-menu .dropdown-item:hover {\r\n                              background-color: #fff;\r\n                            }\r\n                            </style>\r\n                      <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\" style=\"color: white;\">\r\n                      ";
foreach ($categorias as $categoria) {
    echo "<a class=\"dropdown-item\" onclick=\"confirm(" . $categoria["subid"] . ")\"\">" . $categoria["nome"] . " - " . $categoria["subid"] . "</a>";
}
echo "<br>                      <script type=\"text/javascript\">\r\n                      function confirm(id) {\r\n                        var idusuario = ";
echo $idusuario;
echo ";\r\n                        swal({\r\n                          title: \"Tem certeza?\",\r\n                          text: \"Você não poderá reverter isso!\",\r\n                          icon: \"warning\",\r\n                          buttons: true,\r\n                          dangerMode: true,\r\n                        })\r\n                        .then((willDelete) => {\r\n                          if (willDelete) {\r\n                            window.location.href = \"migrarcategoria.php?id=\"+id+\"&idusuario=\"+idusuario;\r\n                          } else {\r\n                            swal(\"Ação cancelada!\");\r\n                          }\r\n                        });\r\n                      }\r\n                      </script>\r\n                                                    </fieldset>\r\n                                                    \r\n                                                </div>\r\n                                                \r\n                                                \r\n                                                <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                    <button type=\"submit\" class=\"btn btn-primary mr-1 mb-1\" name=\"editarrev\">Editar</button>\r\n                                                    <a href=\"listarrevendedores.php\" class=\"btn btn-light-secondary mr-1 mb-1\">Cancelar</a>\r\n                                                </div>\r\n                                                </div>\r\n                                            </div>\r\n                                            \r\n                                        </form>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        \r\n                        <script>\r\n                            function gerar() {\r\n                                var usuario = document.getElementsByName(\"usuariorevenda\")[0];\r\n                                var senha = document.getElementsByName(\"senharevenda\")[0];\r\n                                var limite = document.getElementsByName(\"limitefin\")[0];\r\n                                var validade = document.getElementsByName(\"validadefin\")[0];\r\n                                var caracteres = \"abcdefghijklmnopqrstuvwxyz\";\r\n                                var caracteres_senha = \"abcdefghijklmnopqrstuvwxyz\";\r\n                                var usuario_gerado = caracteres.charAt(Math.floor(Math.random() * 26));\r\n                                var senha_gerada = caracteres_senha.charAt(Math.floor(Math.random() * 26));\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    usuario_gerado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));\r\n                                }\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    senha_gerada += caracteres_senha.charAt(Math.floor(Math.random() * caracteres_senha.length));\r\n                                }\r\n                                usuario.value = usuario_gerado;\r\n                                senha.value = senha_gerada;\r\n                                var alerta = document.getElementById(\"alerta\");\r\n                                alerta.innerHTML = \"<div class='alert alert-success' role='alert'>Usuario e Senha Aleatorio Gerado!</div>\";\r\n                                setTimeout(function() {\r\n                                    \$('.alert').fadeOut();\r\n                                }, 1000);\r\n                                \r\n\r\n                            }\r\n                        </script> <script src=\"../app-assets/js/scripts/forms/number-input.js\"></script>\r\n                         <!--scrolling content Modal -->\r\n <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n<div class=\"col-md-6 col-12\">\r\n\r\n<head>\r\n  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css\" integrity=\"sha512-9O9Sd6Ia1+A0+KwUO1eUg0Fyb3J6UdTo68joKgY9A20+RzI2HfIQK8pk6FyUdxUGpIq3oUItrW8jYVGf9GYZRg==\" crossorigin=\"anonymous\" />\r\n</head>\r\n <div class=\"modal fade\" id=\"criado\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalScrollableTitle\" aria-hidden=\"true\">\r\n                                            <div class=\"modal-dialog modal-dialog-scrollable\" role=\"document\">\r\n                                                <div class=\"modal-content\">\r\n                                                    \r\n                                                <script>\r\n                      function copyDivToClipboard() {\r\n                        var range = document.createRange();\r\n                        range.selectNode(document.getElementById(\"divToCopy\"));\r\n                        window.getSelection().removeAllRanges(); // clear current selection\r\n                        window.getSelection().addRange(range); // to select text\r\n                        document.execCommand(\"copy\");\r\n                        window.getSelection().removeAllRanges();// to deselect\r\n                        //alert\r\n                        swal(\"Copiado!\", \"\", \"success\");\r\n\r\n                      }\r\n                    </script>\r\n                                                    <div class=\"bg-alert modal-header\">\r\n                                                        <h5 class=\"modal-title\" id=\"exampleModalScrollableTitle\"></h5>\r\n                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\r\n                                                            <i class=\"bx bx-x\"></i>\r\n                                                        </button>\r\n                                                    </div>\r\n                                                    <style>\r\n                                                        p {\r\n                                                            margin-bottom: 8px;\r\n                                                            }\r\n                                                    </style>\r\n                                                    <div class=\"modal-body\" id=\"divToCopy\">\r\n                                                    <div class=\"alert alert-alert\" role=\"alert\" style=\"text-align: center; font-size: 18px;\">\r\n                                                       <div class=\"divider divider-success\">\r\n                                                            \r\n                                                        <strong class=\"divider-text\" style=\"font-size: 20px;\">🎉 Revendedor Criado 🎉</strong>\r\n                                                        </div>\r\n                                                        <p>🔎 Usuario: ";
echo $_POST["usuariorevenda"];
echo "</p>\r\n                                                        <p>🔑 Senha: ";
echo $_POST["senharevenda"];
echo "</p>\r\n                                                        <p>🎯 Validade: ";
echo $_POST["validaderevenda"];
echo " Dias</p>\r\n                                                        <p>🕟 Limite: ";
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
echo "\\n\" +\r\n        \"💥 Obrigado por usar nossos serviços!\\n\\n\" +\r\n        '\" \"';\r\n\r\n    var encodedText = encodeURIComponent(text);\r\n    var telegramUrl = \"https://t.me/share/url?url=\" + encodedText;\r\n\r\n    window.open(telegramUrl);\r\n}\r\n</script>\r\n\r\n\r\n\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        <script>\r\n\r\n\r\n                        </script>\r\n\r\n                       \r\n \r\n                       <script src=\"../../../app-assets/js/scripts/pages/bootstrap-toast.js\"></script>\r\n                       <script src=\"../../../app-assets/js/scripts/extensions/sweet-alerts.js\"></script>\r\n                       <script src=\"../app-assets/sweetalert.min.js\"></script>";
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