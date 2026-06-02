<?php error_reporting(0);
session_start();
//se a sessão não existir, redireciona para o login
if(!isset($_SESSION['login']) and !isset($_SESSION['senha'])){
    session_destroy();
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('location:index.php');
}
 
include '../atlas/conexao.php';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
include('headeradmin2.php');

$id = $_SESSION['iduser'];

$sql = "SELECT * FROM accounts WHERE id = '$id'";
//consulta login e senha do id
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$nome = $row['nome'];
$email = $row['contato'];
$accesstoken = $row['accesstoken'];
$valorrevenda = $row['valorrevenda'];
$valorusuario = $row['valorusuario'];
$valordocredito = $row['mainid'];
$tokenpaghiper = $row['acesstokenpaghiper'];
$metodopag = $row['formadepag']; 
$tokenapipaghiper = $row['tokenpaghiper'];

$sql32 = "ALTER TABLE configs ADD COLUMN IF NOT EXISTS minimocompra TEXT NOT NULL DEFAULT '1'";
$result32 = mysqli_query($conn, $sql32);

$sql = "SELECT * FROM configs WHERE id = '1'";
$result = mysqli_query($conn, $sql);    
$row = mysqli_fetch_assoc($result);
$minimocompra = $row['minimocompra'];


?>
                       <script src="../app-assets/sweetalert.min.js"></script>
 <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <p class="text-primary">Aqui você pode Editar o Revendedor.</p>
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
                                    <h4 class="card-title">Configurações de Pagamento</h4>
                                </div>

                                <div id="alerta">
                                </div>
                                
                                
                                <div class="card-content">
                                    
                                    <div class="card-body">
                                    <p class="card-description">Aqui Você Pode Editar Suas Formas De Pagamento</code></p>
                                        <form class="form form-horizontal" action="formaspag.php" method="POST">
                                            <div class="form-body">
                                                <div class="row">
                                                  
                                                    <div class="col-md-4">
                                                        <label>Nome Completo</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="nomepag" placeholder="Nome" value="<?php echo $nome?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Seu Email</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="email" class="form-control" name="emailpag" placeholder="Email" value="<?php echo $email?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                                <label>Metodo de Pagamento (selecione e salva)</label>
                                                            </div>
                                                            <div class="col-md-8 form-group">
                                                                <select class="form-control" name="metodopag">
                                                                    <option value="1" <?php if ($metodopag == 1) echo 'selected'; ?>>Mercado Pago</option>
                                                                    <!-- <option value="2" <?php /* if ($metodopag == 2) echo 'selected';  */?>>PagHiper</option> -->
                                                                </select>
                                                            </div>

                                                            <?php if ($metodopag == 1): ?>
                                                                <div class="col-md-4">
                                                                    <label>Token Mercado Pago</label>
                                                                </div>
                                                                <div class="col-md-8 form-group">
                                                                    <input type="text" class="form-control" name="tokenpag" placeholder="Token" value="<?php echo $accesstoken?>">
                                                                </div>
                                                            <?php endif; ?>
                                                    
                                                    <div class="col-md-4">
                                                        <label>Valor do Usuario Final</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="text" class="form-control" name="valoruser" placeholder="Valor" value="<?php echo $valorusuario?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Valor De 1 Usuario Para Revendedor</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="text" class="form-control" name="valorrev" placeholder="Valor" value="<?php echo $valorrevenda?>">
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <label>Valor De Cada Crédito</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="text" class="form-control" name="valorcredit" placeholder="Valor" value="<?php echo $valordocredito?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Minimo Adiçao</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="text" class="form-control" name="minimoadd" placeholder="O Minimo de Adiciona Login" value="<?php echo $minimocompra?>">
                                                    </div>
                                                <div class="col-12 col-md-8 offset-md-4 form-group">
                                                    <fieldset>
                                                        
                                                    </fieldset>
                                                </div>
                                                <div class="col-sm-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary mr-1 mb-1" name="salvar">Salvar</button>
                                                    <a href="home.php" class="btn btn-light-secondary mr-1 mb-1">Cancelar</a>
                                                </div>
                                                </div>
                                            </div>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        
if(isset($_POST['salvar'])){
    $nomepag = $_POST['nomepag'];
    $emailpag = $_POST['emailpag'];
    $tokenpag = $_POST['tokenpag'];
    $valoruser = $_POST['valoruser'];
    $valorrev = $_POST['valorrev'];
    $valorcredit = $_POST['valorcredit'];
    $minimoadd = $_POST['minimoadd'];
    $tokenpaghiperd = $_POST['tokenpaghiper'];
    $metodopag = $_POST['metodopag'];
    $tokenapicheckpaghiper = $_POST['tokenpagpaghiper'];
    //anti sql injection
    $nomepag = anti_sql($nomepag);
    $emailpag = anti_sql($emailpag);
    $tokenpag = anti_sql($tokenpag);
    $valoruser = anti_sql($valoruser);
    $valorrev = anti_sql($valorrev);
    $valorcredit = anti_sql($valorcredit);
    $minimoadd = anti_sql($minimoadd);
    $tokenpaghiperd = anti_sql($tokenpaghiperd);
    $metodopag = anti_sql($metodopag);
    $tokenapicheckpaghiper = anti_sql($tokenapicheckpaghiper);
    
    
    //time zone
    $sql_min = "UPDATE configs SET minimocompra = '$minimoadd' WHERE id = '1'";
    $result_min = $conn->query($sql_min);
    date_default_timezone_set('America/Sao_Paulo');
    $datahoje = date('d-m-Y H:i:s');
    $sql10 = "INSERT INTO logs (revenda, validade, texto, userid) VALUES ('$_SESSION[login]', '$datahoje', 'Alterou a Forma de Pagamento', '$_SESSION[iduser]')";
    $result10 = mysqli_query($conn, $sql10);
    
     $sql = "UPDATE accounts SET nome='$nomepag', contato='$emailpag', valorusuario='$valoruser', valorrevenda='$valorrev', mainid='$valorcredit', formadepag='$metodopag', acesstokenpaghiper='$tokenpaghiperd', tokenpaghiper='$tokenapicheckpaghiper', accesstoken='$tokenpag' WHERE id='$id'";
    $query = mysqli_query($conn, $sql);
    if($query){
        echo "<script>swal('Sucesso!', 'Dados Alterados Com Sucesso!', 'success').then((value) => {window.location.href='formaspag.php'});</script>";
    }else{
        echo "<script>alert('Erro ao Alterar Dados!');</script>";
    }
}

?>                      
 <script src="../app-assets/js/scripts/forms/number-input.js"></script>
                         <!--scrolling content Modal -->
                       
 
                       <script src="../../../app-assets/js/scripts/pages/bootstrap-toast.js"></script>
                       <script src="../../../app-assets/js/scripts/extensions/sweet-alerts.js"></script>
