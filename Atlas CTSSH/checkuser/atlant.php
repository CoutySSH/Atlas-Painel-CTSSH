<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
header("content-type: application/json; charset=utf-8");
ini_set("error_reporting", 1);
ob_start();
include "../atlas/conexao.php";
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
session_start();
$getRequest = $_GET["request"];
$userid = $_GET["slot1"];
$device = $_GET["slot3"];
$passw = $_GET["slot2"];
$date = date("Y-m-d H:i:s");
$data = json_decode(file_get_contents("php://input"), true);
$ip = $data["ip"];
$username = $data["user"];
$password = $data["password"];
$deviceId = $data["deviceid"];
$currentTime = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
$pesquisa_user = "SELECT * FROM ssh_accounts WHERE login = '" . $username . "' AND senha = '" . $password . "'";
$resultado_user = mysqli_query($conn, $pesquisa_user);
if (0 < mysqli_num_rows($resultado_user)) {
    while ($row = mysqli_fetch_assoc($resultado_user)) {
        $id_user = $row["id"];
        $limite = $row["limite"];
        $categoria = $row["categoriaid"];
        $login = $row["login"];
        $senha = $row["senha"];
        $expira = $row["expira"];
        $byid = $row["byid"];
    }
}
$pesquisa_device = "SELECT * FROM atlasdeviceid WHERE nome_user = '" . $login . "' AND deviceid = '" . $deviceId . "'";
$resultado_device = mysqli_query($conn, $pesquisa_device);
if (0 < mysqli_num_rows($resultado_device)) {
    $startDate = new DateTime($currentTime);
    $timeRemaining = $startDate->diff(new DateTime($expira));
    $months = $timeRemaining->m;
    $days = $timeRemaining->d;
    $hours = $timeRemaining->h;
    $minutes = $timeRemaining->i;
    $response = ["Status" => "searched", "Days" => (int) $days, "Hours" => (int) $hours, "Minutes" => (int) $minutes, "Months" => (int) $months, "Limit" => (int) $limite];
    echo json_encode($response);
} else {
    $cont = "SELECT COUNT(*) FROM atlasdeviceid WHERE nome_user = '" . $login . "'";
    $resultado_cont = mysqli_query($conn, $cont);
    if (0 < mysqli_num_rows($resultado_cont)) {
        while ($row = mysqli_fetch_assoc($resultado_cont)) {
            $quantidade = $row["COUNT(*)"];
        }
    }
    if ($quantidade < $limite) {
        $insert = "INSERT INTO atlasdeviceid (nome_user, deviceid, byid) VALUES ('" . $login . "', '" . $deviceId . "', '" . $byid . "')";
        $resultado_insert = mysqli_query($conn, $insert);
        $startDate = new DateTime($currentTime);
        $timeRemaining = $startDate->diff(new DateTime($expira));
        $months = $timeRemaining->m;
        $days = $timeRemaining->d;
        $hours = $timeRemaining->h;
        $minutes = $timeRemaining->i;
        $response = ["Status" => "searched", "Days" => (int) $days, "Hours" => (int) $hours, "Minutes" => (int) $minutes, "Months" => (int) $months, "Limit" => (int) $limite];
        echo json_encode($response);
    } else {
        $response = ["Status" => "blockdevice"];
        echo json_encode($response);
    }
}

?>