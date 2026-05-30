<?php
session_start();
error_reporting(0);
include('../atlas/conexao.php');
include('../vendor/event/autoload.php');


                use React\EventLoop\Factory;
                use React\Promise\Promise;
//gerador de senha
set_time_limit(0); // Limite de tempo de execução: 2h. Deixe 0 (zero) para sem limite
ignore_user_abort(true); // Continua a execução mesmo que o usuário cancele o download
set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
include ('Net/SSH2.php');
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
      
    }
    if (isset($_SESSION['mensagem_enviada'])) {
        unset($_SESSION['mensagem_enviada']);
      }
$sql3 = "SELECT * FROM categorias";
$result3 = $conn -> query($sql3);
date_default_timezone_set('America/Sao_Paulo');
$datahoje = date('d-m-Y H:i:s');
unset($_SESSION['whatsapp']);
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


?>
     <?php

     $sql2 = "SELECT * FROM servidores";
     $result = $conn -> query($sql2);
     include('headeradmin2.php');
     if (!file_exists('suspenderrev.php')) {
        exit ("<script>alert('Token Invalido!');</script>");
    }else{
        include_once 'suspenderrev.php';
        
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
      
      set_time_limit(0);
              ignore_user_abort(true);
              if (isset($_POST['criaruser'])) {
                $validadefin = $_POST['validadefin'];
                $limitefin = $_POST['limitefin'];
                $usuariofin = $_POST['usuariofin'];
                $senhafin = $_POST['senhafin'];
                $categoria = $_POST['categoria'];
                $notas = $_POST['notas'];
                $valormensal = $_POST['valormensal'];
                $validadefin = anti_sql($validadefin);
                $limitefin = anti_sql($limitefin);
                $usuariofin = anti_sql($usuariofin);
                $senhafin = anti_sql($senhafin);
                $categoria = anti_sql($categoria);
                $notas = anti_sql($notas);
                $valormensal = anti_sql($valormensal);
                $_POST['whatsapp'] = str_replace(" ", "", $_POST['whatsapp']);
                $_POST['whatsapp'] = str_replace("-", "", $_POST['whatsapp']);
                $_SESSION['whatsapp'] = $_POST['whatsapp'];
                $_SESSION['usuariofin'] = $usuariofin;
                $_SESSION['senhafin'] = $senhafin;
                $_SESSION['validadefin'] = $validadefin;
                $_SESSION['limitefin'] = $limitefin;
                //verifica se o usuario ja existe
                if ($usuariofin == "") {
                  echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário não pode ser vazio!');window.location.href='criarusuario.php';</script>";
                  die();
                }
                 if ($senhafin == "") {
                   echo "<script language='javascript' type='text/javascript'>alert('Ops.. Senha não pode ser vazia!');window.location.href='criarusuario.php';</script>";
                   die();
                 }
                 //se login ou senha tiver caracteres especiais
                 if (preg_match('/[^a-z0-9]/i', $usuariofin)) {
                   echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário não pode conter caracteres especiais!');window.location.href='criarusuario.php';</script>";
                   die();
                 }
                 if (preg_match('/[^a-z0-9]/i', $senhafin)) {
                   echo "<script language='javascript' type='text/javascript'>alert('Ops.. Senha não pode conter caracteres especiais!');window.location.href='criarusuario.php';</script>";
                   die();
                 }
                $sql = "SELECT * FROM ssh_accounts WHERE login = '$usuariofin'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                  echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário já existe!');window.location.href='criarusuario.php';</script>";
                  die();
                }
                
                $sql = "SELECT * FROM servidores WHERE subid = '$categoria'";
                $result = $conn->query($sql);

                $_POST['v2ray'] = anti_sql($_POST['v2ray']);
                if ($_POST['v2ray'] == "sim") {
                    $v2ray = "sim";
                    $formattedUuid = generateUUID();
                    $_SESSION['uuid'] = $formattedUuid;
                }else{
                    $v2ray = "nao";
                    $_SESSION['uuid'] = "";
                }
                $loop = Factory::create();
                $servidores_com_erro = [];
                define('SCRIPT_PATH', './atlascreate.sh');
                $sucess_servers = array();
                $failed_servers = array();
                $sucess = false;
                while ($user_data = mysqli_fetch_assoc($result)) {
                    $conectado = false;
                    $ipeporta = $user_data['ip'] . ':6969';
                    $timeout = 3;
                    $socket = @fsockopen($user_data['ip'], 6969, $errno, $errstr, $timeout);
                
                    if ($socket) {
                        fclose($socket);
                        $loop->addTimer(0.001, function () use ($user_data, $conn, $usuariofin, $senhafin, $validadefin, $limitefin, $v2ray, $formattedUuid) {
                            if ($v2ray == "sim") {
                                $comando = "sudo ./add.sh $formattedUuid $usuariofin $senhafin $validadefin $limitefin ";
                            }else{
                                $comando = "sudo ./atlascreate.sh $usuariofin $senhafin $validadefin $limitefin ";
                            }
                            $senha = $_SESSION['token'];
                            $senha = md5($senha);
                            $headers = array(
                                'Senha: ' . $senha
                            );
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $user_data['ip'] . ':6969');
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            //timeout in seconds
                            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "comando=$comando");
                            $output = curl_exec($ch);
                            //echo $output;
                            curl_close($ch);
                        });
                
                        $conectado = true;
                        $sucess_servers[] = $user_data['nome'];
                        $sucess = true;
                    }
                
                    if (!$conectado) {
                        $servidores_com_erro[] = $user_data['ip'];
                        $failed_servers[] = $user_data['nome'];
                    }
                }
                
                if (!$sucess) {
                    echo "<script>alert('Erro ao criar usuário!');window.location.href='criarusuario.php';</script>";
                    die();
                }
                if ($sucess == true) {
                    $sucess_servers_str = implode(", ", $sucess_servers);
                    $failed_servers_str = implode(", ", $failed_servers);
                    
                    echo "<script>window.location.href = 'criado.php?sucess=$sucess_servers_str&failed=$failed_servers_str';</script>";
                      
                     
                     /* $sql10 = "INSERT INTO logs (revenda, validade, texto, userid) VALUES ('$_SESSION[login]', '$datahoje', 'Criou um Usuario $usuariofin de '$validadefin' Dias', '$_SESSION[iduser]')";
                     $result10 = mysqli_query($conn, $sql10); */
                     $data = date('Y-m-d H:i:s');
                     $data = strtotime($data);
                     
                     $data = strtotime("+" . $validadefin . " days", $data);
                     $data = date('Y-m-d H:i:s', $data);
                     $validadefin = $data;
                     
                     $sql9 = "INSERT INTO ssh_accounts (login, senha, expira, limite, byid, categoriaid, lastview ,bycredit, mainid, status, whatsapp, valormensal, uuid) VALUES ('$usuariofin', '$senhafin', '$validadefin', '$limitefin', '$_SESSION[iduser]', '$categoria', '$notas', '0', 'NULL', 'Offline', '$_SESSION[whatsapp]', '$valormensal', '$formattedUuid')";
                     $result9 = mysqli_query($conn, $sql9);
                     
                     $_SESSION['validadefin'] = $validadefin;
                }
                
                $loop->run();
                
                }
            
              ?>
 
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <p class="text-primary">Aqui você pode criar um usuário para seus clientes.</p>
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
                                    <h4 class="card-title">Criar Usuário</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-horizontal" action="criarusuario.php" method="POST">
                                            <div class="form-body">
                                            <button type="button" class="btn btn-primary mr-1 mb-1" onclick="gerar()">Gerar Aleatorio</button>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Categoria</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <select class="form-control" name="categoria">
                                                            <?php
                                                            $sql = "SELECT * FROM categorias";
                                                            $result = $conn->query($sql);
                                                            while ($row = $result->fetch_assoc()) {
                                                              $categorias[] = $row;
                                                            }
                                                            foreach ($categorias as $categoria) {
                                                              echo "<option value='" . $categoria['subid'] . "'>" . $categoria['nome'] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

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
                                                        <label>Dias</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="number" class="form-control" value="30" min="1" name="validadefin" />
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
                                                        <label>Valor Mensal Personalizado</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="valormensal" placeholder="Ex: 10">
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
                                var caracteres = "abcdefghijklmnopqrstuvwxyz";
                                var caracteres_senha = "abcdefghijklmnopqrstuvwxyz";
                                var usuario_gerado = caracteres.charAt(Math.floor(Math.random() * 26));
                                var senha_gerada = caracteres_senha.charAt(Math.floor(Math.random() * 26));
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