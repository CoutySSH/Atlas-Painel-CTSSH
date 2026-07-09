<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
error_reporting(0);
session_start();
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$id = $_SESSION["idrevenda"];
include "header2.php";
if ($_POST["limiteedit"] < $_SESSION["soma"]) {
    echo "<script>sweetAlert('Oops...', 'Limite menor que o permitido!', 'error').then((value) => {window.location = 'editarrevenda.php?id=" . $id . "';});</script>";
    exit;
}
$sql2 = "SELECT * FROM atribuidos WHERE userid = '" . $id . "'";
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$limiteusado = $row2["limite"];
$byid = $row2["byid"];
$limiteagora2 = $row2["limite"];
$limitevaificar = $row2["limite"];
$limiteesta = $row2["limite"];
$usuarioedit = $_POST["usuarioedit"];
$senhaedit = $_POST["senhaedit"];
$validadeedit = $_POST["validadeedit"];
$validadeedit = date("Y-m-d H:i:s", strtotime("+" . $validadeedit . " days"));
$limiteedit = $_POST["limiteedit"];
$soma = $limiteedit + $_SESSION["limiteusado"];
$limiteusado = $limiteusado - $limiteedit;
$edit = $limiteusado + $_SESSION["restante"];
if ($_SESSION["tipodeconta"] == "Credito") {
    $limitevaificar = $limiteedit - $limitevaificar;
    if ($_SESSION["limite"] < $limitevaificar) {
        echo "<script>sweetAlert('Oops...', 'Limite menor que o permitido!', 'error').then((value) => {window.location = 'editarrevenda.php?id=" . $id . "';});</script>";
        exit;
    }
    $limiteagora2 = $limiteedit - $limiteagora2;
    $sql = "UPDATE atribuidos SET limite='" . $limiteedit . "' WHERE userid='" . $id . "'";
    $sql = $conn->prepare($sql);
    $sql->execute();
    $sql->close();
    $sql2 = "UPDATE accounts SET login='" . $usuarioedit . "', senha='" . $senhaedit . "' WHERE id='" . $id . "'";
    $sql2 = $conn->prepare($sql2);
    $sql2->execute();
    $sql2->close();
    $sql3 = "UPDATE atribuidos SET limite=limite-'" . $limiteagora2 . "' WHERE userid='" . $byid . "'";
    $result3 = mysqli_query($conn, $sql3);
    echo "<script>swal('Sucesso!', 'Revenda editada com sucesso!', 'success').then((value) => {window.location = 'editarrevenda.php?id=" . $id . "';});</script>";
} else {
    if ($edit < 0) {
        echo "<script>sweetAlert('Oops...', 'Limite menor que o permitido!', 'error').then((value) => {window.location = 'editarrevenda.php?id=" . $id . "';});</script>";
        unset($_SESSION["limiteedit"]);
        exit;
    }
    if (!empty($_POST["limiteedit"]) && !empty($_POST["validadeedit"]) && !empty($_POST["usuarioedit"]) && !empty($_POST["senhaedit"])) {
        $sql = "UPDATE atribuidos SET limite='" . $limiteedit . "', expira='" . $validadeedit . "' WHERE userid='" . $id . "'";
    }
    $sql = $conn->prepare($sql);
    $sql->execute();
    $sql->close();
    $sql2 = "UPDATE accounts SET login='" . $usuarioedit . "', senha='" . $senhaedit . "' WHERE id='" . $id . "'";
    $sql2 = $conn->prepare($sql2);
    $sql2->execute();
    $sql2->close();
    echo "<script>swal('Sucesso!', 'Revenda editada com sucesso!', 'success').then((value) => {window.location = 'editarrevenda.php?id=" . $id . "';});</script>";
}

?>