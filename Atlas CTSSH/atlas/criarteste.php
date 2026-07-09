<?php


echo "\r\n";
error_reporting(0);
session_start();
set_time_limit(0);
ignore_user_abort(true);
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "Net/SSH2.php";
include "conexao.php";
include "../vendor/event/autoload.php";
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
$_SESSION["tipodeconta"] = $row["tipo"];
$_SESSION["limite"] = $row["limite"];
$tipo = $_SESSION["tipodeconta"];
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
$sql5 = "SELECT * FROM accounts WHERE id = '1'";
$sql5 = $conn->query($sql5);
$row = $sql5->fetch_assoc();
$testelimite = $row["mb"];
$sql2 = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
$result = $conn->query($sql2);
while ($row = $result->fetch_assoc()) {
    $servidores[] = $row;
}
echo "                \r\n            \r\n              ";
include "header2.php";
if (isset($_POST["criaruser"])) {
    ignore_user_abort(true);
    set_time_limit(0);
    $usuariofin = $_POST["usuariofin"];
    $senhafin = $_POST["senhafin"];
    $validadefin = $_POST["validadefin"];
    $notas = $_POST["notas"];
    if ($testelimite < $validadefin) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Você não pode criar um teste com mais de " . $testelimite . " Minutos!');window.location.href='criarteste.php';</script>";
        exit;
    }
    if ($usuariofin == "") {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário não pode ser vazio!');window.location.href='criarteste.php';</script>";
        exit;
    }
    if ($senhafin == "") {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Senha não pode ser vazia!');window.location.href='criarteste.php';</script>";
        exit;
    }
    if (preg_match("/[^a-z0-9]/i", $usuariofin)) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário não pode conter caracteres especiais!');window.location.href='criarteste.php';</script>";
        exit;
    }
    if (preg_match("/[^a-z0-9]/i", $senhafin)) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Senha não pode conter caracteres especiais!');window.location.href='criarteste.php';</script>";
        exit;
    }
    if ($_SESSION["limite"] < $_POST["limitefin"]) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Você não tem limite suficiente!');window.location.href='criarteste.php';</script>";
        exit;
    }
    $limitefin = $_POST["limitefin"];
    if ($_SESSION["tipodeconta"] == "Credito" && $_SESSION["limite"] < $limitefin) {
        echo "<script>alert('Limite Atingido')</script><script>window.location.href = '../home.php'</script>";
        unset($_POST["criaruser"]);
        unset($_POST["usuariofin"]);
        unset($_POST["senhafin"]);
        unset($_POST["validadefin"]);
        exit;
    }
    $_SESSION["usuariofin"] = $usuariofin;
    $_SESSION["senhafin"] = $senhafin;
    $_SESSION["validadefin"] = $validadefin;
    $_SESSION["limitefin"] = $limitefin;
    if ($_SESSION["tipodeconta"] != "Credito") {
        if ($_SESSION["restante"] < $_POST["limitefin"]) {
            echo "<script language='javascript' type='text/javascript'>alert('Ops.. Você não tem limite suficiente!');window.location.href='criarteste.php';</script>";
            exit;
        }
    }
    $sql = "SELECT * FROM ssh_accounts WHERE login = '" . $usuariofin . "'";
    $result = $conn->query($sql);
    if (0 < $result->num_rows) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário já existe!');window.location.href='criarteste.php';</script>";
        exit;
    }
    $sql4 = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
    $result4 = $conn->query($sql4);
    $loop = React\EventLoop\Factory::create();
    $servidores_com_erro = [];
    define("SCRIPT_PATH", "./atlasteste.sh");
    $sucess_servers = [];
    $failed_servers = [];
    $sucess = false;
    while ($user_data = mysqli_fetch_assoc($result4)) {
        $tentativas = 0;
        $conectado = false;
        while ($tentativas < 2 && !$conectado) {
            $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
            if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
                $loop->addTimer(0, function () use($ssh) {
                    $ssh->exec(SCRIPT_PATH . " " . $usuariofin . " " . $senhafin . " " . $validadefin . " " . $limitefin . " > /dev/null 2>&1 &");
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
        echo "<script>window.location.href = 'testecriado.php?sucess=" . $sucess_servers_str . "&failed=" . $failed_servers_str . "';</script>";
        $datahoje = date("d-m-Y H:i:s");
        $sql10 = "INSERT INTO logs (revenda, byid, validade, texto, userid) VALUES ('" . $_SESSION["login"] . "', '" . $_SESSION["byid"] . "', '" . $datahoje . "', 'Criou um Teste " . $usuariofin . " de " . $validadefin . " Minutos ', '" . $_SESSION["iduser"] . "')";
        $result10 = mysqli_query($conn, $sql10);
        $data = date("Y-m-d H:i:s");
        $data = strtotime($data);
        $data = strtotime("+" . $validadefin . " minutes", $data);
        $data = date("Y-m-d H:i:s", $data);
        $validadefin = $data;
        $sql9 = "INSERT INTO ssh_accounts (login, senha, expira, limite, byid, categoriaid, status, bycredit, mainid, lastview) VALUES ('" . $usuariofin . "', '" . $senhafin . "', '" . $validadefin . "', '" . $limitefin . "', '" . $_SESSION["iduser"] . "', '" . $categoria . "', 'Offline', '0', '0', '" . $notas . "')";
        $result9 = mysqli_query($conn, $sql9);
        $loop->run();
    } else {
        echo "<script>alert('Erro ao criar teste, tente novamente')</script><script>window.location.href = 'criarteste.php';</script>";
        exit;
    }
}
echo "\r\n<div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui você pode criar um teste para seu cliente.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\">\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Criar Teste</h4>\r\n                                </div>\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <form class=\"form form-horizontal\" action=\"criarteste.php\" method=\"POST\">\r\n                                            <div class=\"form-body\">\r\n                                                <button type=\"button\" class=\"btn btn-primary mr-1 mb-1\" onclick=\"gerar()\">Gerar Aleatorio</button>\r\n                                                <p class=\"text-primary\">";
echo $tipo;
echo "</p>\r\n                                                <div class=\"row\">\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Login</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"usuariofin\" placeholder=\"Login\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Senha</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"senhafin\" placeholder=\"Senha\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Limite</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" value=\"1\" min=\"1\" name=\"limitefin\" />\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Minutos</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"number\" class=\"form-control\" value=\"60\" min=\"1\" max=\"";
echo $testelimite;
echo "\" name=\"validadefin\" />\r\n                                                    </div>\r\n                                            \r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Notas</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"notas\" placeholder=\"Notas\">\r\n                                                    </div>\r\n                                                    <div class=\"col-12 col-md-8 offset-md-4 form-group\">\r\n                                                        <fieldset>\r\n                                                            \r\n                                                        </fieldset>\r\n                                                    </div>\r\n                                                    <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                        \r\n                                                        <button type=\"submit\" class=\"btn btn-primary mr-1 mb-1\" name=\"criaruser\">Criar</button>\r\n                                                        <button type=\"reset\" class=\"btn btn-light-secondary mr-1 mb-1\">Cancelar</button>\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </form>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        <script>\r\n                            function gerar() {\r\n                                var usuario = document.getElementsByName(\"usuariofin\")[0];\r\n                                var senha = document.getElementsByName(\"senhafin\")[0];\r\n                                var limite = document.getElementsByName(\"limitefin\")[0];\r\n                                var validade = document.getElementsByName(\"validadefin\")[0];\r\n                                var caracteres = \"0123456789\";\r\n                                var caracteres_senha = \"abcdefghijklmnopqrstuvwxyz\";\r\n                                var usuario_gerado = caracteres.charAt(Math.floor(Math.random() * 26));\r\n                                var senha_gerada = caracteres_senha.charAt(Math.floor(Math.random() * 26));\r\n                                for (var i = 0; i < 3; i++) {\r\n                                    usuario_gerado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));\r\n                                }\r\n                                for (var i = 0; i < 3; i++) {\r\n                                    senha_gerada += caracteres_senha.charAt(Math.floor(Math.random() * caracteres_senha.length));\r\n                                }\r\n                                usuario.value = usuario_gerado + senha_gerada;\r\n                                senha.value = usuario_gerado + senha_gerada;\r\n                                limite.value = 1;\r\n                                validade.value = 60;\r\n                            }\r\n                        </script> <script src=\"../app-assets/js/scripts/forms/number-input.js\"></script>           ";

?>