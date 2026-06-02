<?php if (!isset($_SESSION)){
    error_reporting(0);
session_start();

}

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
    $sql = "SELECT * FROM accounts WHERE id = '$id'";
    //consulta login e senha do id
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $login = $row['login'];
    $senha = $row['senha'];
    $byid = $row['byid'];
}
if ($byid == $_SESSION['iduser']) {
}else{
    echo "<script>sweetAlert('Oops...', 'VocÃª nÃ£o tem permissÃ£o para editar este Revendedor!', 'error').then(function(){window.location.href='../home.php'});</script>";
    unset($_POST['criaruser']);
    unset($_POST['usuariofin']);
    unset($_POST['senhafin']);
    unset($_POST['validadefin']);
    exit();
}

    $sql = "SELECT * FROM atribuidos WHERE userid = '$id'";
    //consulta login e senha do id
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $limite = $row['limite'];
    $validade = $row['expira'];
    $_SESSION['idrevenda'] = $id;


//data de validade para dias
//data de validade para dias
$validade = date('Y-m-d', strtotime($validade));
$data = date('Y-m-d');
$diferenca = strtotime($validade) - strtotime($data);
$dias = floor($diferenca / (60 * 60 * 24));

$sql = "SELECT sum(limite) AS limiteatual FROM ssh_accounts where byid='".$_SESSION['iduser']."' ";
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

$slq2 = "SELECT sum(limite) AS limiterevenda  FROM atribuidos where byid='".$_SESSION['idrevenda']."' ";
$result = $conn->prepare($slq2);
$result->execute();
$result->bind_result($limiterevenda);
$result->fetch();
$result->close();

$sql4 = "SELECT sum(limite) AS usadousuarios FROM ssh_accounts where byid='".$_SESSION['idrevenda']."' ";
$result = $conn->prepare($sql4);
$result->execute();
$result->bind_result($usadousuarios);
$result->fetch();
$result->close();


$soma = $usadousuarios + $limiterevenda;

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

$limiteusadorev = $limiteusado;
$restante = $_SESSION['limite'] - $limiteusado - $limiteatual;
$_SESSION['restante'] = $restante;

$sql5 = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[iduser]'";
$sql5 = $conn->query($sql5);
$row = $sql5->fetch_assoc();
$validade = $row['expira'];
$categoria = $row['categoriaid'];
$tipo = $row['tipo'];
$_SESSION['limite'] = $row['limite'];
$_SESSION['tipodeconta'] = $row['tipo'];
if ($tipo == 'Credito') {
$tipo = 'Restam '.$limite.' Creditos desse Revendedor';
}else{
$tipo = 'Esse revenda usou '.$soma.' Logins de '.$limite.'' ;
}

