
<?php
if (!isset($_SESSION)){
    error_reporting(0);
session_start();

}
set_time_limit(0); // Limite de tempo de execuÃ§Ã£o: 2h. Deixe 0 (zero) para sem limite
ignore_user_abort(true); // Continua a execuÃ§Ã£o mesmo que o usuÃ¡rio cancele o download
set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
    include ('Net/SSH2.php');

//se a sessÃ£o nÃ£o existir, redireciona para o login
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
include_once '../admin/suspenderrev.php';
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
    $_SESSION['logineditar'] = $row['login'];
    $_SESSION['senhaeditar'] = $row['senha'];
    $_SESSION['validadeeditar'] = $row['expira'];
    $_SESSION['limiteeditar'] = $row['limite'];
    $byid = $row['byid'];
    $notas = $row['lastview'];
    $whatsapp = $row['whatsapp'];
    $valormensal = $row['valormensal'];
    $_SESSION['byidusereditar'] = $row['byid'];
    $uuid = $row['uuid'];
    if ($uuid == '') {
        $uuid = 'Nao Gerado';
    }   

}
 if ($_SESSION['byidusereditar'] == $_SESSION['iduser']) {
    
}else{
    echo "<script>sweetAlert('Oops...', 'VocÃª nÃ£o tem permissÃ£o para editar este usuÃ¡rio!', 'error').then(function(){window.location.href='../home.php'});</script>";
    unset($_POST['criaruser']);
    unset($_POST['usuariofin']);
    unset($_POST['senhafin']);
    unset($_POST['validadefin']);
} 
$logineditar = $_SESSION['logineditar'];
$senhaeditar = $_SESSION['senhaeditar'];
$validadeeditar = $_SESSION['validadeeditar'];
$limiteeditar = $_SESSION['limiteeditar'];


$validadeeditar = date('Y-m-d H:i:s', strtotime($validadeeditar));
$data = date('Y-m-d H:i:s');
$diferenca = strtotime($validadeeditar) - strtotime($data);
$dias = floor($diferenca / (60 * 60 * 24));

$dias = $dias + 1;

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
                $tipo = $row['tipo'];
$_SESSION['tipodeconta'] = $row['tipo'];

                //time zone
                date_default_timezone_set('America/Sao_Paulo');
                $hoje = date('Y-m-d H:i:s');
                if ($_SESSION['tipodeconta'] == 'Credito') {
                }else{
                if ($validade < $hoje) {
                    echo "<script>sweetAlert('Oops...', 'Sua conta expirou!', 'error').then(function(){window.location.href='../home.php'});</script>";
                    unset($_POST['criaruser']);
                    unset($_POST['usuariofin']);
                    unset($_POST['senhafin']);
                    unset($_POST['validadefin']);
                }
                }
  $sql2 = "SELECT * FROM servidores WHERE subid = '$categoria'";
  $result77 = $conn -> query($sql2);
  while ($row = $result77->fetch_assoc()) { 
    $servidores[] = $row; 
    }

    $sql5 = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[iduser]'";
