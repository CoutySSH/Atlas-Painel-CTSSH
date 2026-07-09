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
include "../atlas/conexao.php";
include "headeradmin2.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$_GET["id"] = anti_sql($_GET["id"]);
$id = $_GET["id"];
$sql22 = "UPDATE atribuidos SET byid = '1' WHERE userid = '" . $id . "'";
$result22 = $conn->query($sql22);
$sql = "UPDATE accounts SET byid = '1' WHERE id = '" . $id . "'";
$result = $conn->query($sql);
if ($result) {
    echo "<script>sweetAlert(\"\", \"Revenda puxada com sucesso!\", \"success\").then((value) => {\r\n        window.location.href = \"listarrevendedores.php\";\r\n      });</script>";
    exit;
}
echo "<script>sweetAlert(\"\", \"Erro ao puxar revenda!\", \"error\").then((value) => {\r\n        window.location.href = \"listarrevendedores.php\";\r\n      });</script>";
exit;
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