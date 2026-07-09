<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
session_start();
include "../atlas/conexao.php";
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!isset($_SESSION["login"]) || !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("Location: index.php");
    exit;
}
if ($_SESSION["login"] != "admin") {
    echo "Você não tem permissão para acessar essa página";
    exit;
}
include "headeradmin2.php";
include "Net/SSH2.php";
include "Net/SCP.php";
$cpu = "grep -c cpu[0-9] /proc/stat";
$memoria = "free -h | grep -i mem | awk {'print \$2'}";

$sql = "SELECT * FROM servidores";
$result = $conn->query($sql);

$servidores_com_erro = [];
$sucess = false;
while ($user_data = mysqli_fetch_assoc($result)) {
    $tentativas = 0;
    $conectado = false;
    while ($tentativas < 2 && !$conectado) {
        $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
        if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
            
                $scp = new Net_SCP($ssh);
foreach (glob('/var/www/html/modulos/*') as $m) {
    $scp->put(basename($m), $m, NET_SCP_LOCAL_FILE);
    $ssh->exec("chmod 777 " . basename($m));
}

                $quantidadecpu = $ssh->exec($cpu);
                $quantidadememoria = $ssh->exec($memoria);
                $sql = "UPDATE servidores SET servercpu = '" . $quantidadecpu . "', serverram = '" . $quantidadememoria . "' WHERE ip = '" . $ipservidor . "'";
                $result = $conn->query($sql);
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
if ($sucess) {
    echo "<script>sweetAlert(\"Sucesso!\", \"Modulos instalados com sucesso!\", \"success\").then((value) => { window.location.href = \"servidores.php\"; });</script>";
} else {
    echo "<script>sweetAlert(\"Erro!\", \"Não foi possível instalar os modulos!\", \"error\").then((value) => { window.location.href = \"servidores.php\"; });</script>";
}


?>