    
<script src="../app-assets/sweetalert.min.js"></script>
<?php
include('../atlas/conexao.php');

 include('headeradmin2.php');
 
error_reporting(0);
session_start();
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

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

$sql4 = "SELECT * FROM ssh_accounts WHERE byid = '$_SESSION[iduser]'";
$sql4 = $conn->prepare($sql4);
$sql4->execute();
$sql4->store_result();
$num_rows = $sql4->num_rows;
$numusuarios = $num_rows;


$limiteusado = $limiteusado + $numusuarios;
$restante = $_SESSION['limite'] - $limiteusado;
$_SESSION['restante'] = $restante;

$_SESSION['limiteusado'] = $limiteusado;
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

<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <p class="text-primary">Aqui você pode criar revendedores.</p>
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
                                    <h4 class="card-title">Novo Revendedor</h4>
                                </div>
                                <div id="alerta">
                                </div>
                                
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-horizontal" action="criarrevenda.php" method="POST">
                                            <div class="form-body">
                                            <button type="button" class="btn btn-primary mr-1 mb-1" onclick="gerar()">Gerar Aleatorio</button>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Selecionar categoria</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <select class="form-control" name="categoria">
                                                        <?php
                                                            $sql = "SELECT * FROM categorias";
                                                            $result = mysqli_query($conn, $sql);
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                echo "<option value='" . $row['subid'] . "'>" . $row['nome'] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label>Usuario</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="usuariorevenda" placeholder="Usuario">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Senha</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="senharevenda" placeholder="Senha">
                                                    </div>
                                                    <div class="col-md-4">
                                                <label>Selecione o Modo</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <select class="form-control select2-size-sm" name="credivalid" id="credivalid" onchange="mostrar()">
                                                    <option value="Validade">Validade</option>
                                                    <option value="Credito">Credito</option>
                                                </select>
                                            </div>
                                                    <div class="col-md-4">
                                                        <label>Limite</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="number" class="form-control" value="1" min="1" name="limiterevenda" />
                                                    </div>

                                                <script>
                                                    function mostrar() {
                                                        var tipo = document.getElementById("credivalid").value;
                                                        if (tipo == "Validade") {
                                                            document.getElementById("validade").style.display = "block";
                                                        } else {
                                                            document.getElementById("validade").style.display = "none";
                                                        }
                                                    }
                                                </script>
                                                <div class="col-md-12 form-group">
                                                    <div id="validade">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label>Validade em Dias</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                            <input type="number" class="form-control" value="30" min="1" name="validaderevenda" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                        <label>Numero Whatsapp (NUMERO IGUAL AO WHATSAPP)</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="whatsapp" placeholder="+5511999999999">
                                                    </div>
                                                <div class="col-12 col-md-8 offset-md-4 form-group">
                                                    <fieldset>
                                                        
                                                    </fieldset>
                                                </div>
                                                <div class="col-sm-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary mr-1 mb-1" name="submit">Criar</button>
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
                                var usuario = document.getElementsByName("usuariorevenda")[0];
                                var senha = document.getElementsByName("senharevenda")[0];
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
                                var alerta = document.getElementById("alerta");
                                alerta.innerHTML = "<div class='alert alert-success' role='alert'>Usuario e Senha Aleatorio Gerado!</div>";
                                setTimeout(function() {
                                    $('.alert').fadeOut();
                                }, 1000);
                                

                            }
                        </script> <script src="../app-assets/js/scripts/forms/number-input.js"></script>
                         <!--scrolling content Modal -->
 <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
