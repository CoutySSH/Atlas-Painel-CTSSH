<?php


session_start();
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:../index.php");
}
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
require_once "Net/SSH2.php";
define("SSH_PORT", 22);
define("SCRIPT_PATH", "ps -x | grep sshd | grep -v root | grep priv | wc -l");
define("SCRIPT_PATH2", "expcleaner > /dev/null 2>&1 &");
$sql = "SELECT * FROM servidores";
$result = mysqli_query($conn, $sql);
foreach ($result as $row) {
    $ip = $row["ip"];
    $porta = $row["porta"];
    $usuario = $row["usuario"];
    $senha = $row["senha"];
    $ssh = trysshconnection($ip, $porta, $usuario, $senha);
    if ($ssh) {
        $output = $ssh->exec(SCRIPT_PATH);
        $ssh->exec(SCRIPT_PATH2);
        $online = intval(trim($output));
        $sql_update = "UPDATE servidores SET onlines = " . $online . " WHERE id = " . $row["id"];
        mysqli_query($conn, $sql_update);
    }
}
mysqli_close($conn);
function trySshConnection($ip, $porta, $usuario, $senha)
{
    $ssh = new Net_SSH2($ip, $porta);
    if ($ssh->login($usuario, $senha)) {
        return $ssh;
    }
    return false;
}

?>