<?php

include_once "../atlas/conexao.php";
include "../vendor/event/autoload.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
date_default_timezone_set("America/Sao_Paulo");
$contasdell = [];
$dataagora = date("Y-m-d H:i:s");
$ontem = date("Y-m-d", strtotime("-1 day"));
$sql = "SELECT * FROM ssh_accounts WHERE expira >= '" . $ontem . "' AND expira < '" . $dataagora . "' AND mainid != 'Suspenso' LIMIT 3";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $contasdell[] = $row;
    }
}
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "Net/SSH2.php";
$loop = React\EventLoop\Factory::create();
$servidores_com_erro = [];
$sucess = false;
$categoriaids = array_column($contasdell, "categoriaid");
$categoriaids_str = implode(",", $categoriaids);
if (empty($categoriaids_str)) {
    exit("Nenhuma conta para suspender");
}
$sql2 = "SELECT * FROM servidores WHERE subid IN (" . $categoriaids_str . ")";
$result2 = $conn->query($sql2);
if (0 < $result2->num_rows) {
    while ($user_data = mysqli_fetch_assoc($result2)) {
        $tentativas = 0;
        $conectado = false;
        while ($tentativas < 2 && !$conectado) {
            $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
            if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
                $loop->addTimer(0, function () use ($ssh, $contasdell, $conn) {
                    foreach ($contasdell as $conta) {
                        $login = $conta["login"];
                        $ssh->exec("./atlasremove.sh " . $login . " ");
                        $sql3 = "UPDATE ssh_accounts SET mainid = 'Suspenso' WHERE login = '" . $login . "'";
                        $result3 = $conn->query($sql3);
                    }
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
}
$loop->run();
?>