$sql5 = $conn->query($sql5);
$row = $sql5->fetch_assoc();
$validade = $row['expira'];
$categoria = $row['categoriaid'];
$tipo = $row['tipo'];
$_SESSION['limite'] = $row['limite'];
$_SESSION['tipodeconta'] = $row['tipo'];
if ($tipo == 'Credito') {
$tipo = 'Restam '.$_SESSION['limite'].' Creditos';
}else{
$tipo = 'Seu limite usado Ã© de '.$limiteusado.' Logins de '.$_SESSION['limite'].'';
}
?>

          <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <p class="text-primary">Aqui VocÃª Editar o Login do Cliente.</p>
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
                                    <h4 class="card-title">Editar UsuÃ¡rio</h4>
                                </div>
                                
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-horizontal" action="editarlogin.php" method="POST">
                                            <div class="form-body">
                                                <div class="row">
                                                  

                                                    <div class="col-md-4">
                                                        <label>Login</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="usuarioedit" placeholder="Login" value="<?php echo $logineditar; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Senha</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="senhaedit" placeholder="Senha" value="<?php echo $senhaeditar; ?>">
                                                    </div>
                                                    <?php if ($_SESSION['tipodeconta'] == "Credito") {
                                                    }else{
                                                      echo '
                                                    <div class="col-md-4">
                                                        <label>Limite</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="number" class="form-control" min="1" name="limiteedit" value="'.$limiteeditar.'" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Dias</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="number" class="form-control" min="1" name="validadeedit" value="'.$dias.'" />
                                                    </div>';
                                                    } ?>
                                                    <div class="col-md-4">
                                                        <label>Valor Mensal</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="valormensal" placeholder="Valor Mensal" value="<?php echo $valormensal; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                    <label>V2Ray <span class="text-success">(BETA)</span></label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <input type="text" class="form-control" name="uuid" placeholder="UUID" value="<?php echo $uuid; ?>" readonly>
                                                        </div>
                                                    <div class="col-md-4">
                                                        <label>Notas</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="notas" placeholder="Notas" value="<?php echo $notas; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Numero Whatsapp (NUMERO IGUAL AO WHATSAPP)</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="whatsapp" placeholder="+5511999999999" value="<?php echo $whatsapp; ?>">
                                                    </div>
                                                    <div class="col-12 col-md-8 offset-md-4 form-group">
                                                        <fieldset>
                                                            
                                                        </fieldset>
                                                        <code><?php echo $tipo?></code>
                                                    </div>
                                                    <div class="col-sm-12 d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1" name="editauser">Editar</button>
                                                        <button onclick="sair()" type="button" class="btn btn-light-secondary mr-1 mb-1">Voltar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            function sair() {
                                window.location.href = "listarusuarios.php";
                            }
                            function gerar() {
                                var usuario = document.getElementsByName("usuariofin")[0];
                                var senha = document.getElementsByName("senhafin")[0];
                                var limite = document.getElementsByName("limitefin")[0];
                                var validade = document.getElementsByName("validadefin")[0];
                                var caracteres = "0123456789abcdefghijklmnopqrstuvwxyz";
                                var caracteres_senha = "0123456789abcdefghijklmnopqrstuvwxyz";
                                var usuario_gerado = "";
                                var senha_gerada = "";
                                for (var i = 0; i < 10; i++) {
                                    usuario_gerado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
                                }
                                for (var i = 0; i < 10; i++) {
                                    senha_gerada += caracteres_senha.charAt(Math.floor(Math.random() * caracteres_senha.length));
                                }
                                usuario.value = usuario_gerado;
                                senha.value = senha_gerada;
                                limite.value = 1;
                                validade.value = 30;
                            }
                        </script> <script src="../app-assets/js/scripts/forms/number-input.js"></script>

<?php

    include('../vendor/event/autoload.php');
    use React\EventLoop\Factory;
