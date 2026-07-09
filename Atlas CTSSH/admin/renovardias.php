<?php


if (!isset($_SESSION)) {
    error_reporting(0);
    session_start();
}
include "../vendor/event/autoload.php";
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:index.php");
}
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
    $login = $row["login"];
    $senha = $row["senha"];
    $validade = $row["expira"];
    $limite = $row["limite"];
    $_SESSION["categoriarenov"] = $row["categoriaid"];
    if ($validade < date("Y-m-d H:i:s")) {
        $validade = date("Y-m-d H:i:s");
    }
}
$novadata = date("Y-m-d H:i:s", strtotime("+31 days", strtotime($validade)));
$sql = "UPDATE ssh_accounts SET expira = '" . $novadata . "', mainid = '' WHERE id = '" . $id . "'";
$result = mysqli_query($conn, $sql);
date_default_timezone_set("America/Sao_Paulo");
$novadata = date("Y-m-d H:i:s", strtotime($novadata));
$data = date("Y-m-d H:i:s");
$diferenca = strtotime($novadata) - strtotime($data);
$dias = floor($diferenca / 86400);
$sql2 = "SELECT * FROM servidores WHERE subid = '" . $_SESSION["categoriarenov"] . "'";
$result = $conn->query($sql2);
$servidores = [];
date_default_timezone_set("America/Sao_Paulo");
$datahoje = date("d-m-Y H:i:s");
$sql10 = "INSERT INTO logs (revenda, validade, texto, userid) VALUES ('" . $_SESSION["login"] . "', '" . $datahoje . "', 'Renovou 30 dias para o usuario " . $login . "', '" . $_SESSION["iduser"] . "')";
$result10 = mysqli_query($conn, $sql10);
set_time_limit(0);
ignore_user_abort(true);
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "Net/SSH2.php";
$loop = React\EventLoop\Factory::create();
$servidores_com_erro = [];
$sucess = false;
$sucess_servers = [];
while ($user_data = mysqli_fetch_assoc($result)) {
    $tentativas = 0;
    $conectado = false;
    while ($tentativas < 2 && !$conectado) {
        $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
        if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
            $loop->addTimer(0, function () use($ssh) {
                $ssh->exec("rm -rf /etc/SSHPlus/userteste/" . $login . ".sh > /dev/null 2>&1 &");
                $ssh->exec("./atlasdata.sh " . $login . " " . $dias . " > /dev/null 2>&1 &");
                $ssh->exec("./atlascreate.sh " . $login . " " . $senha . " " . $dias . " " . $limite . " ");
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
    $sucess = false;
    while ($tentativas < 2 && !$conectado) {
        $ssh = new Net_SSH2($user_data2["ip"], $user_data2["porta"]);
        if ($ssh->login($user_data2["usuario"], $user_data2["senha"])) {
            $loop->addTimer(0, function () use($ssh) {
                $ssh->exec("rm -rf /etc/SSHPlus/userteste/" . $login . ".sh > /dev/null 2>&1 &");
                $ssh->exec("./atlasdata.sh " . $login . " " . $dias . " > /dev/null 2>&1 &");
                $ssh->exec("./atlascreate.sh " . $login . " " . $senha . " " . $dias . " " . $limite . " ");
                $ssh->disconnect();
            });
            $conectado = true;
            $sucess_servers[] = $user_data2["nome"];
            $sucess = true;
        } else {
            $tentativas++;
        }
    }
    if (!$conectado) {
        $failed_servers[] = $user_data2["nome"];
    }
}
$resposta = [];
if ($sucess) {
    $response = ["sucesso" => true, "mensagem" => "Dias renovados com sucesso, Relatorio dos Servidores. Servidores Renovados: " . implode(", ", $sucess_servers) . "."];
    if (!empty($failed_servers)) {
        " Servidores com erro: " . implode(", ", $failed_servers) . ".";
        $response %= "mensagem";
    }
    echo json_encode($response);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao renovar dias!"]);
}
$loop->run();
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