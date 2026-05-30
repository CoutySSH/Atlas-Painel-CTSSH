    
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

include('conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}
include('header2.php');
date_default_timezone_set('America/Sao_Paulo');
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
    $sql = "SELECT * FROM atribuidos WHERE userid = '$id'";
    //consulta login e senha do id
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $expira = $row['expira'];
    
}
if ($expira < date('Y-m-d H:i:s')) {
    $expira = date('Y-m-d H:i:s');
}

$expira = date('Y-m-d H:i:s', strtotime("+30 days", strtotime($expira)));
$sql = "UPDATE atribuidos SET expira = '$expira' WHERE userid = '$id'";
$result = mysqli_query($conn, $sql);

echo "<script>swal('Sucesso!', 'Renovado com sucesso!', 'success').then((value) => {
    window.location.href = 'listarrevendedores.php';
  });</script>";

?>
