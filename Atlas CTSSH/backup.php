<?php


$dominio = $_SERVER["HTTP_HOST"];
error_reporting(0);
include "atlas/conexao.php";
ini_set("display_errors", 1);
ini_set("display_startup_erros", 1);
error_reporting(32767);
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT * FROM accounts WHERE id = '1'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$token = $row["token"];
$idtelegram = $row["idtelegram"];
$sql22 = "SELECT * FROM configs WHERE id = '1'";
$result22 = mysqli_query($conn, $sql22);
$row22 = mysqli_fetch_assoc($result22);
$pasta = $row22["cornavsuperior"];
backup_tables($dbhost, $dbuser, $dbpass, $dbname);
$configfile = "admin/" . $pasta . "/configs.json";
require_once "vendor/autoload.php";
$telegram = new Telegram\Bot\Api($token);
$telegram->sendDocument(["chat_id" => $idtelegram, "document" => fopen("atlasbackup.sql", "r"), "caption" => "Backup do banco de dados"]);
$telegram->sendDocument(["chat_id" => $idtelegram, "document" => fopen($configfile, "r"), "caption" => "Backup do arquivo de configuração AnyVpnMod"]);
unlink("atlasbackup.sql");
function backup_tables($host, $user, $pass, $name)
{
    $link = mysqli_connect($host, $user, $pass);
    mysqli_select_db($link, $name);
    $tables = [];
    $result = mysqli_query($link, "SHOW TABLES");
    for ($i = 0; $row = mysqli_fetch_row($result); $i++) {
        $tables[$i] = $row[0];
    }
    $return = "";
    foreach ($tables as $table) {
        $result = mysqli_query($link, "SELECT * FROM " . $table);
        $num_fields = mysqli_num_fields($result);
        $return .= "DROP TABLE IF EXISTS " . $table . ";";
        $row2 = mysqli_fetch_row(mysqli_query($link, "SHOW CREATE TABLE " . $table));
        $return .= "\n\n" . $row2[1] . ";\n\n";
        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysqli_fetch_row($result)) {
                $return .= "INSERT INTO " . $table . " VALUES(";
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    if (isset($row[$j])) {
                        $return .= "\"" . $row[$j] . "\"";
                    } else {
                        $return .= "\"\"";
                    }
                    if ($j < $num_fields - 1) {
                        $return .= ",";
                    }
                }
                $return .= ");\n";
            }
        }
        $return .= "\n\n\n";
    }
    $handle = fopen("atlasbackup.sql", "w+");
    fwrite($handle, $return);
    fclose($handle);
}

?>