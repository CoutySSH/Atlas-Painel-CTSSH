<?php header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
header('content-type: application/json; charset=utf-8');
ini_set('error_reporting', 1);
ob_start();
include_once '../../../atlas/conexao.php';
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
session_start();

$username = mysqli_real_escape_string($conn, $_GET['slot1']);
$password = mysqli_real_escape_string($conn, $_GET['slot2']);
$deviceId = mysqli_real_escape_string($conn, $_GET['slot3']);

$date = date('Y-m-d H:i:s');
$currentTime = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')));

$pesquisa_user = "SELECT * FROM ssh_accounts WHERE login = '$username' AND senha = '$password'";
$resultado_user = mysqli_query($conn, $pesquisa_user);
if (mysqli_num_rows($resultado_user) > 0) {
    while ($row = mysqli_fetch_assoc($resultado_user)) {
        $id_user = $row['id'];
        $limite = $row['limite'];
        $categoria = $row['categoriaid'];
        $login = $row['login'];
        $senha = $row['senha'];
        $expira = $row['expira'];
        $byid = $row['byid'];
    }
}

$pesquisa_device = "SELECT * FROM atlasdeviceid WHERE nome_user = '$login' AND deviceid = '$deviceId'";
$resultado_device = mysqli_query($conn, $pesquisa_device);
if (mysqli_num_rows($resultado_device) > 0) {
    $startDate = new DateTime($currentTime);
    $timeRemaining = $startDate->diff(new DateTime($expira));
    $months = $timeRemaining->m;
    $days = $timeRemaining->d;
    $hours = $timeRemaining->h;
    $minutes = $timeRemaining->i;
    echo "🗓$months Meses\n";
    echo "🗓$days Dias\n";
    echo "⏳$hours Horas\n";
    echo "📵Limite $limite\n";
}else{
    $cont = "SELECT COUNT(*) FROM atlasdeviceid WHERE nome_user = '$login'";
    $resultado_cont = mysqli_query($conn, $cont);
    if (mysqli_num_rows($resultado_cont) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_cont)) {
            $quantidade = $row['COUNT(*)'];
        }
    }
    if($quantidade < $limite){
        $insert = "INSERT INTO atlasdeviceid (nome_user, deviceid, byid) VALUES ('$login', '$deviceId', '$byid')";
        $resultado_insert = mysqli_query($conn, $insert);
        $startDate = new DateTime($currentTime);
        $timeRemaining = $startDate->diff(new DateTime($expira));
        $months = $timeRemaining->m;
        $days = $timeRemaining->d;
        $hours = $timeRemaining->h;
        $minutes = $timeRemaining->i;
        echo "🗓$months Meses\n";
        echo "🗓$days Dias\n";
        echo "⏳$hours Horas\n";
        echo "📵Limite $limite\n";
    }else{
        echo "Limite de dispositivos atingido";
    }
}

?>
