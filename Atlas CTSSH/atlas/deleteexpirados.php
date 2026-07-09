<?php


echo "\r\n";
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
include "header2.php";
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["iduser"] . "'";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $categoria = $row["categoriaid"];
    }
}
set_time_limit(0);
ignore_user_abort(true);
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "Net/SSH2.php";
$sql1 = "SELECT * FROM accounts WHERE id = '" . $_SESSION["iduser"] . "'";
$result1 = $conn->query($sql1);
if (0 < $result1->num_rows) {
    while ($row1 = $result1->fetch_assoc()) {
        $contas[] = $row1;
    }
}
$contas = array_unique($contas, SORT_REGULAR);
date_default_timezone_set("America/Sao_Paulo");
$data = date("Y-m-d H:i:s");
foreach ($contas as $conta) {
    $sql3 = "SELECT * FROM ssh_accounts WHERE byid = '" . $conta["id"] . "' and expira < '" . $data . "'";
    $result3 = $conn->query($sql3);
    if (0 < $result3->num_rows) {
        while ($row3 = $result3->fetch_assoc()) {
            $ssh_accounts[] = $row3;
        }
    }
}
if (empty($ssh_accounts)) {
    echo "<script>swal('Nenhuma conta expirada!').then(function() {\r\n            window.location.href = '../home.php';\r\n        });</script>";
    exit;
}
$nome = md5(uniqid(rand(), true));
$nome = substr($nome, 0, 10);
$nome = $nome . ".txt";
$file = fopen((int) $nome, "w");
foreach ($ssh_accounts as $ssh_account) {
    $login = $ssh_account["login"];
    fwrite($file, $login . PHP_EOL);
}
$sql2 = "SELECT * FROM servidores WHERE subid = '" . $ssh_account["categoriaid"] . "'";
$result = $conn->query($sql2);

$servidores_com_erro = [];
$sucess = false;
while ($user_data = mysqli_fetch_assoc($result)) {
    $tentativas = 0;
    $conectado = false;
    while ($tentativas < 2 && !$conectado) {
        $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
        if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
            
                $local_file = $nome;
                $limiter_content = file_get_contents($local_file);
                $ssh->exec("echo \"" . $limiter_content . "\" > /root/" . $nome);
                $ssh->exec("python3 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
                $ssh->exec("python2 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
                $ssh->disconnect();
            $conectado = true;
            $sucess = true;
        } else {
            $tentativas++;
        }
    }
    if (!$conectado) {
        $servidores_com_erro[] = $user_data["ip"];
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
            
                $local_file = $nome;
                $limiter_content = file_get_contents($local_file);
                $ssh->exec("echo \"" . $limiter_content . "\" > /root/" . $nome);
                $ssh->exec("python3 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
                $ssh->exec("python2 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
                $ssh->disconnect();
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
    echo "<script>swal('Contas deletadas com sucesso!').then(function() {\r\n                window.location.href = '../home.php';\r\n            });</script>";
    foreach ($ssh_accounts as $ssh_account) {
        $sql4 = "DELETE FROM ssh_accounts WHERE id = '" . $ssh_account["id"] . "'";
        $result4 = $conn->query($sql4);
    }
} else {
    echo "<script>swal('Erro ao deletar contas!').then(function() {\r\n                window.location.href = '../home.php';\r\n            });</script>";
}

unlink($nome);
echo "\r\n";

?>