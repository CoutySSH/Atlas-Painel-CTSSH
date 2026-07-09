<?php


echo "\r\n\r\n";
session_start();
error_reporting(0);
include "atlas/conexao.php";
ini_set("memory_limit", "-1");
if (!isset($_SESSION["senhaatualizar"])) {
    header("Location: index.php");
    exit;
}
$url = "#";
$zip = file_get_contents($url);
file_put_contents("atualizacao3.zip", $zip);
$zip = new ZipArchive();
$res = $zip->open("atualizacao3.zip");
if ($res === true) {
    $zip->extractTo("./");
    $zip->close();
} else {
    echo "failed";
}
unlink("atualizacao3.zip");
echo "Atualizado com sucesso!";

?>