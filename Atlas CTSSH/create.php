<?php


error_reporting(0);
ignore_user_abort(true);
set_time_limit(0);
include "atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
set_include_path(get_include_path() . PATH_SEPARATOR . "lib2");
include "Net/SSH2.php";
$categoria = $_POST["categoria"];
$usuario = $_POST["usuario"];
$senha = $_POST["senha"];
$limite = $_POST["limite"];
$validade = $_POST["validade"];
$tipo = $_POST["tipo"];
$sql = "SELECT id, ip, porta, usuario, senha FROM servidores WHERE subid = '" . $categoria . "'";
$result = mysqli_query($conn, $sql);

while ($user_data = mysqli_fetch_assoc($result)) {
    $tentativas = 0;
    $conectado = false;
    while ($tentativas < 3 && !$conectado) {
        $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
        if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
            
                if ($tipo == "teste") {
                    $ssh->exec("clear");
                    $ssh->exec("./atlasteste.sh " . $usuario . " " . $senha . " " . $validade . " " . $limite . " > /dev/null 2>&1 &");
                    $ssh->exec("./atlasteste.sh " . $usuario . " " . $senha . " " . $validade . " " . $limite . " ");
                } else {
                    $ssh->exec("clear");
                    $ssh->exec("./atlascreate.sh " . $usuario . " " . $senha . " " . $validade . " " . $limite . " > /dev/null 2>&1 &");
                    $ssh->exec("./atlascreate.sh " . $usuario . " " . $senha . " " . $validade . " " . $limite . " ");
                }
            $conectado = true;
        } else {
            $tentativas++;
        }
    }
}


?>