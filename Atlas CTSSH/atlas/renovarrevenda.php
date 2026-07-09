<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
if (!isset($_SESSION)) {
    error_reporting(0);
    session_start();
}
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:index.php");
}
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
include "header2.php";
date_default_timezone_set("America/Sao_Paulo");
$_GET["id"] = anti_sql($_GET["id"]);
if (!empty($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM atribuidos WHERE userid = '" . $id . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $expira = $row["expira"];
}
if ($expira < date("Y-m-d H:i:s")) {
    $expira = date("Y-m-d H:i:s");
}
$expira = date("Y-m-d H:i:s", strtotime("+30 days", strtotime($expira)));
$sql = "UPDATE atribuidos SET expira = '" . $expira . "' WHERE userid = '" . $id . "'";
$result = mysqli_query($conn, $sql);
echo "<script>swal('Sucesso!', 'Renovado com sucesso!', 'success').then((value) => {\r\n    window.location.href = 'listarrevendedores.php';\r\n  });</script>";
function anti_sql($input)
{
    $seg = preg_replace_callback("/(from|select|insert|delete|where|drop table|show tables|#|\\*|--|\\\\)/i", function ($match) {
        return "";
    }, $input);
    $seg = trim($seg);
    $seg = strip_tags($seg);
    $seg = addslashes($seg);
    return $seg;
}

?>