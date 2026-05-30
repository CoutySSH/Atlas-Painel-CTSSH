<script src="../app-assets/sweetalert.min.js"></script>
<?php 
use GuzzleHttp\Psr7\Query;
use LDAP\Result;
if (!isset($_SESSION)){
    error_reporting(0);
session_start();

}
include('../vendor/event/autoload.php');
use React\EventLoop\Factory;
//se a sessão não existir, redireciona para o login
if(!isset($_SESSION['login']) and !isset($_SESSION['senha'])){
    session_destroy();
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('location:index.php');
}
include 'header2.php';
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
    
}

$sql = "SELECT * FROM atribuidos WHERE userid = '$id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categoria = $row['categoriaid'];
        $limitetinha = $row['limite'];
        $byid = $row['byid'];
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

$contas = null;
$ssh_accounts = null;

set_time_limit(0); // Limite de tempo de execução: 2h. Deixe 0 (zero) para sem limite
ignore_user_abort(true); // Continua a execução mesmo que o usuário cancele o download
set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
    include ('Net/SSH2.php');
//consulta todos logins 

  $sql1 = "SELECT * FROM accounts WHERE byid = '$id'";
  $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $contas[] = $row1;
        }
    }
    if ($contas == null) {
    }else{
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
            }
        }
    }
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
                
            }
        }
    }
}

    $sql3 = "SELECT * FROM accounts WHERE id = '$id'";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows > 0) {
        while ($row3 = $result3->fetch_assoc()) {
            $contas[] = $row3;
            $deletes[] = $row3;
        }
    }
    if ($contas == null) {
    }else{
    foreach ($contas as $conta) {
        $sql2 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $contas[] = $row2;
                
            }
        }
    }
    }
    $sql55 = "SELECT * FROM accounts WHERE byid = '$conta[id]'";
    $result55 = $conn->query($sql55);
    if ($result55->num_rows > 0) {
        while ($row55 = $result55->fetch_assoc()) {
            $dells[] = $row55;
            
        }
    }
    
    
    
    
    $contas = array_unique($contas, SORT_REGULAR);
    //pesquisar todos ssh_accouns de $contas e envia um txt para o servidor
    foreach ($contas as $conta) {
        $sql3 = "SELECT * FROM ssh_accounts WHERE byid = '$conta[id]'";
        $result3 = $conn->query($sql3);
        if ($result3->num_rows > 0) {
            while ($row3 = $result3->fetch_assoc()) {
                $ssh_accounts[] = $row3;
            }
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

     

    //deletar todas as contas do banco de dados
    //print_r($contas);
    set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
    include ('Net/SFTP.php');
    $sql2 = "SELECT * FROM servidores WHERE subid = '$categoria'";
          $result = $conn -> query($sql2);
          //time zone
          if ($result->num_rows > 0) {
            $loop = Factory::create();
            $servidores_com_erro = [];
            $sucess = false;         
            $senha = $_SESSION['token'];
            $senha = md5($senha);
            while ($user_data = mysqli_fetch_assoc($result)) {
                $conectado = false;
                $ipeporta = $user_data['ip'] . ':6969';
                $timeout = 3;
                $socket = @fsockopen($user_data['ip'], 6969, $errno, $errstr, $timeout);
          
                if ($socket) {
                    fclose($socket);
                      $loop->addTimer(0.001, function () use ($user_data, $conn, $nome, $senha) {
                        $local_file = $nome;
                        $limiter_content = file_get_contents($local_file);
                        
                        $ipeporta = $user_data['ip'] . ':6969';
                        $headers = array(
                            'Senha: ' . $senha
                        );
                        $ch1 = curl_init();
                        curl_setopt($ch1, CURLOPT_URL, 'http://' . $ipeporta);
                        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch1, CURLOPT_POST, 1);
                        curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query(array('comando' => 'echo "' . $limiter_content . '" > /root/' . $nome)));
                        curl_exec($ch1);
                        curl_close($ch1);
        
                        $ch2 = curl_init();
                        curl_setopt($ch2, CURLOPT_URL, 'http://' . $ipeporta);
                        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch2, CURLOPT_POST, 1);
                        curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query(array('comando' => 'sudo python3 /root/sincronizar.py ' . $nome . ' > /dev/null 2>/dev/null &')));
                        curl_exec($ch2);
                        curl_close($ch2);
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
    




if ($sucess == true) {
echo "1";
    foreach ($contas as $conta) {
        //print_r($conta);
        //update para suspenco
        $sql5 = "UPDATE ssh_accounts SET mainid = '' WHERE byid = '$conta[id]'";
        $result5 = $conn->query($sql5);
        $sql6 = "UPDATE atribuidos SET suspenso = '0' WHERE userid = '$conta[id]'";
        $result6 = $conn->query($sql6);
    }
    foreach ($deletes as $delete) {
        //print_r($delete);
       $sql5 = "UPDATE ssh_accounts SET mainid = '' WHERE byid = '$delete[id]'";
       $result5 = $conn->query($sql5);
       $sql6 = "UPDATE atribuidos SET suspenso = '0' WHERE userid = '$delete[id]'";
         $result6 = $conn->query($sql6);
   } 
   foreach ($dells as $dell) {
    //print_r($dell);
   $sql5 = "UPDATE ssh_accounts SET mainid = '' WHERE byid = '$dell[id]'";
   $result5 = $conn->query($sql5);
   $sql6 = "UPDATE atribuidos SET suspenso = '0' WHERE userid = '$dell[id]'";
     $result6 = $conn->query($sql6);
}
    echo "<script>sweetAlert('Sucesso!', 'Revendedor Reativado com sucesso!', 'success').then(function() { window.location.href = 'listarrevendedores.php'; });</script>";
}else{
    echo "<script>swal('Erro!', 'Erro ao Reativar revendedor!', 'error').then(function() { window.location.href = 'listarrevendedores.php'; });</script>";
}
$loop->run();
unlink($nome);


?>