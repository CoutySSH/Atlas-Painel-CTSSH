    
<script src="../app-assets/sweetalert.min.js"></script>
<?php 
if (!isset($_SESSION)){
    error_reporting(0);
session_start();

}
//se a sessão não existir, redireciona para o login
if(!isset($_SESSION['login']) and !isset($_SESSION['senha'])){
    session_destroy();
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('location:index.php');
}
include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if ($_SESSION['login'] == 'admin') {
} else {
    header('location:../index.php');
  exit();
}

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
include('headeradmin2.php');

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
    $id = $_REQUEST['id'];
    //anti
    $id = anti_sql($id);
    $sql = "SELECT * FROM servidores WHERE id = '$id'";
    //consulta login e senha do id
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $ip = $row['ip'];
    $_SESSION['ipedit'] = $ip;
    $nome = $row['nome'];
    $porta = $row['porta'];
    $usuario = $row['usuario'];
    $senha = $row['senha'];
    $categoria = $row['subid'];
}


?>

<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <p class="text-primary">Aqui você pode editar o Servidor.</p>
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
                                    <h4 class="card-title">Editando Servidor <?php echo $ip; ?></h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-horizontal" action="editarservidor.php" method="POST">
                                            <div class="form-body">
                                            
                                                <div class="row">
                                                    

                                                    <div class="col-md-4">
                                                        <label>Nome</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="nomeservidor" placeholder="Login" value="<?php echo $nome; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Ip</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="ipservidor" placeholder="Senha" value="<?php echo $ip; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Usuario</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="text" class="form-control" name="usuarioservidor" value ="<?php echo $usuario; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Senha</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="text" class="form-control" name="senhaservidor" value ="<?php echo $senha; ?>">
                                                    </div>
                                            
                                                    <div class="col-md-4">
                                                        <label>Porta</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="portaservidor" value ="<?php echo $porta; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Categoria Atual ID: <?php echo $categoria; ?></label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <select class="form-control" name="categoriaservidor">
                                                            <?php
                                                            //mostra a categoria atual em primeiro
                                                            $sql2 = "SELECT * FROM categorias WHERE subid = '$categoria'";
                                                            $result2 = mysqli_query($conn, $sql2);
                                                            $row2 = mysqli_fetch_assoc($result2);
                                                            $nomecat = $row2['nome'];
                                                            $idcat = $row2['subid'];
                                                            echo "<option value='$idcat'>$nomecat</option>";
                                                            $sql = "SELECT * FROM categorias WHERE subid != '$categoria' ORDER BY nome";
                                                            $result = mysqli_query($conn, $sql);
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $nomecat = $row['nome'];
                                                                $idcat = $row['subid'];
                                                                echo "<option value='$idcat'>$nomecat</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="confirma" value="6">
                                                        <label class="custom-control-label" for="customCheck1">Confirmar Edição</label>
                                                    </div>

                                                    <div class="col-12 col-md-8 offset-md-4 form-group">
                                                        <fieldset>
                                                            
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-sm-12 d-flex justify-content-end">
                                                        
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1" name="editservidor">Editar</button>
                                                        <a href="home.php" class="btn btn-light-secondary mr-1 mb-1">Cancelar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php
                    if (isset($_POST['editservidor'])) {
                        $ipservidor = $_POST['ipservidor'];
                        $nomeservidor = $_POST['nomeservidor'];
                        $usuarioservidor = $_POST['usuarioservidor'];
                        $senhaservidor = $_POST['senhaservidor'];
                        $categoriaservidor = $_POST['categoriaservidor'];
                        $portaservidor = $_POST['portaservidor'];
                        $confirma = $_POST['confirma'];
                        //anti sql injection
                        $ipservidor = anti_sql($ipservidor);
                        $nomeservidor = anti_sql($nomeservidor);
                        $usuarioservidor = anti_sql($usuarioservidor);
                        $categoriaservidor = anti_sql($categoriaservidor);
                        $portaservidor = anti_sql($portaservidor);
                        $confirma = anti_sql($confirma);

                        if ($confirma == 6) {
                            $sql4 = "UPDATE servidores SET nome = '$nomeservidor', ip = '$ipservidor', usuario = '$usuarioservidor', senha = '$senhaservidor', porta = '$portaservidor', subid = '$categoriaservidor' WHERE ip = '$_SESSION[ipedit]'";
                            $result4 = mysqli_query($conn, $sql4);
                            if ($result4) {
                                echo "<script>swal('Sucesso!', 'Servidor Editado com Sucesso!', 'success').then((value) => {window.location.href = 'servidores.php';});</script>";
                            } else {
                                echo "<script>swal('Erro!', 'Erro ao Editar Servidor!', 'error').then((value) => {window.location.href = 'servidores.php';});</script>";
                            }
                        } else {
                            echo "<script>swal('Erro!', 'Você não confirmou a edição!', 'error').then((value) => {window.location.href = 'servidores.php';});</script>";
                        }
                    }



?>
                            </div>
                        </div>
                         <script src="../app-assets/js/scripts/forms/number-input.js"></script>