<div class="col-md-6 col-12">

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-9O9Sd6Ia1+A0+KwUO1eUg0Fyb3J6UdTo68joKgY9A20+RzI2HfIQK8pk6FyUdxUGpIq3oUItrW8jYVGf9GYZRg==" crossorigin="anonymous" />
</head>
 <div class="modal fade" id="criado" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                <div class="modal-content">
                                                    
                                                <script>
                      function copyDivToClipboard() {
                        var range = document.createRange();
                        range.selectNode(document.getElementById("divToCopy"));
                        window.getSelection().removeAllRanges(); // clear current selection
                        window.getSelection().addRange(range); // to select text
                        document.execCommand("copy");
                        window.getSelection().removeAllRanges();// to deselect
                        //alert
                        swal("Copiado!", "", "success");

                      }
                    </script>
                                                    <div class="bg-alert modal-header">
                                                        <h5 class="modal-title" id="exampleModalScrollableTitle"></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <i class="bx bx-x"></i>
                                                        </button>
                                                    </div>
                                                    <style>
                                                        p {
                                                            margin-bottom: 8px;
                                                            }
                                                    </style>
                                                    <div class="modal-body" id="divToCopy">
                                                    <div class="alert alert-alert" role="alert" style="text-align: center; font-size: 18px;">
                                                       <div class="divider divider-success">
                                                            
                                                        <strong class="divider-text" style="font-size: 20px;">🎉 Revendedor Criado 🎉</strong>
                                                        </div>
                                                        <p>🔎 Usuario: <?php echo $_POST['usuariorevenda']; ?></p>
                                                        <p>🔑 Senha: <?php echo $_POST['senharevenda']; ?></p>
                                                        <?php
                                                        if ($_POST['credivalid'] == "Validade") {
                                                            echo "<p>🎯 Validade: " . $_POST['validaderevenda'] . " Dias</p>";
                                                        } else {
                                                           
                                                        }
                                                        ?>
                                                        <p>🕟 Limite: <?php echo $_POST['limiterevenda']; ?> </p>
                                                        <p>💥 Obrigado por usar nossos serviços!</p>
                                                        <?php
                                                        /* link painel  */
                                                        $dominio = $_SERVER['HTTP_HOST'];
                                                        echo "<p>🔗 Link do Painel: <a href='https://$dominio/'>https://$dominio/</a></p>";
                                                        ?>
                                                        <div class="divider divider-success">
                                                            <p><strong class="divider-text" style="font-size: 20px;"></strong></p>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <div class="btn-group dropup mr-1 mb-1">
                                                        <style>
                                                            button {
                                                                /* espaço entre os botoes */
                                                                margin-right: 5px;
                                                                }
                                                        </style>
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Copiar
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" onclick="copyDivToClipboard()">Copiar</a>
                                            <a class="dropdown-item" onclick="shareOnWhatsApp()">Compartilhar no Whatsapp</a>
                                            <a class="dropdown-item" onclick="copytotelegram()">Compartilhar no Telegram</a>
                                        </div>
                                        <a href="listarrevendedores.php" class="btn btn-light-secondary">
  <i class="bx bx-x d-block d-sm-none"></i>
  <span class="d-none d-sm-block">Lista de Revendedores</span>
</a>

                                        
                                    </div>
                                                        <!-- botao de copiar whatsapp  https://cdn.discordapp.com/attachments/968040569769181194/1077873044296585216/whatsapp.png-->
                                                        

