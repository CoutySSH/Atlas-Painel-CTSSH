<?php
include_once '../atlas/conexao.php';
include('../vendor/event/autoload.php');
use React\EventLoop\Factory;
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
date_default_timezone_set('America/Sao_Paulo');
$contasdell = [];

$dataagora = date('Y-m-d H:i:s');
$ontem = date('Y-m-d', strtotime('-1 day'));

$sql = "SELECT * FROM ssh_accounts WHERE expira >= '$ontem' AND expira < '$dataagora' AND mainid != 'Suspenso' LIMIT 3";
$result = $conn->query($sql);

//deletar todas contas ssh com usuario em branco
$sql4 = "DELETE FROM ssh_accounts WHERE login = ''";
$result4 = $conn->query($sql4);

//deletar todas contas ssh com senha em branco
$sql5 = "DELETE FROM ssh_accounts WHERE senha = ''";
$result5 = $conn->query($sql5);




if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contasdell[] = $row;
    }
}
set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
            include ('Net/SSH2.php');
            $loop = Factory::create();
            $servidores_com_erro = [];
            $sucess = false;     
$categoriaids = array_column($contasdell, 'categoriaid');
$categoriaids_str = implode(',', $categoriaids);
if (empty($categoriaids_str)) {
    //echo 'Nenhuma conta para suspender';
}else{
$sql2 = "SELECT * FROM servidores WHERE subid IN ($categoriaids_str)";
$result2 = $conn->query($sql2);

    if ($result2->num_rows > 0) {
        $senhatoken = $_SESSION['token'];
          $senhatoken = md5($senhatoken);
        while ($user_data = mysqli_fetch_assoc($result2)) {
            $ipeporta = $user_data['ip'] . ':6969';
                $timeout = 3;
                $socket = @fsockopen($user_data['ip'], 6969, $errno, $errstr, $timeout);
                if ($socket) {
                  fclose($socket);
                $loop->addTimer(0.001, function () use ($user_data, $conn, $senhatoken, $contasdell) {
                    foreach ($contasdell as $contadell) {
                        $login = $contadell['login'];
                        $sql3 = "UPDATE ssh_accounts SET mainid = 'Suspenso' WHERE login = '$login'";
                        $result3 = $conn->query($sql3);
                        $uuid = $contadell['uuid'];
                        if ($uuid != '') {
                            $comando = 'sudo ./rem.sh ' . $uuid .' '. $login;
                        } else {
                            $comando = 'sudo ./atlasremove.sh ' . $login;
                        }
                  
                  $headers = array(
                    'Senha: ' . $senhatoken
                  );
                  $ch = curl_init();
                               curl_setopt($ch, CURLOPT_URL, $user_data['ip'] . ':6969');
                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                               curl_setopt($ch, CURLOPT_POST, 1);
                               curl_setopt($ch, CURLOPT_POSTFIELDS, "comando=$comando");
                               $output = curl_exec($ch);
                                 curl_close($ch);
                }
                });
                
                $sucess = true;
            } else {
                $servidores_com_erro[] = $user_data['ip'];
            }
    }
    }
    $loop->run();
}
?>
