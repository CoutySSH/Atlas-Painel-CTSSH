<?php


echo "\r\n";
session_start();
include "atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$lockfile = "lockfile2.txt";
$handle = fopen($lockfile, "w+");
if (!flock($handle, LOCK_EX | LOCK_NB)) {
    echo "Outra pessoa já está acessando a página, tente novamente mais tarde.";
    exit;
}
require_once "vendor/autoload.php";
set_time_limit(0);
ignore_user_abort(true);
set_include_path(get_include_path() . PATH_SEPARATOR . "lib2");
include "Net/SSH2.php";
date_default_timezone_set("America/Sao_Paulo");
$data = date("d-m-Y");
$sql = "DELETE FROM pagamentos WHERE status = 'Aguardando Pagamento' AND data < '" . $data . "'";
$result = $conn->query($sql);
$sql2 = "SELECT * FROM pagamentos WHERE status = 'Aguardando Pagamento'";
$result2 = $conn->query($sql2);
if (0 < $result2->num_rows) {
    while ($row2 = $result2->fetch_assoc()) {
        $idpagamentos[] = $row2;
    }
}
foreach ($idpagamentos as $idpagamento) {
    if ($idpagamento["formadepag"] == 1) {
        $url = "https://api.mercadopago.com/v1/payments/" . $idpagamento["idpagamento"];
        $token = $idpagamento["access_token"];
        $header = ["Authorization: Bearer " . $token, "Content-Type: application/json"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close($ch);
        $status = json_decode($result);
        if ($status->status == "approved") {
            $aprovado = "sim";
        } else {
            $aprovado = "nao";
        }
    } else {
        if ($idpagamento["formadepag"] == 2) {
            $transaction_id = $idpagamento["idpagamento"];
            $api_key = $idpagamento["access_token"];
            $token = $idpagamento["tokenpaghiper"];
            $data = ["transaction_id" => $transaction_id, "apiKey" => $api_key, "token" => $token];
            $json_data = json_encode($data);
            $headers = ["Content-Type: application/json", "Content-Length: " . strlen($json_data)];
            $url = "https://pix.paghiper.com/invoice/status/";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            $json = json_decode($result, true);
            curl_close($ch);
            $status = $json["status_request"]["status"];
            if ($status == "paid") {
                $aprovado = "sim";
            } else {
                $aprovado = "nao";
            }
        }
    }
    if ($aprovado == "sim") {
        $sql = "SELECT * FROM pagamentos WHERE idpagamento = '" . $idpagamento["idpagamento"] . "'";
        $result = $conn->query($sql);
        if (0 < $result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $tipo = $row["tipo"];
                $iduser = $row["iduser"];
                $addlimite = $row["addlimite"];
                $byid = $row["byid"];
            }
        }
        if ($tipo == "Renovacao Painel") {
            $sql = "SELECT * FROM atribuidos WHERE userid = " . $iduser;
            $result = $conn->query($sql);
            if (0 < $result->num_rows) {
                while ($row = $result->fetch_assoc()) {
                    $data = date("Y-m-d H:i:s");
                    if ($row["expira"] < $data) {
                        $data = date("Y-m-d H:i:s", strtotime("+30 days", strtotime($data)));
                        $sql = "UPDATE atribuidos SET expira = '" . $data . "' WHERE userid = " . $iduser;
                        $conn->query($sql);
                    } else {
                        $data = date("Y-m-d H:i:s", strtotime("+30 days", strtotime($row["expira"])));
                        $sql = "UPDATE atribuidos SET expira = '" . $data . "' WHERE userid = " . $iduser;
                        $conn->query($sql);
                    }
                }
            }
            $sql = "UPDATE pagamentos SET status = 'Aprovado' WHERE idpagamento = '" . $idpagamento["idpagamento"] . "'";
            $result = $conn->query($sql);
        }
        if ($tipo == "Adicionar Limite") {
            $sql2 = "SELECT * FROM atribuidos WHERE userid = '" . $byid . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $limite = $row2["limite"];
                }
            }
            $slq2 = "SELECT sum(limite) AS limiterevenda  FROM atribuidos where byid='" . $byid . "'";
            $result = $conn->prepare($slq2);
            $result->execute();
            $result->bind_result($limiterevenda);
            $result->fetch();
            $result->close();
            $sql4 = "SELECT * FROM ssh_accounts WHERE byid = '" . $byid . "'";
            $sql4 = $conn->prepare($sql4);
            $sql4->execute();
            $sql4->store_result();
            $num_rows = $sql4->num_rows;
            $usadousuarios = $num_rows;
            $sql55 = "SELECT * FROM atribuidos WHERE userid = '" . $byid . "'";
            $result55 = $conn->query($sql55);
            if (0 < $result55->num_rows) {
                while ($row55 = $result55->fetch_assoc()) {
                    $limite = $row55["limite"];
                }
            }
            $soma = $usadousuarios + $limiterevenda + $addlimite;
            if ($byid == "1") {
                $sql = "UPDATE atribuidos SET limite = limite + '" . $addlimite . "' WHERE userid = '" . $iduser . "'";
                $result = $conn->query($sql);
                $sql2 = "UPDATE pagamentos SET status = 'Aprovado' WHERE idpagamento = '" . $idpagamento["idpagamento"] . "'";
                $result2 = $conn->query($sql2);
            } else {
                if ($limite < $soma) {
                    $sql = "UPDATE pagamentos SET status = 'Sem Limite' WHERE idpagamento = '" . $idpagamento["idpagamento"] . "'";
                    $result = $conn->query($sql);
                } else {
                    $sql = "UPDATE atribuidos SET limite = limite + '" . $addlimite . "' WHERE userid = '" . $iduser . "'";
                    $result = $conn->query($sql);
                    $sql2 = "UPDATE pagamentos SET status = 'Aprovado' WHERE idpagamento = '" . $idpagamento["idpagamento"] . "'";
                    $result2 = $conn->query($sql2);
                }
            }
        }
        if ($tipo == "Adicionar Credito") {
            $sql2 = "SELECT * FROM atribuidos WHERE userid = '" . $byid . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $limite = $row2["limite"];
                }
            }
            if ($byid == "1") {
                $limite = "1000000000000";
            }
            if ($limite < $addlimite) {
                $sql3 = "UPDATE pagamentos SET status = 'Sem Limite' WHERE idpagamento = '" . $idpagamento["idpagamento"] . "'";
                $result3 = $conn->query($sql3);
            } else {
                $sql = "UPDATE atribuidos SET limite = limite + '" . $addlimite . "' WHERE userid = '" . $iduser . "'";
                $result = $conn->query($sql);
                $sql2 = "UPDATE atribuidos SET limite = limite - '" . $addlimite . "' WHERE userid = '" . $byid . "'";
                $result2 = $conn->query($sql2);
                $sql3 = "UPDATE pagamentos SET status = 'Aprovado' WHERE idpagamento = '" . $idpagamento["idpagamento"] . "'";
                $result3 = $conn->query($sql3);
            }
        }
        if ($tipo == "Renovacao Usuario") {
            $sql4 = "SELECT * FROM ssh_accounts WHERE id = '" . $iduser . "'";
            $result4 = $conn->query($sql4);
            if (0 < $result4->num_rows) {
                while ($row4 = $result4->fetch_assoc()) {
                    $login = $row4["login"];
                    $senha = $row4["senha"];
                    $data = $row4["expira"];
                    $limite = $row4["limite"];
                    $categoria = $row4["categoriaid"];
                    $byid = $row4["byid"];
                }
            }
            $sql = "SELECT * FROM atribuidos WHERE userid = " . $byid;
            $result = $conn->query($sql);
            if (0 < $result->num_rows) {
                while ($row = $result->fetch_assoc()) {
                    $tiporevenda = $row["tipo"];
                }
            }
            if ($data < date("Y-m-d H:i:s")) {
                $novadata = date("Y-m-d H:i:s", strtotime("+30 days", strtotime(date("Y-m-d H:i:s"))));
            } else {
                $novadata = date("Y-m-d H:i:s", strtotime("+30 days", strtotime($data)));
            }
            $validade = $novadata;
            $validade = date("Y-m-d", strtotime($validade));
            $data = date("Y-m-d");
            $diferenca = strtotime($validade) - strtotime($data);
            $dias = floor($diferenca / 86400);
            $sql3 = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
            $result = $conn->query($sql3);
            if (0 < $result->num_rows) {
                while ($row = $result->fetch_assoc()) {
                    $ssh = new Net_SSH2($row["ip"], $row["porta"]);
                    for ($tentativas = 0; !$ssh->login($row["usuario"], $row["senha"]) && $tentativas < 3; $tentativas++) {
                        sleep(1);
                    }
                    if ($tentativas == 3) {
                        $sucesso = "0";
                    } else {
                        $ssh->exec("rm -rf /etc/SSHPlus/userteste/" . $login . ".sh > /dev/null 2>&1 &");
                        $ssh->exec("./atlascreate.sh " . $login . " " . $senha . " " . $dias . " " . $limite . " ");
                        $ssh->exec("./atlasdata.sh " . $login . " " . $dias . "");
                        $ssh->disconnect();
                        $sucesso = "1";
                    }
                }
            }
            if ($sucesso == "1") {
                if ($tiporevenda == "Credito") {
                    $slqdad = "SELECT * FROM atribuidos WHERE userid = '" . $byid . "'";
                    $resultdad = $conn->query($slqdad);
                    if (0 < $resultdad->num_rows) {
                        while ($row33 = $resultdad->fetch_assoc()) {
                            $limitebyid = $row33["limite"];
                        }
                    }
                    if ($limitebyid < $limite) {
                        $sql3 = "UPDATE pagamentos SET status = 'Sem Limite' WHERE idpagamento = '" . $idpagamento["idpagamento"] . "'";
                        $result3 = $conn->query($sql3);
                    } else {
                        $sql6 = "UPDATE atribuidos SET limite = limite - " . $limite . " WHERE userid = '" . $byid . "'";
                        $result6 = $conn->query($sql6);
                    }
                }
                $sql4 = "UPDATE ssh_accounts SET expira = '" . $novadata . "' WHERE id = '" . $iduser . "'";
                $result4 = $conn->query($sql4);
                $sql5 = "UPDATE pagamentos SET status = 'Aprovado' WHERE idpagamento = '" . $idpagamento["idpagamento"] . "'";
                $result5 = $conn->query($sql5);
            }
        }
    }
}
flock($handle, LOCK_UN);
fclose($handle);
unlink($lockfile);
echo "    ";

?>