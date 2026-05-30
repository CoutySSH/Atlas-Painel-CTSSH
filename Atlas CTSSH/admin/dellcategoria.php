    
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
include 'headeradmin2.php';
include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}
if ($_SESSION['login'] == 'admin') {
}else{
  echo "<script>alert('Você não tem permissão para acessar essa página!');window.location.href='../logout.php';</script>";
  exit();
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
    $categoria = $_GET['id'];
    
}

$sql = "DELETE FROM categorias WHERE id = '$categoria'";
$result = mysqli_query($conn, $sql);
if ($result) {
    echo "<script>swal('Categoria excluída com sucesso!', '', 'success').then(function(){window.location.href='categorias.php';});</script>";
} else {
    echo "<script>swal('Erro ao excluir categoria!', '', 'error').then(function(){window.location.href='categorias.php';});</script>";
}

?>
