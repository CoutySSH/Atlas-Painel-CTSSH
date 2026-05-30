<?php 
if (!isset($_SESSION)){
    error_reporting(0);
session_start();

}
include('../vendor/event/autoload.php');
use React\EventLoop\Factory;
set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
include ('Net/SSH2.php');
//se a sessão não existir, redireciona para o login
if(!isset($_SESSION['login']) and !isset($_SESSION['senha'])){
    session_destroy();
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('location:index.php');
}
include('conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}
if (!file_exists('../admin/suspenderrev.php')) {
    exit ("<script>alert('Token Invalido!');</script>");
  }else{
    include_once '../admin/suspenderrev.php';
    
  }
  if (!isset($_SESSION['sgdfsr43erfggfd4rgs3rsdfsdfsadfe']) || !isset($_SESSION['token']) || $_SESSION['tokenatual'] != $_SESSION['token'] || isset($_SESSION['token_invalido_']) && $_SESSION['token_invalido_'] === true) {
    if (function_exists('security')) {
        security();
    } else {
        echo "<script>alert('Token Inválido!');</script>";
        echo "<script>location.href='../index.php';</script>";

        $_SESSION['token_invalido_'] = true;
        exit;
    }
  }
function anti_sql($input)
{
    $seg = preg_replace_callback("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i", function($match) {
        return '';
    }, $input);
    $seg = trim($seg);
    $seg = strip_tags($seg);
    $seg = addslashes($seg);
    return $seg;
}
//anti sql injection na $_GET['id']
$_GET['id'] = anti_sql($_GET['id']);

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM ssh_accounts WHERE id = '$id'";
    //consulta login e senha do id
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $login = $row['login'];
    $categoria = $row['categoriaid'];
    $byid = $row['byid'];
    $uuid = $row['uuid'];
}
if ($byid == $_SESSION['iduser']) {
}else{
    echo "<script>sweetAlert('Oops...', 'Você não tem permissão para editar este usuário!', 'error').then(function(){window.location.href='../home.php'});</script>";
    unset($_POST['criaruser']);
    unset($_POST['usuariofin']);
    unset($_POST['senhafin']);
    unset($_POST['validadefin']);
    exit();
}

//todos os servidores com a categoria do usuario
$sql2 = "SELECT * FROM servidores WHERE subid = ?";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_bind_param($stmt2, "i", $categoria);
mysqli_stmt_execute($stmt2);
$result = mysqli_stmt_get_result($stmt2);

$loop = Factory::create();
            $servidores_com_erro = [];
            $sucess = false;         
            $senhatoken = $_SESSION['token'];
            $senhatoken = md5($senhatoken);
            while ($user_data = mysqli_fetch_assoc($result)) {
                $ipeporta = $user_data['ip'] . ':6969';
                            $timeout = 3;
                            $socket = @fsockopen($user_data['ip'], 6969, $errno, $errstr, $timeout);
                            if ($socket) {
                                fclose($socket);
                                $loop->addTimer(0.001, function () use ($ssh, $user_data, $conn, $login, $senhatoken, $uuid) {
                                    if ($uuid == '') {
                                        $comando = 'sudo ./atlasremove.sh ' . $login;
                                    }else{
                                        $comando = 'sudo ./rem.sh ' . $uuid .' '. $login;
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
                                });
                                $sucess = true;
                } else {
                    $servidores_com_erro[] = $user_data['ip'];
                }
            }

if (!$sucess) {
    echo 'erro no servidor';
} elseif ($sucess == true) {
    echo 'suspenso com sucesso';
    $suspenso = "Suspenso";
    $sql3 = "UPDATE ssh_accounts SET mainid = ? WHERE id = ?";
    $stmt3 = mysqli_prepare($conn, $sql3);
    mysqli_stmt_bind_param($stmt3, "si", $suspenso, $id);
    mysqli_stmt_execute($stmt3);
}
$loop->run();

?>