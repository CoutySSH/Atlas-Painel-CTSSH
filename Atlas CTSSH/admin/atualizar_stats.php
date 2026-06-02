<?php include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$id = $_GET['id'];
#escape string
$id = mysqli_real_escape_string($conn, $id);
$consulta = "SELECT * FROM servidores WHERE id = '$id'";
$resultado = $conn->query($consulta);
if ($resultado->num_rows === 0) {
    echo 'Servidor não encontrado';
    exit;
}
$row = $resultado->fetch_assoc();
$ip = $row['ip'];
$command = 'top -bn1 | awk \'/Cpu/ { cpu = sprintf("%.1f%%", $2 + $4) }; /KiB Mem/ { ram = sprintf("%.1f%%", ($8 / $4) * 100) }; END { print cpu " " ram }\'';
$senha = $_SESSION['token'];
$senha = md5($senha);
$headers = array(
    'Senha: ' . $senha
);
$ch = curl_init();
$url = "http://$ip:6969";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
//timeout de 4 segundos
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('comando' => $command)));
$output = curl_exec($ch);
curl_close($ch);
$partes = explode(' ', $output);
$cpu = $partes[0];
$memoria = $partes[1];
if ($cpu == '') {
    $cpu = 'Sem resposta';

}
if ($memoria == '') {
    $memoria = 'Sem resposta';
}
echo json_encode(array('cpu' => $cpu, 'memoria' => $memoria));


?>
