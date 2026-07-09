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
include "headeradmin2.php";
$modulo = "rm atlasdata.sh || true && rm atlascreate.sh || true && rm atlasteste.sh || true && rm atlasremove.sh || true && rm delete.py || true && rm sincronizar.py || true &&\r\nwget https://cdn.discordapp.com/attachments/469054518823223296/1211606992242614272/atlascreate.sh?ex=65eecff1&is=65dc5af1&hm=0a88fb365e9d3f227c468df7670fcaf0cfe8120d64c4b67d7e3cac33e5fe620f& && chmod 777 atlascreate.sh && wget https://cdn.discordapp.com/attachments/469054518823223296/1211607519839911936/atlasteste.sh?ex=65eed06f&is=65dc5b6f&hm=9eca2c04fff5812e64134d26b18039155e80b4c84bd960a4fdf79cf6022f32fa& && chmod 777 atlasteste.sh && wget https://cdn.discordapp.com/attachments/469054518823223296/1211607519408037969/atlasremove.sh?ex=65eed06f&is=65dc5b6f&hm=7e0da9fa5d8a90719e36dff85fca24ad0906e4eebd6758f40ccbcb18ec47ee99& && chmod 777 atlasremove.sh && wget https://cdn.discordapp.com/attachments/469054518823223296/1211607520188043284/delete.py?ex=65eed06f&is=65dc5b6f&hm=f12d21efaf0d5a1a53ed1c3a4e5a5ca37a1e863737494a3613096aa118075954& && wget https://cdn.discordapp.com/attachments/469054518823223296/1211607518883880970/atlasdata.sh?ex=65eed06f&is=65dc5b6f&hm=f394e72a7eff815f599e88f332b25e481fcc496de61ef2d951bc3f1fc7ad340c& && chmod 777 atlasdata.sh && chmod 777 delete.py && wget https://cdn.discordapp.com/attachments/469054518823223296/1211607522033803346/sincronizar.py?ex=65eed06f&is=65dc5b6f&hm=9569e023d58616bf441709801235774d91bbb69a1feeaa4683e5f616bf07419f& && chmod 777 sincronizar.py && chmod 777 sincronizar.py > /dev/null 2>&1";
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
    $ssh->exec($modulo);
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