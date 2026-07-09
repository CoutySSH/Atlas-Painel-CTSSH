<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
session_start();
include "../atlas/conexao.php";
set_time_limit(0);
ignore_user_abort(true);
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
if ($_SESSION["login"] != "admin") {
    session_destroy();
    header("Location: index.php");
    exit;
}
include "Net/SSH2.php";
include "Net/SCP.php";
include "headeradmin2.php";

$cpu = "grep -c cpu[0-9] /proc/stat";
$memoria = "free -h | grep -i mem | awk {'print \$2'}";
if (isset($_SESSION["ipservidor"]) && isset($_SESSION["portaservidor"]) && isset($_SESSION["usuarioservidor"]) && isset($_SESSION["senhaservidor"])) {
    $ipservidor = $_SESSION["ipservidor"];
    $portaservidor = $_SESSION["portaservidor"];
    $usuarioservidor = $_SESSION["usuarioservidor"];
    $senhaservidor = $_SESSION["senhaservidor"];
    $ssh = new Net_SSH2($ipservidor, $portaservidor);
    if (!$ssh->login($usuarioservidor, $senhaservidor)) {
        echo "<script>swal(\"Erro!\", \"Falha na autenticação do servidor!\", \"error\").then(function() { window.location = \"adicionarservidor.php\"; });</script>";
        $sql = $conn->query("DELETE FROM servidores WHERE ip = '" . $ipservidor . "'");
        exit;
    }
    $scp = new Net_SCP($ssh);
foreach (glob('/var/www/html/modulos/*') as $m) {
    $scp->put(basename($m), $m, NET_SCP_LOCAL_FILE);
    $ssh->exec("chmod 777 " . basename($m));
}

    $quantidadecpu = $ssh->exec($cpu);
    $quantidadememoria = $ssh->exec($memoria);
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    $sql = "UPDATE servidores SET servercpu = '" . $quantidadecpu . "', serverram = '" . $quantidadememoria . "' WHERE ip = '" . $ipservidor . "'";
    $result = $conn->query($sql);
    $conn->close();
    $ssh->disconnect();
    unset($_SESSION["ipservidor"]);
    unset($_SESSION["portaservidor"]);
    unset($_SESSION["usuarioservidor"]);
    unset($_SESSION["senhaservidor"]);
    echo "<script>swal(\"Servidor Adicionado com Sucesso!\", \"\", \"success\").then(function() { window.location = \"servidores.php\"; });</script>";
}

?>