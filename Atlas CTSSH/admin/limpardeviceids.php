<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n\r\n";
session_start();
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
include "headeradmin2.php";
if ($_SESSION["login"] != "admin") {
    session_destroy();
    header("Location: index.php");
    exit;
}
$sql = "DELETE FROM atlasdeviceid";
$result = $conn->query($sql);
$sql2 = "DELETE FROM userlimiter";
$result2 = $conn->query($sql2);
echo "<script>swal('Sucesso!', 'Todos os DeviceIDs foram resetados com sucesso!', 'success');</script><script>setTimeout(\"location.href = 'listarusuarios.php';\",1500);</script>";

?>