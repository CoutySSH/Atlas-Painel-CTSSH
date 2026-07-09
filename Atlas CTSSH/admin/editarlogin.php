<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
if (!isset($_SESSION)) {
    error_reporting(0);
    session_start();
}
set_time_limit(0);
ignore_user_abort(true);
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "Net/SSH2.php";
include "../vendor/event/autoload.php";
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:index.php");
}
if ($_SESSION["login"] != "admin") {
    session_destroy();
    header("Location: index.php");
    exit;
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
    $sql = "SELECT * FROM ssh_accounts WHERE id = '" . $id . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $_SESSION["logineditar"] = $row["login"];
    $_SESSION["senhaeditar"] = $row["senha"];
    $_SESSION["validadeeditar"] = $row["expira"];
    $_SESSION["limiteeditar"] = $row["limite"];
    $_SESSION["categoriaeditar"] = $row["categoriaid"];
    $notas = $row["lastview"];
    $valormensal = $row["valormensal"];
    $whatsapp = $row["whatsapp"];
}
unset($_SESSION["whatsapp"]);
$logineditar = $_SESSION["logineditar"];
$senhaeditar = $_SESSION["senhaeditar"];
$validadeeditar = $_SESSION["validadeeditar"];
$limiteeditar = $_SESSION["limiteeditar"];
$validadeeditar = date("Y-m-d", strtotime($validadeeditar));
$data = date("Y-m-d");
$diferenca = strtotime($validadeeditar) - strtotime($data);
$dias = floor($diferenca / 86400);
$sql = "SELECT limite FROM atribuidos WHERE id = '" . $_SESSION["iduser"] . "'";
$result = $conn->prepare($sql);
$result->execute();
$result->bind_result($limiteatual);
$result->fetch();
$result->close();
$_SESSION["limiteatual"] = $limiteatual;
$slq2 = "SELECT sum(limite) AS limiteusado  FROM atribuidos where byid='" . $_SESSION["iduser"] . "' ";
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
$sql2 = "SELECT * FROM servidores WHERE subid = '" . $_SESSION["categoriaeditar"] . "'";
$result77 = $conn->query($sql2);
echo "\r\n          <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui Você Editar o Login do Cliente.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\">\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Editar Usuário</h4>\r\n                                </div>\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <form class=\"form form-horizontal\" action=\"editarlogin.php\" method=\"POST\">\r\n                                            <div class=\"form-body\">\r\n                                                <div class=\"row\">\r\n                                                  \r\n\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Login</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"usuarioedit\" placeholder=\"Login\" value=\"";
echo $logineditar;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Senha</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"senhaedit\" placeholder=\"Senha\" value=\"";
echo $senhaeditar;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Limite</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" min=\"1\" name=\"limiteedit\" value=\"";
echo $limiteeditar;
echo "\" />\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Dias</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" min=\"1\" name=\"validadeedit\" value=\"";
echo $dias;
echo "\" />\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Valor Mensal</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" min=\"1\" name=\"valormensal\" placeholder=\"Exemplo: 10\" value=\"";
echo $valormensal;
echo "\" />\r\n                                                    </div>\r\n                                            \r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Notas</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"notas\" placeholder=\"Notas\" value=\"";
echo $notas;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Numero Whatsapp (NUMERO IGUAL AO WHATSAPP)</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"whatsapp\" placeholder=\"+5511999999999\" value=\"";
echo $whatsapp;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-12 col-md-8 offset-md-4 form-group\">\r\n                                                        <fieldset>\r\n                                                            \r\n                                                        </fieldset>\r\n                                                    </div>\r\n                                                    <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                        <button type=\"submit\" class=\"btn btn-primary mr-1 mb-1\" name=\"editauser\">Editar</button>\r\n                                                        <button onclick=\"sair()\" type=\"button\" class=\"btn btn-light-secondary mr-1 mb-1\">Voltar</button>\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </form>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        \r\n                        <script>\r\n                            function sair() {\r\n                                window.location.href = \"listarusuarios.php\";\r\n                            }\r\n                            function gerar() {\r\n                                var usuario = document.getElementsByName(\"usuariofin\")[0];\r\n                                var senha = document.getElementsByName(\"senhafin\")[0];\r\n                                var limite = document.getElementsByName(\"limitefin\")[0];\r\n                                var validade = document.getElementsByName(\"validadefin\")[0];\r\n                                var caracteres = \"0123456789abcdefghijklmnopqrstuvwxyz\";\r\n                                var caracteres_senha = \"0123456789abcdefghijklmnopqrstuvwxyz\";\r\n                                var usuario_gerado = \"\";\r\n                                var senha_gerada = \"\";\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    usuario_gerado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));\r\n                                }\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    senha_gerada += caracteres_senha.charAt(Math.floor(Math.random() * caracteres_senha.length));\r\n                                }\r\n                                usuario.value = usuario_gerado;\r\n                                senha.value = senha_gerada;\r\n                                limite.value = 1;\r\n                                validade.value = 30;\r\n                            }\r\n                        </script> <script src=\"../app-assets/js/scripts/forms/number-input.js\"></script>\r\n\r\n";
if (isset($_POST["editauser"])) {
    $usuarioedit = $_POST["usuarioedit"];
    $senhaedit = $_POST["senhaedit"];
    $validadeedit = $_POST["validadeedit"];
    $limiteedit = $_POST["limiteedit"];
    $notas = $_POST["notas"];
    $valormensal = $_POST["valormensal"];
    $whatsapp = $_POST["whatsapp"];
    $whatsapp = str_replace(" ", "", $whatsapp);
    $whatsapp = str_replace("-", "", $whatsapp);
    if ($valormensal == "") {
        $valormensal = 0;
    }
    $sql = "SELECT * FROM ssh_accounts WHERE login = '" . $usuarioedit . "'";
    $result = $conn->query($sql);
    if (0 < $result->num_rows && $usuarioedit != $logineditar) {
        echo "<script>swal('Erro', 'Usuario ja existe', 'error').then(function() { window.location = 'listarusuarios.php'; });</script>";
        exit;
    }
    date_default_timezone_set("America/Sao_Paulo");
    $validade = $validadeedit;
    $data = date("Y-m-d H:i:s");
    $data = strtotime($data);
    $data = strtotime("+" . $validadeedit . " days", $data);
    $data = date("Y-m-d H:i:s", $data);
    $validadeedit = $data;
    $loop = React\EventLoop\Factory::create();
    $servidores_com_erro = [];
    $sucess_servers = [];
    $failed_servers = [];
    $sucess = false;
    while ($user_data = mysqli_fetch_assoc($result77)) {
        $tentativas = 0;
        $conectado = false;
        while ($tentativas < 2 && !$conectado) {
            $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
            if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
                $loop->addTimer(0, function () use($ssh) {
                    $ssh->exec("./atlasremove.sh " . $logineditar . "  || true && ./atlascreate.sh " . $usuarioedit . " " . $senhaedit . " " . $validade . " " . $limiteedit . " > /dev/null 2>&1");
                    $ssh->exec("rm -rf /etc/SSHPlus/userteste/" . $logineditar . ".sh > /dev/null 2>&1 || true > /dev/null 2>&1");
                    $ssh->disconnect();
                });
                $sucess_servers[] = $user_data["nome"];
                $conectado = true;
                $sucess = true;
            } else {
                $tentativas++;
            }
        }
        if (!$conectado) {
            $servidores_com_erro[] = $user_data["ip"];
            $failed_servers[] = $user_data["nome"];
        }
    }
    foreach ($servidores_com_erro as $ip) {
        $sql2 = "SELECT id, ip, porta, usuario, senha FROM servidores WHERE ip = '" . $ip . "'";
        $result2 = mysqli_query($conn, $sql2);
        $user_data2 = mysqli_fetch_assoc($result2);
        $tentativas = 0;
        $conectado = false;
        while ($tentativas < 2 && !$conectado) {
            $ssh = new Net_SSH2($user_data2["ip"], $user_data2["porta"]);
            if ($ssh->login($user_data2["usuario"], $user_data2["senha"])) {
                $loop->addTimer(0, function () use($ssh) {
                    $ssh->exec("./atlasremove.sh " . $logineditar . "  || true && ./atlascreate.sh " . $usuarioedit . " " . $senhaedit . " " . $validade . " " . $limiteedit . "  || true && rm -rf /etc/SSHPlus/userteste/" . $logineditar . ".sh > /dev/null 2>&1 || true > /dev/null 2>&1");
                    $ssh->disconnect();
                });
                $sucess_servers[] = $user_data["nome"];
                $conectado = true;
                $sucess = true;
            } else {
                $tentativas++;
            }
        }
        if (!$conectado) {
            $failed_servers[] = $user_data2["nome"];
        }
    }
    if ($sucess) {
        $_SESSION["usuariofin"] = $usuarioedit;
        $_SESSION["senhafin"] = $senhaedit;
        $_SESSION["validadefin"] = $validadeedit;
        $_SESSION["limitefin"] = $limiteedit;
        $valormensal = $_POST["valormensal"];
        $sucess_servers_str = implode(", ", $sucess_servers);
        $failed_servers_str = implode(", ", $failed_servers);
        $sql = "UPDATE ssh_accounts SET login = '" . $usuarioedit . "', senha = '" . $senhaedit . "', expira = '" . $validadeedit . "', limite = '" . $limiteedit . "', mainid = '', lastview = '" . $notas . "', valormensal = '" . $valormensal . "', whatsapp = '" . $whatsapp . "' WHERE login = '" . $logineditar . "'";
        $sql = $conn->prepare($sql);
        $sql->execute();
        echo "<script>window.location.href = 'criado.php?sucess=" . $sucess_servers_str . "&failed=" . $failed_servers_str . "';</script>";
    } else {
        echo "<script>alert('Erro ao editar usuario');</script>";
    }
    $loop->run();
}
echo "\r\n";
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