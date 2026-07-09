<?php


error_reporting(0);
session_start();
set_time_limit(0);
ignore_user_abort(true);
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "Net/SSH2.php";
include "../vendor/event/autoload.php";
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
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
$slq2 = "SELECT sum(limite) AS numusuarios  FROM ssh_accounts where byid='" . $_SESSION["iduser"] . "' ";
$result = $conn->prepare($slq2);
$result->execute();
$result->bind_result($numusuarios);
$result->fetch();
$result->close();
$limiteusado = $limiteusado + $numusuarios;
$restante = $_SESSION["limite"] - $limiteusado;
$_SESSION["restante"] = $restante;
$sql5 = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["iduser"] . "'";
$sql5 = $conn->query($sql5);
$row = $sql5->fetch_assoc();
$validade = $row["expira"];
$categoria = $row["categoriaid"];
$tipo = $row["tipo"];
$_SESSION["tipodeconta"] = $row["tipo"];
$_SESSION["limite"] = $row["limite"];
if ($tipo == "Credito") {
    $tipo = "<code>Restam " . $_SESSION["limite"] . " Creditos</code>";
} else {
    $tipo = "<code>Seu limite usado é de " . $limiteusado . " Logins de " . $_SESSION["limite"] . "</code>";
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
        if ($restante < 1) {
            echo "<script>alert('Limite Atingido')</script><script>window.location.href = '../home.php'</script>";
            unset($_POST["criaruser"]);
            unset($_POST["usuariofin"]);
            unset($_POST["senhafin"]);
            unset($_POST["validadefin"]);
        }
    }
}
$sql2 = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
$result = $conn->query($sql2);
while ($row = $result->fetch_assoc()) {
    $servidores[] = $row;
}
echo "              ";
include "header2.php";
if (isset($_POST["criaruser"])) {
    $_SESSION["usuariofin"] = $_POST["usuariofin"];
    $_SESSION["senhafin"] = $_POST["senhafin"];
    if ($_SESSION["tipodeconta"] == "Credito") {
        $_SESSION["validadefin"] = "31";
    } else {
        $_SESSION["validadefin"] = $_POST["validadefin"];
    }
    if ($_SESSION["tipodeconta"] != "Credito") {
        if ($_SESSION["restante"] < $_POST["limitefin"]) {
            echo "<script language='javascript' type='text/javascript'>alert('Ops.. Você não tem limite suficiente!');window.location.href='../home.php';</script>";
            exit;
        }
    }
    $_SESSION["limitefin"] = $_POST["limitefin"];
    $usuariofin = $_POST["usuariofin"];
    $senhafin = $_POST["senhafin"];
    if ($usuariofin == "") {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário não pode ser vazio!');window.location.href='criarusuario.php';</script>";
        exit;
    }
    if ($senhafin == "") {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Senha não pode ser vazia!');window.location.href='criarusuario.php';</script>";
        exit;
    }
    if (preg_match("/[^a-z0-9]/i", $usuariofin)) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário não pode conter caracteres especiais!');window.location.href='criarusuario.php';</script>";
        exit;
    }
    if (preg_match("/[^a-z0-9]/i", $senhafin)) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Senha não pode conter caracteres especiais!');window.location.href='criarusuario.php';</script>";
        exit;
    }
    if ($_SESSION["limite"] < $_POST["limitefin"]) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Você não tem limite suficiente!');window.location.href='criarusuario.php';</script>";
        exit;
    }
    $validadefin = $_SESSION["validadefin"];
    $validadelog = $_SESSION["validadefin"];
    $limitefin = $_POST["limitefin"];
    $sql = "SELECT * FROM ssh_accounts WHERE login = '" . $usuariofin . "'";
    $result = $conn->query($sql);
    if (0 < $result->num_rows) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário já existe!');window.location.href='criarusuario.php';</script>";
        exit;
    }
    $sql = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
    $result = $conn->query($sql);
    $loop = React\EventLoop\Factory::create();
    $servidores_com_erro = [];
    define("SCRIPT_PATH", "./atlascreate.sh");
    $sucess_servers = [];
    $failed_servers = [];
    $sucess = false;
    while ($user_data = mysqli_fetch_assoc($result)) {
        $tentativas = 0;
        $conectado = false;
        while ($tentativas < 2 && !$conectado) {
            $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
            if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
                $loop->addTimer(0, function () use($ssh) {
                    $ssh->exec(SCRIPT_PATH . " " . $usuariofin . " " . $senhafin . " " . $validadefin . " " . $limitefin . " ");
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
                    $ssh->exec(SCRIPT_PATH . " " . $usuariofin . " " . $senhafin . " " . $validadefin . " " . $limitefin . " > /dev/null 2>&1 &");
                    $ssh->disconnect();
                });
                $sucess_servers[] = $user_data2["nome"];
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
    if (!$sucess) {
        echo "<script>alert('Erro ao criar usuário!');window.location.href='criarusuario.php';</script>";
        exit;
    }
    if ($sucess) {
        $sucess_servers_str = implode(", ", $sucess_servers);
        $failed_servers_str = implode(", ", $failed_servers);
        echo "<script>window.location.href = 'criado.php?sucess=" . $sucess_servers_str . "&failed=" . $failed_servers_str . "';</script>";
        date_default_timezone_set("America/Sao_Paulo");
        $data = date("Y-m-d H:i:s");
        $data = strtotime($data);
        $data = strtotime("+" . $validadefin . " days", $data);
        $data = date("Y-m-d H:i:s", $data);
        $validadefin = $data;
        $sql9 = "INSERT INTO ssh_accounts (login, senha, expira, limite, byid, categoriaid, lastview, bycredit, mainid, status) VALUES ('" . $usuariofin . "', '" . $senhafin . "', '" . $validadefin . "', '" . $limitefin . "', '" . $_SESSION["iduser"] . "', '" . $categoria . "', '" . $_POST["notas"] . "', '0', 'NULL', 'Offline')";
        $result9 = mysqli_query($conn, $sql9);
        date_default_timezone_set("America/Sao_Paulo");
        $datahoje = date("d-m-Y H:i:s");
        $sql10 = "INSERT INTO logs (revenda, byid, validade, texto, userid) VALUES ('" . $_SESSION["login"] . "', '" . $_SESSION["byid"] . "', '" . $datahoje . "', 'Criou um Usuario " . $usuariofin . " de " . $validadelog . " Dias', '" . $_SESSION["iduser"] . "')";
        $result10 = mysqli_query($conn, $sql10);
        if ($_SESSION["tipodeconta"] == "Credito") {
            $total = $_SESSION["limite"] - $limitefin;
            $sql11 = "UPDATE atribuidos SET limite = '" . $total . "' WHERE userid = '" . $_SESSION["iduser"] . "'";
            $result11 = mysqli_query($conn, $sql11);
        }
    }
    $_SESSION["validadefin"] = $validadefin;
    $loop->run();
}
echo "    <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui você pode criar um usuário para seus clientes.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\">\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Criar Usuário</h4>\r\n                                </div>\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <form class=\"form form-horizontal\" action=\"criarusuario.php\" method=\"POST\">\r\n                                            <div class=\"form-body\">\r\n                                            <button type=\"button\" class=\"btn btn-primary mr-1 mb-1\" onclick=\"gerar()\">Gerar Aleatorio</button>\r\n                                            <p class=\"text-primary\">";
echo $tipo;
echo "</p>\r\n                                                <div class=\"row\">\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Login</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"usuariofin\" placeholder=\"Login\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Senha</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"senhafin\" placeholder=\"Senha\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Limite</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" value=\"1\" min=\"1\" name=\"limitefin\" />\r\n                                                    </div>\r\n                                                    ";
if ($_SESSION["tipodeconta"] != "Credito") {
    echo "<div class=\"col-md-4\">\r\n                                                        <label>Dias</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" value=\"30\" min=\"1\" name=\"validadefin\" />\r\n                                                    </div>\r\n                                                    ";
}
echo "                                                    <div class=\"col-md-4\">\r\n                                                        <label>Notas</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"notas\" placeholder=\"Notas\">\r\n                                                    </div>\r\n                                                    <div class=\"col-12 col-md-8 offset-md-4 form-group\">\r\n                                                        <fieldset>\r\n                                                            \r\n                                                        </fieldset>\r\n                                                    </div>\r\n                                                    <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                        \r\n                                                        <button type=\"submit\" class=\"btn btn-primary mr-1 mb-1\" name=\"criaruser\">Criar</button>\r\n                                                        <button type=\"reset\" class=\"btn btn-light-secondary mr-1 mb-1\">Cancelar</button>\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </form>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        <script>\r\n                            function gerar() {\r\n                                var usuario = document.getElementsByName(\"usuariofin\")[0];\r\n                                var senha = document.getElementsByName(\"senhafin\")[0];\r\n                                var limite = document.getElementsByName(\"limitefin\")[0];\r\n                                var validade = document.getElementsByName(\"validadefin\")[0];\r\n                                var caracteres = \"abcdefghijklmnopqrstuvwxyz\";\r\n                                var caracteres_senha = \"abcdefghijklmnopqrstuvwxyz\";\r\n                                var usuario_gerado = caracteres.charAt(Math.floor(Math.random() * 26));\r\n                                var senha_gerada = caracteres_senha.charAt(Math.floor(Math.random() * 26));\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    usuario_gerado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));\r\n                                }\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    senha_gerada += caracteres_senha.charAt(Math.floor(Math.random() * caracteres_senha.length));\r\n                                }\r\n                                usuario.value = usuario_gerado;\r\n                                senha.value = senha_gerada;\r\n                                limite.value = 1;\r\n                                validade.value = 30;\r\n                            }\r\n                        </script> <script src=\"../app-assets/js/scripts/forms/number-input.js\"></script>";

?>