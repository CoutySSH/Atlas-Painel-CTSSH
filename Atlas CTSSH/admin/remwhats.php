<?php include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
include('headeradmin2.php');

    //truncate table whatsapp
    $sql = "TRUNCATE TABLE whatsapp";
    if (mysqli_query($conn, $sql)) {
        echo "Tabela whatsapp limpa com sucesso!";
    } else {
        echo "Erro ao limpar tabela whatsapp: " . mysqli_error($conn);
    }


?>
