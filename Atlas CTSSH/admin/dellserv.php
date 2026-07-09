<?php


session_start();
if (!isset($_SESSION["login"]) || !isset($_SESSION["senha"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
if ($_SESSION["login"] != "admin") {
    session_destroy();
    header("Location: index.php");
    exit;
}
require_once "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
include "headeradmin2.php";
$id = $_POST["id"];
if (!empty($id)) {
    $sql = "DELETE FROM servidores WHERE id = '" . $id . "'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $message = "Servidor deletado com sucesso!";
        $type = "success";
    } else {
        $message = "Erro ao deletar servidor!";
        $type = "error";
    }
    echo $message;
} else {
    $message = "Não foi possível obter o ID do servidor.";
    $type = "error";
    echo $message;
}

?>