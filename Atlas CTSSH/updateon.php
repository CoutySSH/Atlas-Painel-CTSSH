<?php

include('atlas/conexao.php');

// Conexão com o banco de dados
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Zera a tabela onlines
$sql = "TRUNCATE TABLE onlines";
mysqli_query($conn, $sql);

// Lê o conteúdo do arquivo onlines.txt
function ler()
{
    $read = fopen("onlines.txt", "r");
    $onlines = fread($read, filesize("onlines.txt"));
    fclose($read);
    return $onlines;
}

// Converte os valores lidos do arquivo em um array e prepara-os para a inserção no banco de dados
$var = ler();
$var = trim($var);
$var = explode("\n", $var);

$values = array_map(function($value) use ($conn) {
    $value = trim($value);
    $value = mysqli_real_escape_string($conn, $value);
    return "('$value')";
}, $var);

$values = implode(',', $values);

// Atualiza o status dos usuários no banco de dados
$sql = "UPDATE ssh_accounts SET status = CASE WHEN login IN ($values) THEN 'Online' ELSE 'Offline' END";
mysqli_query($conn, $sql);

// Insere usuários online na tabela onlines, sem inserir usuários repetidos
$sql = "INSERT IGNORE INTO onlines (usuario) VALUES $values";
mysqli_query($conn, $sql);

$sql = "UPDATE onlines 
        INNER JOIN (
            SELECT usuario, COUNT(*) AS quantidade 
            FROM onlines 
            GROUP BY usuario
        ) AS temp
        ON onlines.usuario = temp.usuario
        SET onlines.quantidade = temp.quantidade";
mysqli_query($conn, $sql);


// Remove usuários duplicados na tabela onlines
$sql = "DELETE FROM onlines WHERE id NOT IN (SELECT * FROM (SELECT MIN(id) FROM onlines GROUP BY usuario) AS t)";
mysqli_query($conn, $sql);

// Remove o arquivo onlines.txt
unlink("onlines.txt");

?>
