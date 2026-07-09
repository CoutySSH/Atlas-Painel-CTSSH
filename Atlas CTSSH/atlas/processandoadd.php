<?php


error_reporting(0);
session_start();
include "header2.php";
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
require_once "../vendor/pix/autoload.php";
date_default_timezone_set("America/Sao_Paulo");
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$sql4 = "SELECT * FROM accounts WHERE id = '" . $_SESSION["byid"] . "'";
$result4 = $conn->query($sql4);
if (0 < $result4->num_rows) {
    while ($row4 = $result4->fetch_assoc()) {
        $_SESSION["access_token"] = $row4["accesstoken"];
        $_SESSION["valorcredito"] = $row4["mainid"];
        $_SESSION["acesstokenpaghiper"] = $row4["acesstokenpaghiper"];
        $_SESSION["formadepag"] = $row4["formadepag"];
        $_SESSION["email"] = $row4["contato"];
        $_SESSION["nome"] = $row4["nome"];
        $_SESSION["tokenpaghiper"] = $row4["tokenpaghiper"];
    }
}
$sql34 = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["byid"] . "'";
$result34 = $conn->query($sql34);
if (0 < $result34->num_rows) {
    while ($row34 = $result34->fetch_assoc()) {
        $_SESSION["limite"] = $row34["limite"];
    }
}
if ($_SESSION["byid"] == 1) {
    $_SESSION["limite"] = 100000;
}
if ($_SESSION["typecont"] == "Credito" && $_SESSION["limite"] < $_POST["addquantidade"]) {
    echo "<script>alert(\"Seu Revendedor não tem Creditos suficiente!\")</script><script>window.location.href = \"adicionar.php\";</script>";
    exit;
}
$access_token = $_SESSION["access_token"];
$_SESSION["access_token"] = $access_token;
$addquantidade = $_POST["addquantidade"];
$valorlogin = $_SESSION["valorrevenda"];
$data = $_SESSION["validade"];
$data = date("d/m/Y", strtotime($data));
$data = explode("/", $data);
$data = $data[2] . "-" . $data[1] . "-" . $data[0];
$data = strtotime($data);
$hoje = strtotime(date("Y-m-d"));
$dias = floor(($data - $hoje) / 86400);
$valordia = $valorlogin / 30;
$valor1 = $valordia * $dias;
$texto = "O Usuario '" . $_SESSION["login"] . " Adicionou " . $addquantidade . " Creditos ao painel";
$valoradd = $valor1 * $addquantidade;
$valoradd = number_format($valoradd, 2, ".", "");
$_SESSION["addquantidade"] = $addquantidade;
$access_token = $_SESSION["access_token"];
if ($_SESSION["typecont"] == "Credito") {
    $valoradd = $_SESSION["addquantidade"] * $_SESSION["valorcredito"];
    $valoradd = number_format($valoradd, 2, ".", "");
    $_SESSION["valoradd"] = $valoradd;
    $_SESSION["valor"] = $valoradd;
    $tipo = "Adicionar Credito";
} else {
    $tipo = "Adicionar Limite";
    if ($valoradd < 0) {
        echo "<script>alert('Renove Primeiro')</script><script>window.location = ('pagamento.php')</script>";
        exit;
    }
}
$cupom = $_POST["cupom"];
$sql22 = "SELECT * FROM cupons WHERE cupom = '" . $cupom . "' AND byid = " . $_SESSION["byid"];
$result22 = $conn->query($sql22);
if (0 < $result22->num_rows) {
    while ($row22 = $result22->fetch_assoc()) {
        $desconto = $row22["desconto"];
        $valoradd = $valoradd - $valoradd * $desconto / 100;
        $valoradd = number_format($valoradd, 2, ".", "");
        $sql23 = "UPDATE cupons SET usado = usado + 1 WHERE cupom = '" . $cupom . "'";
        $result23 = $conn->query($sql23);
    }
}
$login = $_SESSION["login"];
if ($_SESSION["formadepag"] == 1) {
    $dt = new DateTime();
    $interval = date_interval_create_from_date_string("30 minutes");
    $dt->add($interval);
    $formatted_date = $dt->format("Y-m-d\\TH:i:s.000O");
    $_SESSION["valoradd"] = $valoradd;
    MercadoPago\SDK::setAccessToken($access_token);
    $payment = new MercadoPago\Payment();
    $payment->transaction_amount = $valoradd;
    $payment->description = "Adição de " . $addquantidade . " Logins - Usuario: " . $_SESSION["login"];
    $payment->payment_method_id = "pix";
    $payment->date_of_expiration = $formatted_date;
    $payment->payer = ["email" => "suporte@atlaspainel.com.br", "first_name" => "Venda", "last_name" => "Painel", "identification" => ["type" => "CPF", "number" => "19119119100"], "address" => ["zip_code" => "06233200", "street_name" => "Av. das Nações Unidas", "street_number" => "3003", "neighborhood" => "Bonfim", "city" => "Osasco", "federal_unit" => "SP"]];
    $payment->save();
    $_SESSION["expiracaopix"] = $payment->date_of_expiration;
    $_SESSION["payment_id"] = $payment->id;
    $_SESSION["qr_code_base64"] = $payment->point_of_interaction->transaction_data->qr_code_base64;
    $_SESSION["qr_code"] = $payment->point_of_interaction->transaction_data->qr_code;
} else {
    if ($_SESSION["formadepag"] == 2) {
        $idpedido = rand(1000000000, 0);
        $_SESSION["valoradd"] = $valoradd;
        $valor_em_centavos = round($valoradd * 100);
        if ($valor_em_centavos < 300) {
            echo "<script>alert('Valor minimo para pagamento e de R\$3,00')</script><script>window.location = ('pagamento.php')</script>";
            exit;
        }
        $data = ["apiKey" => $_SESSION["acesstokenpaghiper"], "order_id" => $idpedido, "payer_email" => $_SESSION["email"], "payer_name" => $_SESSION["nome"], "payer_cpf_cnpj" => "74293930043", "payer_phone" => "1140638785", "notification_url" => "https://mysite.com/notification/paghiper/", "shipping_methods" => "PAC", "number_ntfiscal" => $idpedido, "fixed_description" => true, "days_due_date" => "1", "items" => [["description" => "Adição de " . $addquantidade . " Logins - Usuario: " . $_SESSION["login"], "quantity" => "1", "item_id" => "1", "price_cents" => $valor_em_centavos]]];
        $data_post = json_encode($data);
        $url = "https://pix.paghiper.com/invoice/create/";
        $mediaType = "application/json";
        $charSet = "UTF-8";
        $headers = [];
        $headers[] = "Accept: " . $mediaType;
        $headers[] = "Accept-Charset: " . $charSet;
        $headers[] = "Accept-Encoding: " . $mediaType;
        $headers[] = "Content-Type: " . $mediaType . ";charset=" . $charSet;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $json = json_decode($result, true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode == 201) {
            $_SESSION["payment_id"] = $json["pix_create_request"]["transaction_id"];
            $_SESSION["qr_code_base64"] = $json["pix_create_request"]["pix_code"]["qrcode_base64"];
            $_SESSION["qr_code"] = $json["pix_create_request"]["pix_code"]["emv"];
        } else {
            echo $result;
        }
    }
}
$texto = "Adição de " . $_SESSION["addquantidade"] . " Logins - " . $_SESSION["login"];
date_default_timezone_set("America/Sao_Paulo");
$sql2 = "SELECT * FROM pagamentos WHERE iduser = '" . $_SESSION["iduser"] . "' AND status = 'Aguardando Pagamento'";
$result2 = $conn->query($sql2);
if (0 < $result2->num_rows) {
    while ($row = $result2->fetch_assoc()) {
        $access_token_cancel = $row["access_token"];
        $data = ["status" => "cancelled"];
        $json_data = json_encode($data);
        $headers = ["Authorization: Bearer " . $access_token_cancel, "Content-Type: application/json"];
        $payment_id = $row["idpagamento"];
        $options = [CURLOPT_URL => "https://api.mercadopago.com/v1/payments/" . $payment_id, CURLOPT_CUSTOMREQUEST => "PUT", CURLOPT_POSTFIELDS => $json_data, CURLOPT_HTTPHEADER => $headers, CURLOPT_RETURNTRANSFER => true];
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);
        $sql3 = "DELETE FROM pagamentos WHERE idpagamento = '" . $row["idpagamento"] . "'";
        $result3 = $conn->query($sql3);
    }
}
$data = date("d-m-Y H:i:s");
if ($_SESSION["formadepag"] == 2) {
    $access_token = $_SESSION["acesstokenpaghiper"];
}
$sql10 = "INSERT INTO pagamentos SET valor = '" . $valoradd . "', formadepag = '" . $_SESSION["formadepag"] . "', login = '" . $login . "', texto = '" . $texto . "', iduser = '" . $_SESSION["iduser"] . "', byid = '" . $_SESSION["byid"] . "', data = '" . $data . "', idpagamento = '" . $_SESSION["payment_id"] . "', status = 'Aguardando Pagamento', tipo = '" . $tipo . "', addlimite = '" . $_SESSION["addquantidade"] . "', access_token = '" . $access_token . "', tokenpaghiper = '" . $_SESSION["tokenpaghiper"] . "'";
$result10 = $conn->query($sql10);
echo "<script>window.location = ('adcpagamento.php')</script>";

?>