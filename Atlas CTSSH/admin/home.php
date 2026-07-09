<?php


echo "\r\n";
error_reporting(0);
session_start();
if (isset($_SESSION["last_activity"]) && 1200 < time() - $_SESSION["last_activity"]) {
    echo "<script>alert('Sessão expirada por inatividade!');</script>";
    session_unset();
    session_destroy();
    echo "<script>setTimeout(function(){ window.location.href='../index.php'; }, 500);</script>";
    exit;
}
$_SESSION["last_activity"] = time();
$_SESSION["last_activity"] = time();
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:index.php");
}
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$login = $_SESSION["login"];
$senha = $_SESSION["senha"];
$sql4 = "SELECT * FROM accounts WHERE login = '" . $login . "' AND senha = '" . $senha . "'";
$result4 = $conn->query($sql4);
if (0 < $result4->num_rows) {
    while ($row4 = $result4->fetch_assoc()) {
        $_SESSION["iduser"] = $row4["id"];
        $_SESSION["byid"] = $row4["byid"];
    }
}
$sql = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["iduser"] . "'";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $_SESSION["validade"] = $row["expira"];
        $_SESSION["limite"] = $row["limite"];
        $_SESSION["tipo"] = $row["tipo"];
    }
}
include "headeradmin.php";
$sql = "SELECT * FROM atribuidos WHERE tipo = ''";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $contas[] = $row;
    }
}
foreach ($contas as $conta) {
    $sql = "UPDATE atribuidos SET tipo = 'Validade' WHERE userid = '" . $conta["userid"] . "'";
    $result = $conn->query($sql);
}
$create = "CREATE TABLE IF NOT EXISTS `bot` (\r\n    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,\r\n    `app` text DEFAULT NULL,\r\n    `sender` text DEFAULT NULL,\r\n    `message` text DEFAULT NULL,\r\n    `data` text DEFAULT NULL,\r\n    `idpagamento` text DEFAULT NULL,\r\n    `access_token` text DEFAULT NULL,\r\n    `quantidadeuser` text DEFAULT NULL,\r\n    `status` text DEFAULT NULL,\r\n    PRIMARY KEY (`id`)\r\n  ) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
$conn->query($create);
$sqltoken = "ALTER TABLE accounts ADD COLUMN IF NOT EXISTS tokenvenda TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken);
$sqltoken = "ALTER TABLE accounts\r\n    ADD COLUMN IF NOT EXISTS acesstokenpaghiper TEXT DEFAULT NULL,\r\n    ADD COLUMN IF NOT EXISTS formadepag TEXT DEFAULT NULL,\r\n    ADD COLUMN IF NOT EXISTS tokenpaghiper TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken);
$sqltoken44 = "ALTER TABLE pagamentos\r\nADD COLUMN IF NOT EXISTS formadepag TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken44);
$sqltoken55 = "ALTER TABLE pagamentos\r\nADD COLUMN IF NOT EXISTS tokenpaghiper TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken55);
$sqltoken55 = "ALTER TABLE accounts\r\nADD COLUMN IF NOT EXISTS whatsapp TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken55);
$sqltoken66 = "ALTER TABLE atribuidos ADD COLUMN IF NOT EXISTS notificado TEXT DEFAULT 'nao'";
mysqli_query($conn, $sqltoken66);
$sqltoken77 = "ALTER TABLE ssh_accounts ADD COLUMN IF NOT EXISTS notificado TEXT DEFAULT 'nao'";
mysqli_query($conn, $sqltoken77);
$sqltoken77 = "ALTER TABLE ssh_accounts ADD COLUMN IF NOT EXISTS whatsapp TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken77);
$sqlMensagens = "CREATE TABLE IF NOT EXISTS `mensagens` (\r\n    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,\r\n    `funcao` text DEFAULT NULL,\r\n    `mensagem` text DEFAULT NULL,\r\n    `ativo` text DEFAULT NULL,\r\n    `hora` text DEFAULT NULL,\r\n    PRIMARY KEY (`id`)\r\n  ) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
mysqli_query($conn, $sqlMensagens);
$sqlWhatsapp = "CREATE TABLE IF NOT EXISTS `whatsapp` (\r\n    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,\r\n    `token` text DEFAULT NULL,\r\n    `sessao` text DEFAULT NULL,\r\n    `ativo` text DEFAULT '1',\r\n    PRIMARY KEY (`id`)\r\n  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
mysqli_query($conn, $sqlWhatsapp);
$sqltoken3 = "ALTER TABLE atribuidos\r\nADD COLUMN IF NOT EXISTS valormensal TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken3);
$sqltoken4 = "ALTER TABLE ssh_accounts\r\nADD COLUMN IF NOT EXISTS valormensal TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken4);
$slqtokenvenda = "ALTER TABLE accounts ADD COLUMN IF NOT EXISTS tokenvenda TEXT DEFAULT NULL";
mysqli_query($conn, $slqtokenvenda);
mysqli_query($conn, $sqltoken);

?>