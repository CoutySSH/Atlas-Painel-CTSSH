<?php 
use GuzzleHttp\Psr7\Query;
use LDAP\Result;
if (!isset($_SESSION)){
    error_reporting(0);
session_start();

}
include('../vendor/event/autoload.php');
use React\EventLoop\Factory;
set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
    include ('Net/SSH2.php');
//include('headeradmin2.php');
//se a sessão não existir, redireciona para o login
if(!isset($_SESSION['login']) and !isset($_SESSION['senha'])){
    session_destroy();
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('location:index.php');
}
//include 'header2.php';
include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
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

$id = $_GET['id'];

$sql6 = "SELECT * FROM servidores WHERE id = '$id'";
//consulta a categoria do servidor
$result = mysqli_query($conn, $sql6);
$row = mysqli_fetch_assoc($result);
$categoria = $row['subid'];

//pesquisa todos os logins da categoria
$sql = "SELECT * FROM ssh_accounts WHERE categoriaid = '$categoria'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ssh_accounts[] = $row;
    }
}



    $nome = md5 ( uniqid ( rand (), true ));
    $nome = substr($nome, 0, 10);
    $nome = $nome . ".txt";
    //salvar txt
    $file = fopen("$nome", "w");
    foreach ($ssh_accounts as $ssh_account) {
         $login = $ssh_account['login'];
         $senha = $ssh_account['senha'];
         $validade = $ssh_account['expira'];
         $uuid = $ssh_account['uuid'];
         //validade para dias restantes
         $validade = date('Y-m-d h:i:s', strtotime($validade));
         $data = date('Y-m-d h:i:s');
         $diferenca = strtotime($validade) - strtotime($data);
         $dias = floor($diferenca / (60 * 60 * 24));      
         $limite = $ssh_account['limite'];
         fwrite($file, $login . " " . $senha . " " . $dias . " " . $limite . " " . $uuid . "\n");
    }

    
     $sql2 = "SELECT * FROM servidores WHERE id = '$id'";
          $result = $conn -> query($sql2);
          $loop = Factory::create();
            $servidores_com_erro = [];
            $sucess = false;         
  
            while ($user_data = mysqli_fetch_assoc($result)) {
              $tentativas = 0;
              $conectado = false;
          
              while ($tentativas < 2 && !$conectado) {
                  $ssh = new Net_SSH2($user_data['ip'], $user_data['porta']);
          
                  if ($ssh->login($user_data['usuario'], $user_data['senha'])) {
                      $loop->addTimer(0.001, function () use ($ssh, $user_data, $conn, $nome) {
                          $local_file = $nome;
                          $limiter_content = file_get_contents($local_file);
                          $ssh->exec('echo "' . $limiter_content . '" > /root/' . $nome);
                          $ssh->exec('python3 /root/sincronizar.py ' . $nome . ' > /dev/null 2>/dev/null &');
                          $ssh->disconnect();
                      });
                      $conectado = true;
                      $sucess = true;
                  } else {
                      $tentativas++;
                  }
              }
          
              if (!$conectado) {
                  $servidores_com_erro[] = $user_data['ip'];
              }
          }
          foreach ($servidores_com_erro as $ip) {
            $sql2 = "SELECT id, ip, porta, usuario, senha FROM servidores WHERE ip = '$ip'";
            $result2 = mysqli_query($conn, $sql2);
            $user_data2 = mysqli_fetch_assoc($result2);
        
            $tentativas = 0;
            $conectado = false;
            $sucess = false;
        
            while ($tentativas < 2 && !$conectado) {
                $ssh = new Net_SSH2($user_data2['ip'], $user_data2['porta']);
        
                if ($ssh->login($user_data2['usuario'], $user_data2['senha'])) {
                    $loop->addTimer(0.001, function () use ($ssh, $user_data2, $conn, $nome) {
                        $local_file = $nome;
                        $limiter_content = file_get_contents($local_file);
                        $ssh->exec('echo "' . $limiter_content . '" > /root/' . $nome);
                        $ssh->exec('python3 /root/sincronizar.py ' . $nome . ' > /dev/null 2>/dev/null &');
                        $ssh->disconnect();
                    });
                    $conectado = true;
                    $sucess = true;
                } else {
                    $tentativas++;
                }
            }
        
            if (!$conectado) {
                
                $failed_servers[] = $user_data2['nome'];
            }
        }
        if ($sucess == true) {
            echo 'Comando enviado com sucesso';
        }
        $loop->run();
        unlink($nome);
        ?>



