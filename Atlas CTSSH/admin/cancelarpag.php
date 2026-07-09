<?php


session_start();
include "headeradmin2.php";
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$id = $_POST["id"];
$sql_pag_cancel = "SELECT * FROM pagamentos WHERE idpagamento = '" . $id . "'";
$result_pag_cancel = $conn->query($sql_pag_cancel);
$row = $result_pag_cancel->fetch_assoc();
$access_token = $row["access_token"];
$payment_id = $row["idpagamento"];
$data = ["status" => "cancelled"];
$json_data = json_encode($data);
$headers = ["Authorization: Bearer " . $access_token, "Content-Type: application/json"];
$payment_id = $row["idpagamento"];
$options = [CURLOPT_URL => "https://api.mercadopago.com/v1/payments/" . $payment_id, CURLOPT_CUSTOMREQUEST => "PUT", CURLOPT_POSTFIELDS => $json_data, CURLOPT_HTTPHEADER => $headers, CURLOPT_RETURNTRANSFER => true];
$curl = curl_init();
curl_setopt_array($curl, $options);
$response = curl_exec($curl);
curl_close($curl);
$sql3 = "DELETE FROM pagamentos WHERE idpagamento = '" . $row["idpagamento"] . "'";
$result3 = $conn->query($sql3);

?>