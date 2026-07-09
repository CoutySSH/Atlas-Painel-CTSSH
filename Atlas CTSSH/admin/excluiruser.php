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
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$_GET["id"] = anti_sql($_GET["id"]);
$id = $_GET["id"];
$sql = "SELECT * FROM ssh_accounts WHERE id = '" . $id . "'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$login = $row["login"];
$categoria = $row["categoriaid"];
$sql2 = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
$result = $conn->query($sql2);
date_default_timezone_set("America/Sao_Paulo");
$datahoje = date("d-m-Y H:i:s");
$sql10 = "INSERT INTO logs (revenda, validade, texto, userid) VALUES ('" . $_SESSION["login"] . "', '" . $datahoje . "', 'Excluiu o usuario " . $login . "', '" . $_SESSION["iduser"] . "')";
$result10 = mysqli_query($conn, $sql10);
set_time_limit(0);
ignore_user_abort(true);
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "Net/SSH2.php";

$servidores_com_erro = [];
$sucess = false;
while ($user_data = mysqli_fetch_assoc($result)) {
    $tentativas = 0;
    $conectado = false;
    error_log("Tentativa de conexão SSH para " . $user_data["ip"]);
    while ($tentativas < 2 && !$conectado) {
        $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
        if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
            error_log("Conexão SSH bem-sucedida para " . $user_data["ip"]);
            
                error_log("Executando comando SSH");
                $ssh->exec("./atlasremove.sh " . $login . " ");
                $ssh->disconnect();
            $conectado = true;
            $sucess = true;
        } else {
            $tentativas++;
            error_log("Falha na tentativa " . $tentativas . " de conexão SSH para " . $user_data["ip"]);
        }
    }
    if (!$conectado) {
        $servidores_com_erro[] = $user_data["ip"];
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
                error_log("Conexão SSH bem-sucedida para " . $user_data2["ip"]);
                
                    error_log("Executando comando SSH");
                    $ssh->exec("./atlasremove.sh " . $login . " ");
                    $ssh->disconnect();
                $conectado = true;
                $sucess = true;
            } else {
                $tentativas++;
                error_log("Falha na tentativa " . $tentativas . " de conexão SSH para " . $user_data2["ip"]);
            }
        }
        if (!$conectado) {
            $failed_servers[] = $user_data2["nome"];
        }
    }
}
if ($sucess = true) {
    echo "excluido";
    $sql3 = "DELETE FROM ssh_accounts WHERE id = '" . $id . "'";
    $result = mysqli_query($conn, $sql3);
}

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
