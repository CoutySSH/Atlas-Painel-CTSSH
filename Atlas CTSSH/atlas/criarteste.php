
<?php
error_reporting(0);
session_start();
//gerador de senha
set_time_limit(0); // Limite de tempo de execução: 2h. Deixe 0 (zero) para sem limite
ignore_user_abort(true); // Continua a execução mesmo que o usuário cancele o download
set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
include ('Net/SSH2.php');
     include('conexao.php');
     include('../vendor/event/autoload.php');
     use React\EventLoop\Factory;
     $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
     if (!$conn) {
         die("Connection failed: " . mysqli_connect_error());
         
     }
     if (isset($_SESSION['mensagem_enviada'])) {
        unset($_SESSION['mensagem_enviada']);
      }
     
$sql = "SELECT limite FROM atribuidos WHERE userid = '$_SESSION[iduser]'";
$result = $conn->prepare($sql);
$result->execute();
$result->bind_result($limiteatual);
$result->fetch();
$result->close();

$slq2 = "SELECT sum(limite) AS limiteusado  FROM atribuidos where byid='".$_SESSION['iduser']."' ";
$result = $conn->prepare($slq2);
$result->execute();
$result->bind_result($limiteusado);
$result->fetch();
$result->close();
function generateUUID() {
    $uuid = sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
    return $uuid;
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

$sql3 = "SELECT * FROM atribuidos WHERE byid = '$_SESSION[iduser]'";
$sql3 = $conn->prepare($sql3);
$sql3->execute();
$sql3->store_result();
$num_rows = $sql3->num_rows;
$numerodereven = $num_rows;

$slq2 = "SELECT sum(limite) AS numusuarios  FROM ssh_accounts where byid='".$_SESSION['iduser']."' ";
$result = $conn->prepare($slq2);
$result->execute();
$result->bind_result($numusuarios);
$result->fetch();
$result->close();

$limiteusado = $limiteusado + $numusuarios;
$restante = $_SESSION['limite'] - $limiteusado;
$_SESSION['restante'] = $restante;

        $sql5 = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[iduser]'";
        $sql5 = $conn->query($sql5);
        $row = $sql5->fetch_assoc();
        $validade = $row['expira'];
        $categoria = $row['categoriaid'];
        $_SESSION['tipodeconta'] = $row['tipo'];
        $_SESSION['limite'] = $row['limite'];
        $tipo = $_SESSION['tipodeconta'];
        if ($tipo == 'Credito') {
        $tipo = '<code>Restam '.$_SESSION['limite'].' Creditos</code>';
        }else{
        $tipo = '<code>Seu limite usado é de '.$limiteusado.' Logins de '.$_SESSION['limite'].'</code>';
        }

        date_default_timezone_set('America/Sao_Paulo');
        $hoje = date('Y-m-d H:i:s');
        if ($_SESSION['tipodeconta'] == 'Credito') {
        }else{
        if ($validade < $hoje) {
            echo "<script>alert('Sua conta está vencida')</script>";
            echo "<script>window.location.href = '../home.php'</script>";
            unset($_POST['criaruser']);
            unset($_POST['usuariofin']);
            unset($_POST['senhafin']);
            unset($_POST['validadefin']);
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
        
        $sql5 = "SELECT * FROM accounts WHERE id = '1'";
              $sql5 = $conn->query($sql5);
              $row = $sql5->fetch_assoc();
              $testelimite = $row['mb'];
              
        $sql2 = "SELECT * FROM servidores WHERE subid = '$categoria'";
                  $result = $conn -> query($sql2);
                  while ($row = $result->fetch_assoc()) { 
                    $servidores[] = $row; 
                    }
     ?>
                
            
              <?php
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
              
              include('header2.php');
              if (isset($_POST['criaruser'])) {
                ignore_user_abort(true);
                set_time_limit(0);
                 $usuariofin = $_POST['usuariofin'];
                 $senhafin = $_POST['senhafin'];
                 $validadefin = $_POST['validadefin'];
                 $notas = $_POST['notas'];
                 //anti sql injection
                    $usuariofin = anti_sql($usuariofin);
                    $senhafin = anti_sql($senhafin);
                    $validadefin = anti_sql($validadefin);
                    $notas = anti_sql($notas);

                 if ($validadefin > $testelimite) {
                   echo "<script language='javascript' type='text/javascript'>alert('Ops.. Você não pode criar um teste com mais de $testelimite Minutos!');window.location.href='criarteste.php';</script>";
                   die();
                 }
                 if ($usuariofin == "") {
                  echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário não pode ser vazio!');window.location.href='criarteste.php';</script>";
                  die();
                }
                 if ($senhafin == "") {
                   echo "<script language='javascript' type='text/javascript'>alert('Ops.. Senha não pode ser vazia!');window.location.href='criarteste.php';</script>";
                   die();
                 }
                 if (preg_match('/[^a-z0-9]/i', $usuariofin)) {
                   echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário não pode conter caracteres especiais!');window.location.href='criarteste.php';</script>";
                   die();
                 }
                 if (preg_match('/[^a-z0-9]/i', $senhafin)) {
                   echo "<script language='javascript' type='text/javascript'>alert('Ops.. Senha não pode conter caracteres especiais!');window.location.href='criarteste.php';</script>";
                   die();
                 }
                 if ($_POST['limitefin'] > $_SESSION['limite']) {
                   echo "<script language='javascript' type='text/javascript'>alert('Ops.. Você não tem limite suficiente!');window.location.href='criarteste.php';</script>";
                   die();
                 }
                 $limitefin = $_POST['limitefin'];
                 if($_SESSION['tipodeconta'] == 'Credito'){
                  if ($limitefin > $_SESSION['limite']){
                      echo "<script>alert('Limite Atingido')</script>";
                      echo "<script>window.location.href = '../home.php'</script>";
                      unset($_POST['criaruser']);
                      unset($_POST['usuariofin']);
                      unset($_POST['senhafin']);
                      unset($_POST['validadefin']);
                      exit();
                  }
              }
                 $_SESSION['usuariofin'] = $usuariofin;
                  $_SESSION['senhafin'] = $senhafin;
                  $_SESSION['validadefin'] = $validadefin;
                  $_SESSION['limitefin'] = $limitefin;
                  if ($_SESSION['tipodeconta'] == 'Credito'){
                  }else{
                    if ($_POST['limitefin'] > $_SESSION['restante']) {
                      echo "<script language='javascript' type='text/javascript'>alert('Ops.. Você não tem limite suficiente!');window.location.href='criarteste.php';</script>";
                      die();
                    }
                  }

                 $sql = "SELECT * FROM ssh_accounts WHERE login = '$usuariofin'";
                 $result = $conn->query($sql);
                 if ($result->num_rows > 0) {
                   echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário já existe!');window.location.href='criarteste.php';</script>";
                   die();
                 }

                 $sql4 = "SELECT * FROM servidores WHERE subid = '$categoria'";
                   $result4 = $conn -> query($sql4);
                   $rows = mysqli_fetch_all($result4, MYSQLI_ASSOC);
                   //dias restantes para data de expiração
                   $loop = Factory::create();
                   $servidores_com_erro = [];
                   define('SCRIPT_PATH', './atlasteste.sh');
                   $sucess_servers = [];
                   $failed_servers = [];
                   $sucess = false;
                   $senha = $_SESSION['token'];
                   $senha = md5($senha);
                   //anti sql injection
                     $_POST['v2ray'] = anti_sql($_POST['v2ray']);
                   if ($_POST['v2ray'] == "sim") {
                    $v2ray = "sim";
                    $formattedUuid = generateUUID();
                    $_SESSION['uuid'] = $formattedUuid;
                }else{
                    $v2ray = "nao";
                    $_SESSION['uuid'] = "";
                }
   
                   foreach ($rows as $user_data) {
                       $conectado = false;
                       $ipeporta = $user_data['ip'] . ':6969';
                       $timeout = 3;
                       $socket = @fsockopen($user_data['ip'], 6969, $errno, $errstr, $timeout);
   
                       if ($socket) {
                           fclose($socket);
                           $loop->addTimer(0.001, function () use ($user_data, $conn, $usuariofin, $senhafin, $validadefin, $limitefin, $senha, $v2ray, $formattedUuid, $notas) {
                            if ($v2ray == "sim") {
                                $comando = "sudo ./addteste.sh $formattedUuid $usuariofin $senhafin $validadefin $limitefin ";
                            }else{
                                $comando = ("sudo ./atlasteste.sh " . $usuariofin . " " . $senhafin . " " . $validadefin . " " . $limitefin . " ");
                            }
                               $headers = array(
                                   'Senha: ' . $senha
                               );
                               $ch = curl_init();
                               curl_setopt($ch, CURLOPT_URL, $user_data['ip'] . ':6969');
                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                               curl_setopt($ch, CURLOPT_POST, 1);
                               curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                               curl_setopt($ch, CURLOPT_POSTFIELDS, "comando=$comando");
                               $output = curl_exec($ch);
                               curl_close($ch);
                               echo $output;
                           });
   
                           $conectado = true;
                           $sucess_servers[] = $user_data['nome'];
                           $sucess = true;
                       }
   
                       if (!$conectado) {
                           $servidores_com_erro[] = $user_data['ip'];
                           $failed_servers[] = $user_data['nome'];
                       }
                       $loop->run();
                   }
                if (!$sucess) {
                    echo "<script>alert('Erro ao criar usuário!');window.location.href='criarusuario.php';</script>";
                    die();
                }
                  if ($sucess == true) {
                    $sucess_servers_str = implode(", ", $sucess_servers);
                    $failed_servers_str = implode(", ", $failed_servers);
                    echo "<script>window.location.href = 'testecriado.php?sucess=$sucess_servers_str&failed=$failed_servers_str';</script>";
                        $datahoje = date('d-m-Y H:i:s');
                        $sql10 = "INSERT INTO logs (revenda, byid, validade, texto, userid) VALUES ('$_SESSION[login]', '$_SESSION[byid]', '$datahoje', 'Criou um Teste $usuariofin de $validadefin Minutos ', '$_SESSION[iduser]')";
                        $result10 = mysqli_query($conn, $sql10);
                        $_SESSION['whatsapp'] = $_POST['whatsapp'];
                        $data = date('Y-m-d H:i:s');
                        $data = strtotime($data);
                        $data = strtotime("+".$validadefin." minutes", $data);
                        $data = date('Y-m-d H:i:s', $data);
                        $validadefin = $data;
                
                    $sql9 = "INSERT INTO ssh_accounts (login, senha, expira, limite, byid, categoriaid, status, bycredit, mainid, lastview, uuid, whatsapp) VALUES ('$usuariofin', '$senhafin', '$validadefin', '$limitefin', '$_SESSION[iduser]', '$categoria', 'Offline', '0', '0', '$notas', '$formattedUuid', '$_POST[whatsapp]')";
                    $result9 = mysqli_query($conn, $sql9);
                      } else {
                        echo "<script>alert('Erro ao criar teste, tente novamente')</script>";
                        echo "<script>window.location.href = 'criarteste.php';</script>";
                        die();
                      } 
                      $loop->run();
                  } 
?>

<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <p class="text-primary">Aqui você pode criar um teste para seu cliente.</p>
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section id="dashboard-ecommerce">
                    <div class="row">
                <section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Criar Teste</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-horizontal" action="criarteste.php" method="POST">
                                            <div class="form-body">
                                                <button type="button" class="btn btn-primary mr-1 mb-1" onclick="gerar()">Gerar Aleatorio</button>
                                                <p class="text-primary"><?php echo $tipo ?></p>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Login</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="usuariofin" placeholder="Login">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Senha</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="senhafin" placeholder="Senha">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Limite</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="number" class="form-control" value="1" min="1" name="limitefin" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Minutos</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="number" class="form-control" value="60" min="1" max="<?php echo $testelimite ?>" name="validadefin" />
                                                    </div>
                                                    <div class="col-md-4">
                                                    <label>V2Ray <span class="text-success">(BETA)</span></label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <select class="form-control select2-size-sm" name="v2ray" id="v2ray">
                                                                <option value="nao">Não</option>
                                                                <option value="sim">Sim</option>
                                                            </select>
                                                        </div>
                                            
                                                    <div class="col-md-4">
                                                        <label>Notas</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="notas" placeholder="Notas">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Numero Whatsapp (NUMERO IGUAL AO WHATSAPP)</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="whatsapp" placeholder="5511999999999">
                                                    </div>
                                                    <div class="col-12 col-md-8 offset-md-4 form-group">
                                                        <fieldset>
                                                            
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-sm-12 d-flex justify-content-end">
                                                        
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1" name="criaruser">Criar</button>
                                                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Cancelar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            function gerar() {
                                var usuario = document.getElementsByName("usuariofin")[0];
                                var senha = document.getElementsByName("senhafin")[0];
                                var limite = document.getElementsByName("limitefin")[0];
                                var validade = document.getElementsByName("validadefin")[0];
                                var caracteres = "0123456789";
                                var caracteres_senha = "abcdefghijklmnopqrstuvwxyz";
                                var usuario_gerado = caracteres.charAt(Math.floor(Math.random() * 26));
                                var senha_gerada = caracteres_senha.charAt(Math.floor(Math.random() * 26));
                                for (var i = 0; i < 3; i++) {
                                    usuario_gerado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
                                }
                                for (var i = 0; i < 3; i++) {
                                    senha_gerada += caracteres_senha.charAt(Math.floor(Math.random() * caracteres_senha.length));
                                }
                                usuario.value = usuario_gerado + senha_gerada;
                                senha.value = usuario_gerado + senha_gerada;
                                limite.value = 1;
                                validade.value = 60;
                            }
                        </script> <script src="../app-assets/js/scripts/forms/number-input.js"></script>           