<?php


error_reporting(0);
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$usuario = $_GET["user"];
$deviceid = $_GET["deviceID"];
$sql = "SELECT * FROM ssh_accounts WHERE login = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$validade = $row["expira"];
$limite = $row["limite"];
$validade = date("d/m/Y", strtotime($validade));
$sql2 = "SELECT * FROM atlasdeviceid WHERE nome_user = ? AND deviceid = ?";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_bind_param($stmt2, "ss", $usuario, $deviceid);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$linhas = mysqli_num_rows($result2);
if (0 < $linhas) {
    $deviceblocked = "0";
} else {
    $cont = "SELECT COUNT(*) FROM atlasdeviceid WHERE nome_user = '" . $usuario . "'";
    $resultado_cont = mysqli_query($conn, $cont);
    $row = mysqli_fetch_assoc($resultado_cont);
    $quantidade = $row["COUNT(*)"];
    if ($limite < $quantidade) {
        $deviceblocked = "1";
    } else {
        $sql3 = "INSERT INTO atlasdeviceid (nome_user, deviceid) VALUES (?, ?)";
        $stmt3 = mysqli_prepare($conn, $sql3);
        mysqli_stmt_bind_param($stmt3, "ss", $usuario, $deviceid);
        mysqli_stmt_execute($stmt3);
        $deviceblocked = "0";
    }
}
$dados = ["validade" => $validade, "limite" => $limite, "deviceblocked" => $deviceblocked];
echo json_encode($dados);

?>