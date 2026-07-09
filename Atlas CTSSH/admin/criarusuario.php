<?php
session_start();
error_reporting(0);
include "../atlas/conexao.php";
include "../vendor/event/autoload.php";
set_time_limit(0);
ignore_user_abort(true);
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "Net/SSH2.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION["mensagem_enviada"])) {
    unset($_SESSION["mensagem_enviada"]);
}

$sql3 = "SELECT * FROM categorias";
$result3 = $conn->query($sql3);

date_default_timezone_set("America/Sao_Paulo");
$datahoje = date("d-m-Y H:i:s");
unset($_SESSION["whatsapp"]);
echo "     ";

$sql2 = "SELECT * FROM servidores";
$result = $conn->query($sql2);

include "headeradmin2.php";

echo "    \r\n\r\n    \r\n\r\n      ";
set_time_limit(0);
ignore_user_abort(true);

if (isset($_POST["criaruser"])) {
    $validadefin = $_POST["validadefin"];
    $limitefin = $_POST["limitefin"];
    $usuariofin = $_POST["usuariofin"];
    $senhafin = $_POST["senhafin"];
    $categoria = $_POST["categoria"];
    $notas = $_POST["notas"];
    $valormensal = $_POST["valormensal"];
    $_POST["whatsapp"] = str_replace(" ", "", $_POST["whatsapp"]);
    $_POST["whatsapp"] = str_replace("-", "", $_POST["whatsapp"]);
    $_SESSION["whatsapp"] = $_POST["whatsapp"];
    $_SESSION["usuariofin"] = $usuariofin;
    $_SESSION["senhafin"] = $senhafin;
    $_SESSION["validadefin"] = $validadefin;
    $_SESSION["limitefin"] = $limitefin;

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
        $conectado = false;
        $ipeporta = $user_data["ip"] . ":6969";
        $timeout = 3;
        $socket = @fsockopen($user_data["ip"], 6969, $errno, $errstr, $timeout);

        if ($socket) {
            fclose($socket);
            $loop->addTimer(0, function () use($user_data, $usuariofin, $senhafin, $validadefin, $limitefin) {
                // Exemplo de escape usando escapeshellarg
                $comando = "sudo ./atlascreate.sh " . escapeshellarg($usuariofin) . " " . escapeshellarg($senhafin) . " " . escapeshellarg($validadefin) . " " . escapeshellarg($limitefin);

                $senha = $_SESSION["token"];
                $senha = md5($senha);
                $headers = ["Senha: " . $senha];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $user_data["ip"] . ":6969");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "comando=" . $comando);
                $output = curl_exec($ch);
                curl_close($ch);
            });

            $conectado = true;
            $sucess_servers[] = $user_data["nome"];
            $sucess = true;
        }

        if (!$conectado) {
            $servidores_com_erro[] = $user_data["ip"];
            $failed_servers[] = $user_data["nome"];
        }
    }

    if (!$sucess) {
        echo "<script>alert('Erro ao criar usuário!');window.location.href='criarusuario.php';</script>";
        exit;
    }

    if ($sucess) {
        $data = date("Y-m-d H:i:s");
        $data = strtotime($data);
        $data = strtotime("+" . $validadefin . " days", $data);
        $data = date("Y-m-d H:i:s", $data);
        $validadefin = $data;
        $sql9 = "INSERT INTO ssh_accounts (login, senha, expira, limite, byid, categoriaid, lastview ,bycredit, mainid, status, whatsapp, valormensal) VALUES ('" . $usuariofin . "', '" . $senhafin . "', '" . $validadefin . "', '" . $limitefin . "', '" . $_SESSION["iduser"] . "', '" . $categoria . "', '" . $notas . "', '0', 'NULL', 'Offline', '" . $_SESSION["whatsapp"] . "', '" . $valormensal . "')";
        $result9 = mysqli_query($conn, $sql9);
        $_SESSION["validadefin"] = $validadefin;
        $loop->run();
        $sucess_servers_str = implode(", ", $sucess_servers);
        $failed_servers_str = implode(", ", $failed_servers);
        echo "<script>window.location.href = 'criado.php?sucess=" . $sucess_servers_str . "&failed=" . $failed_servers_str . "';</script>";
        ob_flush();
        flush();
        exit;
    }
}

echo " \r\n    <!-- BEGIN: Content-->\r\n    <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui você pode criar um usuário para seus clientes.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\">\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Criar Usuário</h4>\r\n                                </div>\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <form class=\"form form-horizontal\" action=\"criarusuario.php\" method=\"POST\">\r\n                                            <div class=\"form-body\">\r\n                                            <button type=\"button\" class=\"btn btn-primary mr-1 mb-1\" onclick=\"gerar()\">Gerar Aleatorio</button>\r\n                                                <div class=\"row\">\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Categoria</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <select class=\"form-control\" name=\"categoria\">\r\n                                                            ";
$sql = "SELECT * FROM categorias";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $categorias[] = $row;
}
foreach ($categorias as $categoria) {
    echo "<option value='" . $categoria["subid"] . "'>" . $categoria["nome"] . "</option>";
}
echo "                                                        </select>\r\n                                                    </div>\r\n\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Login</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"usuariofin\" placeholder=\"Login\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Senha</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"senhafin\" placeholder=\"Senha\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Limite</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" value=\"1\" min=\"1\" name=\"limitefin\" />\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Dias</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" value=\"30\" min=\"1\" name=\"validadefin\" />\r\n                                                    </div>\r\n                                            \r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Notas</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"notas\" placeholder=\"Notas\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Valor Mensal Personalizado</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"valormensal\" placeholder=\"Ex: 10\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Numero Whatsapp (NUMERO IGUAL AO WHATSAPP)</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"whatsapp\" placeholder=\"+5511999999999\">\r\n                                                    </div>\r\n                                                    <div class=\"col-12 col-md-8 offset-md-4 form-group\">\r\n                                                        <fieldset>\r\n                                                            \r\n                                                        </fieldset>\r\n                                                    </div>\r\n                                                    <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                        \r\n                                                        <button type=\"submit\" class=\"btn btn-primary mr-1 mb-1\" name=\"criaruser\">Criar</button>\r\n                                                        <button type=\"reset\" class=\"btn btn-light-secondary mr-1 mb-1\">Cancelar</button>\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </form>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        <script>\r\n                            function gerar() {\r\n                                var usuario = document.getElementsByName(\"usuariofin\")[0];\r\n                                var senha = document.getElementsByName(\"senhafin\")[0];\r\n                                var limite = document.getElementsByName(\"limitefin\")[0];\r\n                                var validade = document.getElementsByName(\"validadefin\")[0];\r\n                                var caracteres = \"abcdefghijklmnopqrstuvwxyz\";\r\n                                var caracteres_senha = \"abcdefghijklmnopqrstuvwxyz\";\r\n                                var usuario_gerado = caracteres.charAt(Math.floor(Math.random() * 26));\r\n                                var senha_gerada = caracteres_senha.charAt(Math.floor(Math.random() * 26));\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    usuario_gerado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));\r\n                                }\r\n                                for (var i = 0; i < 10; i++) {\r\n                                    senha_gerada += caracteres_senha.charAt(Math.floor(Math.random() * caracteres_senha.length));\r\n                                }\r\n                                usuario.value = usuario_gerado;\r\n                                senha.value = senha_gerada;\r\n                                limite.value = 1;\r\n                                validade.value = 30;\r\n                            }\r\n                        </script> <script src=\"../app-assets/js/scripts/forms/number-input.js\"></script>";
?>
