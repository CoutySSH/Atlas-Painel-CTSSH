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
include "../vendor/event/autoload.php";
if ($_SESSION["login"] != "admin") {
    echo "Você não tem permissão para acessar essa página";
    exit;
}
include "headeradmin2.php";
include "Net/SSH2.php";
$cpu = "grep -c cpu[0-9] /proc/stat";
$memoria = "free -h | grep -i mem | awk {'print \$2'}";
$modulo = "rm atlasdata.sh || true && rm atlascreate.sh || true && rm atlasteste.sh || true && rm atlasremove.sh || true && rm delete.py || true && rm sincronizar.py || true &&\r\nwgethttps://cdn.discordapp.com/attachments/469054518823223296/1211606992242614272/atlascreate.sh?ex=65eecff1&is=65dc5af1&hm=0a88fb365e9d3f227c468df7670fcaf0cfe8120d64c4b67d7e3cac33e5fe620f& && chmod 777 atlascreate.sh && wget https://cdn.discordapp.com/attachments/469054518823223296/1211607519839911936/atlasteste.sh?ex=65eed06f&is=65dc5b6f&hm=9eca2c04fff5812e64134d26b18039155e80b4c84bd960a4fdf79cf6022f32fa& && chmod 777 atlasteste.sh && wget https://cdn.discordapp.com/attachments/469054518823223296/1211607519408037969/atlasremove.sh?ex=65eed06f&is=65dc5b6f&hm=7e0da9fa5d8a90719e36dff85fca24ad0906e4eebd6758f40ccbcb18ec47ee99& && chmod 777 atlasremove.sh && wget https://cdn.discordapp.com/attachments/469054518823223296/1211607520188043284/delete.py?ex=65eed06f&is=65dc5b6f&hm=f12d21efaf0d5a1a53ed1c3a4e5a5ca37a1e863737494a3613096aa118075954& && wget https://cdn.discordapp.com/attachments/469054518823223296/1211607518883880970/atlasdata.sh?ex=65eed06f&is=65dc5b6f&hm=f394e72a7eff815f599e88f332b25e481fcc496de61ef2d951bc3f1fc7ad340c& && chmod 777 atlasdata.sh && chmod 777 delete.py && wget https://cdn.discordapp.com/attachments/469054518823223296/1211607522033803346/sincronizar.py?ex=65eed06f&is=65dc5b6f&hm=9569e023d58616bf441709801235774d91bbb69a1feeaa4683e5f616bf07419f& && chmod 777 sincronizar.py > /dev/null 2>&1";
$sql = "SELECT * FROM servidores";
$result = $conn->query($sql);
$loop = React\EventLoop\Factory::create();
$servidores_com_erro = [];
$sucess = false;
while ($user_data = mysqli_fetch_assoc($result)) {
    $tentativas = 0;
    $conectado = false;
    while ($tentativas < 2 && !$conectado) {
        $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
        if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
            $loop->addTimer(0, function () use($ssh) {
                $ssh->exec($modulo);
                $quantidadecpu = $ssh->exec($cpu);
                $quantidadememoria = $ssh->exec($memoria);
                $sql = "UPDATE servidores SET servercpu = '" . $quantidadecpu . "', serverram = '" . $quantidadememoria . "' WHERE ip = '" . $ipservidor . "'";
                $result = $conn->query($sql);
                $ssh->disconnect();
            });
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
$loop->run();

?>