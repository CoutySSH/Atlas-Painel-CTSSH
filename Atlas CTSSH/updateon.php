<?php


include "atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$sql = "TRUNCATE TABLE onlines";
mysqli_query($conn, $sql);
$var = ler();
$var = trim($var);
$var = explode("\n", $var);
$values = array_map(function ($value) use($value) {
    $value = trim($value);
    $value = mysqli_real_escape_string($conn, $value);
    return "('" . $value . "')";
}, $var);
$values = implode(",", $values);
$sql = "UPDATE ssh_accounts SET status = CASE WHEN login IN (" . $values . ") THEN 'Online' ELSE 'Offline' END";
mysqli_query($conn, $sql);
$sql = "INSERT IGNORE INTO onlines (usuario) VALUES " . $values;
mysqli_query($conn, $sql);
$sql = "UPDATE onlines \r\n        INNER JOIN (\r\n            SELECT usuario, COUNT(*) AS quantidade \r\n            FROM onlines \r\n            GROUP BY usuario\r\n        ) AS temp\r\n        ON onlines.usuario = temp.usuario\r\n        SET onlines.quantidade = temp.quantidade";
mysqli_query($conn, $sql);
$sql = "DELETE FROM onlines WHERE id NOT IN (SELECT * FROM (SELECT MIN(id) FROM onlines GROUP BY usuario) AS t)";
mysqli_query($conn, $sql);
unlink("onlines.txt");
function ler()
{
    $read = fopen("onlines.txt", "r");
    $onlines = fread($read, filesize("onlines.txt"));
    fclose($read);
    return $onlines;
}

?>