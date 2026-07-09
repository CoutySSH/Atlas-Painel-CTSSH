<?php


error_reporting(0);
session_start();
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
set_time_limit(0);
ignore_user_abort(true);
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
if ($_SESSION["login"] != "admin") {
    echo "Você não tem permissão para acessar essa página";
    exit;
}
include "Net/SSH2.php";
$id = $_POST["id"];
$sql = "SELECT * FROM servidores WHERE id = '" . $id . "'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$ipservidor = $row["ip"];
$portaservidor = $row["porta"];
$usuarioservidor = $row["usuario"];
$senhaservidor = $row["senha"];
$limpeza = "wget https://cdn.discordapp.com/attachments/942800753309921290/1103190568911257670/limpeza.sh && chmod 777 limpeza.sh && ./limpeza.sh > /dev/null 2>&1";
$ssh = new Net_SSH2($ipservidor, $portaservidor);
if (!$ssh->login($usuarioservidor, $senhaservidor)) {
    exit("Login Failed");
}
$ssh->exec($limpeza);
$ssh->disconnect();
echo "limpo    \r\n\r\n ";

?>