//time zone
date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d H:i:s');
if ($_SESSION['tipodeconta'] == 'Credito') {
$modo = 'Credito';
$minimo = '1';
} else {
$modo = 'Limite';
   $minimo = $soma;
   $_SESSION['soma'] = $soma;
  if ($validade < $hoje) {
    echo "<script>alert('Sua conta estÃ¡ vencida')</script>";
    echo "<script>window.location.href = '../home.php'</script>";
    unset($_POST['criaruser']);
    unset($_POST['usuariofin']);
    unset($_POST['senhafin']);
    unset($_POST['validadefin']);
  }
}
include_once '../admin/suspenderrev.php';
?>
<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <p class="text-primary">Aqui vocÃª pode Editar o Revendedor.</p>
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
                                    <h4 class="card-title"> VocÃª Esta Editando o Revendedor(a)<code> <?php echo $login?> </code></h4>
                                </div>

                                <div id="alerta">
                                </div>
                                
                                
                                <div class="card-content">
                                    
                                    <div class="card-body">
                                    <p class="card-description"><?php echo $tipo?></p>
                                        <form class="form form-horizontal" action="editarrev.php" method="POST">
                                            <div class="form-body">
                                                <div class="row">
                                                  
                                                    <div class="col-md-4">
                                                        <label>Usuario</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="usuarioedit" placeholder="Usuario" value="<?php echo $login?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Senha</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="senhaedit" placeholder="Senha" value="<?php echo $senha?>">
                                                    </div>
                                           
                                                    <div class="col-md-4">
                                                        <label><?php echo $modo?></label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="number" class="form-control" value="<?php echo $limite ?>" name="limiteedit" min="<?php echo $minimo ?>" />
                                                    </div>
                                                    <?php
                                                    if($_SESSION['tipodeconta'] == "Validade"){
                                                        echo '<div class="col-md-12 form-group">
                                                        <div id="validade">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label>Validade em Dias</label>
                                                                </div>
                                                                <div class="col-md-8">
                                                                <input type="number" class="form-control" value="'.$dias.'" min="1" max="365" name="validadeedit" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    ';
                                                    }
                                                    ?>
                                                <div class="col-12 col-md-8 offset-md-4 form-group">
                                                    <fieldset>
                                                        
                                                    </fieldset>
                                                <?php 
                       if ($_SESSION['tipodeconta'] == "Credito") {
                      } else {
                        echo "<code>Limite Restante: $restante de $_SESSION[limite]</code>";
                      }
                      ?>
                                                </div>
                
                                                <div class="col-sm-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary mr-1 mb-1" name="editarrev">Editar</button>
                                                    <a href="listarrevendedores.php" class="btn btn-light-secondary mr-1 mb-1">Cancelar</a>
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
                                                            
                                                        <strong class="divider-text" style="font-size: 20px;">ðŸŽ‰ Revendedor Criado ðŸŽ‰</strong>
                                                        </div>
                                                        <p>ðŸ”Ž Usuario: <?php echo $_POST['usuariorevenda']; ?></p>
                                                        <p>ðŸ”‘ Senha: <?php echo $_POST['senharevenda']; ?></p>
                                                        <p>ðŸŽ¯ Validade: <?php echo $_POST['validaderevenda']; ?> Dias</p>
                                                        <p>ðŸ•Ÿ Limite: <?php echo $_POST['limiterevenda']; ?> </p>
                                                        <p>ðŸ’¥ Obrigado por usar nossos serviÃ§os!</p>
                                                        <?php
                                                        /* link painel  */
                                                        $dominio = $_SERVER['HTTP_HOST'];
                                                        echo "<p>ðŸ”— Link do Painel: <a href='https://$dominio/'>https://$dominio/</a></p>";
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
                                                                /* espaÃ§o entre os botoes */
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
                                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Lista de Usuarios</span>
                                        </button>
                                        
                                    </div>
                                                        <!-- botao de copiar whatsapp  https://cdn.discordapp.com/attachments/968040569769181194/1077873044296585216/whatsapp.png-->
                                                        

<script>
function shareOnWhatsApp() {
  var text = "ðŸŽ‰ Revendedor Criado! ðŸŽ‰\n" + 
             "ðŸ”Ž Usuario: <?php echo $_SESSION['usuariofin']; ?>\n" +
             "ðŸ”‘ Senha: <?php echo $_SESSION['senhafin']; ?>\n" +
             "ðŸŽ¯ Validade: <?php echo $_SESSION['validadefin']; ?>\n" +
             "ðŸ•Ÿ Limite: <?php echo $_SESSION['limitefin']; ?>\n" +
             "ðŸ’¥ Obrigado por usar nossos serviÃ§os!\n\n" +
              '';
                                                   
             

  var encodedText = encodeURIComponent(text);
  var whatsappUrl = "https://api.whatsapp.com/send?text=" + encodedText;
  
  window.open(whatsappUrl);
}
</script>
<script>
function copytotelegram() {
    /* monoespaÃ§ado */
var text = "ðŸŽ‰ Revendedor Criado! ðŸŽ‰\n" +
        "ðŸ”Ž Usuario: <?php echo $_SESSION['usuariofin']; ?>\n" +
        "ðŸ”‘ Senha: <?php echo $_SESSION['senhafin']; ?>\n" +
        "ðŸŽ¯ Validade: <?php echo $_SESSION['validadefin']; ?>\n" +
        "ðŸ•Ÿ Limite: <?php echo $_SESSION['limitefin']; ?>\n" +
        "ðŸ’¥ Obrigado por usar nossos serviÃ§os!\n\n" +
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
                       <script src="../app-assets/sweetalert.min.js"></script>
