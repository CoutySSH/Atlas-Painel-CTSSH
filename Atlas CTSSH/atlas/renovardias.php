<?php 
if (!isset($_SESSION)){
    //error_reporting(0);
session_start();

}
//se a sessão não existir, redireciona para o login
if(!isset($_SESSION['login']) and !isset($_SESSION['senha'])){
    session_destroy();
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('location:index.php');
    exit();
}
include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}
include('../vendor/event/autoload.php');
use React\EventLoop\Factory;
//include('header2.php');

//consulta se esta em dia
$sql5 = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[iduser]'";
$sql5 = $conn->query($sql5);
$row = $sql5->fetch_assoc();
$validade = $row['expira'];
$categoria = $row['categoriaid'];
$tipo = $row['tipo'];
$_SESSION['limite'] = $row['limite'];
$_SESSION['tipodeconta'] = $row['tipo'];
if ($_SESSION['tipodeconta'] == ''){
    $_SESSION['tipodeconta'] = 'Validade';
}

//time zone
date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d H:i:s');
if ($_SESSION['tipodeconta'] == 'Credito') {
    if ($_SESSION['limite'] < 1) {
        echo "<script>alert('Limite Atingido')</script>";
        echo "<script>window.location.href = '../home.php'</script>";
        unset($_POST['criaruser']);
        unset($_POST['usuariofin']);
        unset($_POST['senhafin']);
        unset($_POST['validadefin']);
        exit();
    }
}elseif ($_SESSION['tipodeconta'] == 'Validade') {
if ($validade < $hoje) {
    echo "<script>alert('Sua conta está vencida')</script>";
    echo "<script>window.location.href = '../home.php'</script>";
    unset($_POST['criaruser']);
    unset($_POST['usuariofin']);
    unset($_POST['senhafin']);
    unset($_POST['validadefin']);
    exit();
    if ($restante < 1) {
      echo "<script>alert('Limite Atingido')</script>";
      echo "<script>window.location.href = '../home.php'</script>";
      unset($_POST['criaruser']);
      unset($_POST['usuariofin']);
      unset($_POST['senhafin']);
      unset($_POST['validadefin']);
    }
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
    $senha = $row['senha'];
    $validade = $row['expira'];
    $limite = $row['limite'];
    $byid = $row['byid'];
    if($_SESSION['tipodeconta'] == 'Credito'){
        if ($limite > $_SESSION['limite']){
            echo "<script>alert('Limite Atingido')</script>";
            echo "<script>window.location.href = '../home.php'</script>";
            unset($_POST['criaruser']);
            unset($_POST['usuariofin']);
            unset($_POST['senhafin']);
            unset($_POST['validadefin']);
            exit();
        }
    }


    $_SESSION['categoriarenov'] = $row['categoriaid'];
    if ($validade < date('Y-m-d H:i:s')){
        $validade = date('Y-m-d H:i:s');
    }
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
//adicionar mais 30 dias

//novadata para dias
date_default_timezone_set('America/Sao_Paulo');

$novadata = date('Y-m-d H:i:s', strtotime("+31 days", strtotime($validade)));
    $novadata = date('Y-m-d H:i:s', strtotime($novadata));
    $data = date('Y-m-d H:i:s');
$diferenca = strtotime($novadata) - strtotime($data);
$dias = floor($diferenca / (60 * 60 * 24));


//time zone
date_default_timezone_set('America/Sao_Paulo');
$datahoje = date('d-m-Y H:i:s');
$sql10 = "INSERT INTO logs (revenda, validade, texto, userid) VALUES ('$_SESSION[login]', '$datahoje', 'Renovou 30 dias para o usuario $login', '$_SESSION[iduser]')";
$result10 = mysqli_query($conn, $sql10);

set_time_limit(0); // Limite de tempo de execução: 2h. Deixe 0 (zero) para sem limite
ignore_user_abort(true); // Continua a execução mesmo que o usuário cancele o download


set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
$tentativas = 0;
$max_tentativas = 3;
$sucesso = 0;
include ('Net/SSH2.php');
define('SSH_PORT', 22);
            
// Função para tentar a conexão SSH com um servidor
$loop = Factory::create();
$sql2 = "SELECT * FROM servidores WHERE subid = '$categoria'";
          $result = $conn -> query($sql2);
            $servidores_com_erro = [];
            $sucess = false;        
            $sucess_servers = []; 
            $senhatoken = $_SESSION['token'];
            $senhatoken = md5($senhatoken);
            while ($user_data = mysqli_fetch_assoc($result)) {
                $conectado = false;
                $ipeporta = $user_data['ip'] . ':6969';
                $timeout = 3;
                $socket = @fsockopen($user_data['ip'], 6969, $errno, $errstr, $timeout);
            
                if ($socket) {
                    fclose($socket);
                    $loop->addTimer(0.001, function () use ($user_data, $conn, $login, $dias, $senha, $limite, $senhatoken) {

                             $comando1 = 'sudo rm -rf /etc/SSHPlus/userteste/'.$login.'.sh';
                                $comando2 = 'sudo ./atlasremove.sh ' . $login . ' ';
                                $comando3 = 'sudo ./atlascreate.sh ' . $login . ' ' . $senha . ' ' . $dias . ' ' . $limite . ' ';
                             $headers = array(
                                  'Senha: ' . $senhatoken
                              );
                               $ch = curl_init();
                               curl_setopt($ch, CURLOPT_URL, $user_data['ip'] . ':6969');
                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                               curl_setopt($ch, CURLOPT_POST, 1);
                               curl_setopt($ch, CURLOPT_POSTFIELDS, "comando=$comando1");
                               $output = curl_exec($ch);
                               
                                 curl_close($ch);
                                 $ch = curl_init();
                               curl_setopt($ch, CURLOPT_URL, $user_data['ip'] . ':6969');
                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                               curl_setopt($ch, CURLOPT_POST, 1);
                               curl_setopt($ch, CURLOPT_POSTFIELDS, "comando=$comando2");
                               $output = curl_exec($ch);
                               
                                 curl_close($ch);
                                 $ch = curl_init();
                               curl_setopt($ch, CURLOPT_URL, $user_data['ip'] . ':6969');
                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                               curl_setopt($ch, CURLOPT_POST, 1);
                               curl_setopt($ch, CURLOPT_POSTFIELDS, "comando=$comando3");
                               $output = curl_exec($ch);
                                 curl_close($ch);

                    });
                    $sucess_servers[] = $user_data['nome'];
                    $sucess = true;
                } else {
                    $servidores_com_erro[] = $user_data['ip'];
                    $failed_servers[] = $user_data['nome'];
                }
            }            
            
if ($sucess == true) {
    $sql3 = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[iduser]'";
    $result3 = mysqli_query($conn, $sql3);
    $row3 = mysqli_fetch_assoc($result3);
    $limiteatual = $row3['limite'];
    $limiteatual = $limiteatual - $limite;

    if ($tipo == 'Credito') {
        $sql11 = "UPDATE atribuidos SET limite = '$limiteatual' WHERE userid = '$_SESSION[iduser]'";
        $result11 = mysqli_query($conn, $sql11);
    }
    echo 'Renovado com Sucesso!';
    $sql = "UPDATE ssh_accounts SET expira = '$novadata', mainid = '0' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
}
if ($sucess == false) {
    echo 'Erro ao renovar!';
}
$loop->run();
?>