if(isset($_POST['editauser'])){
    $usuarioedit = $_POST['usuarioedit'];
    $senhaedit = $_POST['senhaedit'];
    $validadeedit = $_POST['validadeedit'];
    $limiteedit = $_POST['limiteedit'];
    $notas = $_POST['notas'];
    $valormensal = $_POST['valormensal'];
    //anti sql injection
    $usuarioedit = anti_sql($usuarioedit);
    $senhaedit = anti_sql($senhaedit);
    $validadeedit = anti_sql($validadeedit);
    $limiteedit = anti_sql($limiteedit);
    $notas = anti_sql($notas);
    $valormensal = anti_sql($valormensal);
    
    if ($limiteedit == $limiteeditar) {
    }else{
    $limiteusado = $limiteeditar - $limiteedit;
    $restante = $restante + $limiteusado;
    //se restante for maior que 0
          if ($_SESSION['tipodeconta'] == "Validade") {
            if ($restante < 0) {
              echo "<script>sweetAlert('Oops...', 'Limite Insuficiente!', 'error').then(function(){window.location.href='editarlogin.php'});</script>";
              exit();
            }
          }
    }


    $data = date('Y-m-d H:i:s');
    $data = strtotime("+".$validadeedit." days", strtotime($data));
    $data = date('Y-m-d H:i:s', $data);

        $sql = "SELECT * FROM ssh_accounts WHERE login = '$usuarioedit'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  //se o usuario encontrado for diferente do usuario que esta sendo editado
  if ($usuarioedit != $logineditar) {
    echo "<script>sweetAlert('Oops...', 'Usuario ja existe!', 'error').then(function(){window.location.href='editarlogin.php'});</script>";
            exit();
  }
}
    date_default_timezone_set('America/Sao_Paulo');
        if ($_SESSION['tipodeconta'] == "Credito") {
          $validadeedit = $dias;
          $limiteedit = $_SESSION['limiteeditar'];
        }
        if ($validadeedit < 1) {
          $validadeedit = 1;
        }
        $sql2 = "SELECT * FROM servidores WHERE subid = '$categoria'";
        $result77 = $conn -> query($sql2);
        $loop = Factory::create();
        $servidores_com_erro = [];
        $sucess_servers = array();
        $failed_servers = array();
        $sucess = false;
        $senha = $_SESSION['token'];
        $senha = md5($senha);
        while ($user_data = mysqli_fetch_assoc($result77)) {
            $conectado = false;
            $ipeporta = $user_data['ip'] . ':6969';
            $timeout = 3;
            $socket = @fsockopen($user_data['ip'], 6969, $errno, $errstr, $timeout);
        

            if ($socket) {
                fclose($socket);
                $loop->addTimer(0.001, function () use ($user_data, $conn, $usuarioedit, $senhaedit, $validadeedit, $limiteedit, $notas, $valormensal, $logineditar, $validade, $senha) {
                    $comando1 = 'sudo ./atlasremove.sh ' . $logineditar.' ';
                    
                    $comando2 = 'sudo rm -rf /etc/SSHPlus/userteste/'.$logineditar.'.sh';
                    $comando3 = 'sudo ./atlascreate.sh ' . $usuarioedit . ' ' . $senhaedit . ' ' . $validadeedit . ' ' . $limiteedit . ' ';

                    
                    $headers = array(
                        'Senha: ' . $senha
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $user_data['ip'] . ':6969');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "comando=$comando1");
                    $output = curl_exec($ch);
                    curl_close($ch);
                    //echo $output;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $user_data['ip'] . ':6969');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "comando=$comando2");
                    $output = curl_exec($ch);
                    curl_close($ch);
                    //echo $output;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $user_data['ip'] . ':6969');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "comando=$comando3");
                    $output = curl_exec($ch);
                    curl_close($ch);
                    //echo $output;
                });

                $sucess_servers[] = $user_data['nome'];
                $conectado = true;
                $sucess = true;
            }

        if (!$conectado) {
            $servidores_com_erro[] = $user_data['ip'];
            $failed_servers[] = $user_data['nome'];
        }
}

      if ($sucess == true) {
          $_SESSION['usuariofin'] = $usuarioedit;
          $_SESSION['senhafin'] = $senhaedit;
          $sucess_servers_str = implode(", ", $sucess_servers);
          $failed_servers_str = implode(", ", $failed_servers);
          if ($_SESSION['tipodeconta'] == "Credito") {
              $_SESSION['validadefin'] = $_SESSION['validadeeditar'];
              
            }else{
                $_SESSION['validadefin'] = $data;
            }
            $_SESSION['limitefin'] = $limiteedit;
            //anti sql injection
            $_SESSION['usuariofin'] = anti_sql($_SESSION['usuariofin']);
            $_SESSION['senhafin'] = anti_sql($_SESSION['senhafin']);
            $_SESSION['validadefin'] = anti_sql($_SESSION['validadefin']);
            $_SESSION['limitefin'] = anti_sql($_SESSION['limitefin']);
            $_POST['whatsapp'] = anti_sql($_POST['whatsapp']);
            $_POST['notas'] = anti_sql($_POST['notas']);
            $_POST['valormensal'] = anti_sql($_POST['valormensal']);
            
            if ($_SESSION['tipodeconta'] == "Credito") {
                $sql = "UPDATE ssh_accounts SET login = '$usuarioedit', senha = '$senhaedit', mainid = '', lastview = '$notas', whatsapp = '$_POST[whatsapp]' WHERE login = '$logineditar'";
                $sql = $conn->prepare($sql);
                $sql->execute();
               
                echo "<script>window.location.href = 'criado.php?sucess=$sucess_servers_str&failed=$failed_servers_str';</script>";
            } else {
            //echo "<script language='javascript' type='text/javascript'>alert('Usuario Editado Com Sucesso!');window.location.href='home.php';</script>";
            $sql = "UPDATE ssh_accounts SET login = '$usuarioedit', senha = '$senhaedit', expira = '$data', limite = '$limiteedit', mainid = '', lastview = '$notas', valormensal = '$valormensal', whatsapp = '$_POST[whatsapp]' WHERE login = '$logineditar'";
            $sql = $conn->prepare($sql);
            $sql->execute();
            echo "<script>window.location.href = 'criado.php?sucess=$sucess_servers_str&failed=$failed_servers_str';</script>";
        }
    }
    if ($sucess == false) {
        echo "<script>sweetAlert('Oops...', 'Erro ao Editar Usuario!', 'error').then(function(){window.location.href='editarlogin.php'});</script>";
    }
    $loop->run();
}



?>

