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
if ($_SESSION["login"] == "admin") {
    if (!empty($_POST["id"])) {
        $id = $_POST["id"];
        $comando = $_POST["comando"];
    }
    set_time_limit(0);
    ignore_user_abort(true);
    set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
    include "Net/SSH2.php";
    $sql2 = "SELECT * FROM servidores WHERE id = '" . $id . "'";
    $result = $conn->query($sql2);
    $row = mysqli_fetch_assoc($result);
    $login = $row["usuario"];
    $senha = $row["senha"];
    $porta = $row["porta"];
    $ip = $row["ip"];
    try {
        $ssh = new Net_SSH2($ip, $porta);
        if (!$ssh->login($login, $senha)) {
            echo "Não foi possível autenticar";
        } else {
            $ssh->exec($comando . " > /dev/null 2>&1 &");
            $ssh->disconnect();
            echo "Comando enviado com sucesso";
        }
    } catch (Exception $ex) {
        echo "Não foi possível conectar ao servidor";
    }
} else {
    echo "<script>alert('Você não tem permissão para acessar essa página!');window.location.href='../logout.php';</script>";
    exit;
}

?>