<script>
function shareOnWhatsApp() {
  var text = "🎉 Revendedor Criado! 🎉\n" + 
             "🔎 Usuario: <?php echo $_SESSION['usuariofin']; ?>\n" +
             "🔑 Senha: <?php echo $_SESSION['senhafin']; ?>\n" +
             "🎯 Validade: <?php echo $_SESSION['validadefin']; ?>\n" +
             "🕟 Limite: <?php echo $_SESSION['limitefin']; ?>\n" +
             "💥 Obrigado por usar nossos serviços!\n\n" +
              '';
                                                   
             

  var encodedText = encodeURIComponent(text);
  var whatsappUrl = "https://api.whatsapp.com/send?text=" + encodedText;
  
  window.open(whatsappUrl);
}
</script>
<script>
function copytotelegram() {
    /* monoespaçado */
var text = "🎉 Revendedor Criado! 🎉\n" +
        "🔎 Usuario: <?php echo $_SESSION['usuariofin']; ?>\n" +
        "🔑 Senha: <?php echo $_SESSION['senhafin']; ?>\n" +
        "🎯 Validade: <?php echo $_SESSION['validadefin']; ?>\n" +
        "🕟 Limite: <?php echo $_SESSION['limitefin']; ?>\n" +
        "💥 Obrigado por usar nossos serviços!\n\n" +
        '" "';

    var encodedText = encodeURIComponent(text);
    var telegramUrl = "https://t.me/share/url?url=" + encodedText;

    window.open(telegramUrl);
}
</script>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>


                        </script>

                       
 
                       <script src="../../../app-assets/js/scripts/pages/bootstrap-toast.js"></script>
                       <script src="../../../app-assets/js/scripts/extensions/sweet-alerts.js"></script>
                       <script src="../app-assets/sweetalert.min.js"></script>
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

                                if (isset($_POST['submit'])) {
                                    $_POST['usuariorevenda'] = anti_sql($_POST['usuariorevenda']);
                                    $_POST['senharevenda'] = anti_sql($_POST['senharevenda']);
                                    $_POST['limiterevenda'] = anti_sql($_POST['limiterevenda']);
                                    $_POST['validaderevenda'] = anti_sql($_POST['validaderevenda']);
                                    $_POST['whatsapp'] = anti_sql($_POST['whatsapp']);
                                    $_SESSION['usuariofin'] = $_POST['usuariorevenda'];
                                    $_SESSION['senhafin'] = $_POST['senharevenda'];
                                    $_SESSION['limitefin'] = $_POST['limiterevenda'];
                                    $_SESSION['validadefin'] = $_POST['validaderevenda'];
                                    $categoria = $_POST['categoria'];
                                    $credivalid = $_POST['credivalid'];
                                    $categoria = anti_sql($categoria);
                                    $credivalid = anti_sql($credivalid);
                                    //anti sql injection
                                    //
                                    $sql7 = "SELECT * FROM accounts "; 
                                    $result7 = mysqli_query($conn, $sql7);
                                    //verifica se o usuario ja existe
                                    //verifica se os campos estão vazios
                                    if (empty($_POST['usuariorevenda']) || empty($_POST['senharevenda']) || empty($_POST['limiterevenda'])) {
                                        echo "<script>var alerta = document.getElementById('alerta'); alerta.innerHTML = '<div class=\'alert alert-danger\' role=\'alert\'>Preencha todos os campos!</div>';setTimeout(function() { $('.alert').fadeOut(); }, 2000);</script>";
                                        exit;
                                    }
                                    while($row = $result7->fetch_assoc()) {
                                        if ($row['login'] == $_POST['usuariorevenda']) {
                                            echo "<script>var alerta = document.getElementById('alerta'); alerta.innerHTML = '<div class=\'alert alert-danger\' role=\'alert\'>Revendedor ja existe!</div>';setTimeout(function() { $('.alert').fadeOut(); }, 2000);</script>";
                                            exit;
                                        }
                                    }
                                    $whatsapp = $_POST['whatsapp'];
                                    $whatsapp = anti_sql($whatsapp);
                                    $slq5 = "INSERT INTO accounts (login, senha, byid, whatsapp) VALUES ('$_POST[usuariorevenda]', '$_POST[senharevenda]', '$_SESSION[iduser]', '$whatsapp')";
                                    $result5 = mysqli_query($conn, $slq5);
                                    $sql6 = "SELECT id FROM accounts WHERE login = '$_POST[usuariorevenda]'";
                                    $result6 = mysqli_query($conn, $sql6);
                                    while($row = $result6->fetch_assoc()) {
                                        $idrevenda = $row['id'];
                                        
                                    }
                                    $usuarioreven = $_POST['usuariorevenda'];
                                    $validade = $_POST['validaderevenda'];
                                    $_POST['validaderevenda'] = $_POST['validaderevenda'] * 86400;
                                    $_POST['validaderevenda'] = $_POST['validaderevenda'] + time();
                                    $_POST['validaderevenda'] = date('Y-m-d H:i:s', $_POST['validaderevenda']);
                                    
                                    if ($credivalid == "Credito") {
                                        $valllid = "Nunca";
                                        $sql7 = "INSERT INTO atribuidos (userid, byid, limite, categoriaid, tipo) VALUES ('$idrevenda', '$_SESSION[iduser]', '$_POST[limiterevenda]', '$categoria', '$credivalid')";
                                        $result7 = mysqli_query($conn, $sql7);
                                    } else {
                                        $valllid = $validade;
                                        $sql7 = "INSERT INTO atribuidos (userid, byid, limite, expira, categoriaid, tipo) VALUES ('$idrevenda', '$_SESSION[iduser]', '$_POST[limiterevenda]', '$_POST[validaderevenda]', '$categoria', '$credivalid')";
                                        $result7 = mysqli_query($conn, $sql7);
                                    }
                                    $dominioserver = 'apiwhats.atlaspainel.com.br';
                                    $sqlwhats = "SELECT * FROM whatsapp WHERE byid = '$_SESSION[iduser]'";
                                    $resultwhats = mysqli_query($conn, $sqlwhats);
                                    $rowwhats = mysqli_fetch_assoc($resultwhats);
                                    $tokenwpp = $rowwhats['token'];
                                    $sessaowpp = $rowwhats['sessao'];
                                    $ativewpp = $rowwhats['ativo'];
  
                                    if ($tokenwpp != "" || $sessaowpp != "") {
                                        $mensagens = "SELECT * FROM mensagens WHERE ativo = 'ativada' AND funcao = 'criarrevenda'";
                                        $resultmensagens = mysqli_query($conn, $mensagens);
                                        $rowmensagens = mysqli_fetch_assoc($resultmensagens);
                                        $mensagem = $rowmensagens['mensagem'];
                                        //remove os <br> da mensagem para enviar no whatsapp
                                        if (!empty($mensagem)) {
                                        $mensagem = strip_tags($mensagem);
                                        $mensagem = str_replace("<br>", "\n", $mensagem);
                                        $mensagem = str_replace("<br><br>", "\n", $mensagem);
                                        //se a mensagem nao tiver vazia
                                        $numerowpp = $_POST['whatsapp'];
                                        //remove espaços e - do numero
                                        $numerowpp = str_replace(" ", "", $numerowpp);
                                        $numerowpp = str_replace("-", "", $numerowpp);
                                        $numerowpp = str_replace("+", "", $numerowpp);
  
                                        $dominio = $_SERVER['HTTP_HOST'];
                                        $mensagem = str_replace("{login}", $usuarioreven, $mensagem);
                                        $mensagem = str_replace("{usuario}", $usuarioreven, $mensagem);
                                        $mensagem = str_replace("{senha}", $_POST['senharevenda'], $mensagem);
                                        $mensagem = str_replace("{validade}", $valllid, $mensagem);
                                        $mensagem = str_replace("{limite}", $_POST['limiterevenda'], $mensagem);
                                        $mensagem = str_replace("{dominio}", $dominio, $mensagem);
                                        $mensagem = addslashes($mensagem);
                                        $mensagem = json_encode($mensagem);
                                        $mensagem = str_replace('"', '', $mensagem);
                                        
                                        echo "<script>
                                            var enviado = false;
                                            var phoneNumber = '{$numerowpp}';
                                            const message = '{$mensagem}';
                                        
                                            const data = {
                                                number: phoneNumber,
                                                textMessage: {
                                                  text: message
                                                },
                                                options: {
                                                  delay: 0,
                                                  presence: 'composing'
                                                }
                                              };  
                                            const urlsend = 'https://{$dominioserver}/message/sendText/$sessaowpp';
                                            const headerssend = {
                                            accept: '*/*',
                                            Authorization: 'Bearer {$tokenwpp}',
                                            'Content-Type': 'application/json'
                                            };
                                        
                                            const enviar = () => {
                                            if (!enviado) { // Verifica se a mensagem ainda não foi enviada
                                                enviado = true; // Define a variável como true para evitar novo envio
                                        
                                                $.ajax({
                                                url: urlsend,
                                                type: 'POST',
                                                data: JSON.stringify(data),
                                                headers: headerssend,
                                                success: function(response) {
                                                    console.log(response);
                                                    if (response.status == 'success') {
                                                    // Exiba uma mensagem de sucesso ou faça qualquer outra ação necessária
                                                    } else {
                                                    // Trate o erro de envio da mensagem
                                                    }
                                                },
                                                error: function(error) {
                                                    console.error('Erro ao enviar mensagem:', error);
                                                }
                                                });
                                            }
                                            };
                                            enviar();
                                        </script>";
                                    }
                                    }
                                    //timezone brasil
                                    date_default_timezone_set('America/Sao_Paulo');
                                    $datahoje = date('d-m-Y H:i:s');
                                    $sql10 = "INSERT INTO logs (revenda, validade, texto, userid) VALUES ('$_SESSION[login]', '$datahoje', 'Criou o Revendedor $usuarioreven com $validade dias', '$_SESSION[iduser]')";
                                    $result10 = mysqli_query($conn, $sql10);
                                    /* mostra modal */
                                    echo "<script>$('#criado').modal('show');</script>";
                     
                                }
                    
                            
                    ?>
