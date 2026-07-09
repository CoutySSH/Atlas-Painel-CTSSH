<?php


$time = rand(1, 10);
sleep($time);
ignore_user_abort(true);
set_time_limit(0);
session_start();
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
date_default_timezone_set("America/Sao_Paulo");
$dominioserver = "apiwhats.atlaspainel.com.br";
$sqlwhats = "SELECT * FROM whatsapp WHERE ativo = '1'";
$resultwhats = mysqli_query($conn, $sqlwhats);
$rowwhats = mysqli_fetch_assoc($resultwhats);
$tokenwpp = $rowwhats["token"];
$sessaowpp = $rowwhats["sessao"];
$ativewpp = $rowwhats["ativo"];
$dataset = date("Y-m-d H:i:s", strtotime("+2 day"));
$remove = "UPDATE atribuidos SET notificado = 'nao' WHERE expira > '" . $dataset . "'";
$result = $conn->query($remove);
$remove2 = "UPDATE ssh_accounts SET notificado = 'nao' WHERE expira > '" . $dataset . "'";
$result2 = $conn->query($remove2);
if ($ativewpp == "1") {
    $datainter = date("Y-m-d", strtotime("+1 day"));
    $sqlnotify = "SELECT atribuidos.expira, atribuidos.limite, accounts.login, accounts.senha, accounts.whatsapp, atribuidos.userid\r\n        FROM atribuidos\r\n        INNER JOIN accounts ON atribuidos.userid = accounts.id\r\n        WHERE DATE(atribuidos.expira) = '" . $datainter . "' \r\n        AND atribuidos.notificado = 'nao'\r\n        AND accounts.login <> 'admin'\r\n        AND atribuidos.byid = 1";
    $resultnotify = $conn->query($sqlnotify);
    $sqlnotifymsn = "SELECT * FROM mensagens WHERE funcao = 'revendaexpirada' AND ativo = 'ativada'";
    $resultnotifymsn = $conn->query($sqlnotifymsn);
    $rowmsn = $resultnotifymsn->fetch_assoc();
    if (0 < $resultnotify->num_rows) {
        while ($row = $resultnotify->fetch_assoc()) {
            if ($row["notificado"] == "nao") {
                $numerowpp = $row["whatsapp"];
                $mensagem = $rowmsn["mensagem"];
                $horaEnvio = $rowmsn["hora"];
                $horaAtual = date("H:i");
                if ($horaEnvio < $horaAtual && !empty($mensagem) && !empty($numerowpp)) {
                    $mensagem = strip_tags($mensagem);
                    $mensagem = str_replace("<br>", "\n", $mensagem);
                    $mensagem = str_replace("<br><br>", "\n", $mensagem);
                    $expira = $row["expira"];
                    $expira_formatada = date("d/m/Y", strtotime($expira));
                    $row["expira"] = $expira_formatada;
                    $mensagem = str_replace("{login}", $row["login"], $mensagem);
                    $mensagem = str_replace("{usuario}", $row["login"], $mensagem);
                    $mensagem = str_replace("{senha}", $row["senha"], $mensagem);
                    $mensagem = str_replace("{validade}", $row["expira"], $mensagem);
                    $mensagem = str_replace("{limite}", $row["limite"], $mensagem);
                    $mensagem = str_replace("{dominio}", $_SERVER["HTTP_HOST"], $mensagem);
                    $numerowpp = $row["whatsapp"];
                    $mensagem = addslashes($mensagem);
                    $urlsend = "https://" . $dominioserver . "/api/" . $sessaowpp . "/send-message";
                    $headerssend = ["accept: */*", "Authorization: Bearer " . $tokenwpp, "Content-Type: application/json"];
                    $data = ["phone" => $numerowpp, "isGroup" => false, "message" => $mensagem];
                    $ch = curl_init($urlsend);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerssend);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    if ($response) {
                        $response = json_decode($response, true);
                        if ($response["status"] == "success") {
                            $enviadosql = "UPDATE atribuidos SET notificado = 'sim' WHERE userid = '" . $row["userid"] . "'";
                            if (mysqli_query($conn, $enviadosql)) {
                                echo "Notificação enviada com sucesso!";
                            } else {
                                echo "Erro ao atualizar tabela atribuidos: " . mysqli_error($conn);
                            }
                        } else {
                            $enviadosql = "UPDATE atribuidos SET notificado = 'sim' WHERE userid = '" . $row["userid"] . "'";
                            if (mysqli_query($conn, $enviadosql)) {
                                echo "Notificação enviada com sucesso!";
                            } else {
                                echo "Erro ao atualizar tabela atribuidos: " . mysqli_error($conn);
                            }
                            $enviadosql = "UPDATE accounts SET whatsapp = '' WHERE id = '" . $row["userid"] . "'";
                            if (mysqli_query($conn, $enviadosql)) {
                                echo "Notificação enviada com sucesso!";
                            } else {
                                echo "Erro ao atualizar tabela atribuidos: " . mysqli_error($conn);
                            }
                        }
                    }
                }
            }
        }
    }
    $datainterssh = date("Y-m-d", strtotime("+1 day"));
    $notifyuser = "SELECT * FROM ssh_accounts WHERE DATE(expira) = '" . $datainterssh . "' AND notificado = 'nao' AND byid = 1";
    $resultnotifyuser = $conn->query($notifyuser);
    if (0 < $resultnotifyuser->num_rows) {
        while ($rowuser = $resultnotifyuser->fetch_assoc()) {
            $sqlnotifymsnuser = "SELECT * FROM mensagens WHERE funcao = 'contaexpirada' AND ativo = 'ativada'";
            $resultnotifymsnuser = $conn->query($sqlnotifymsnuser);
            if ($rowuser["notificado"] == "nao") {
                $rowmsnuser = $resultnotifymsnuser->fetch_assoc();
                $numerowppuser = $rowuser["whatsapp"];
                $mensagemuser = $rowmsnuser["mensagem"];
                $horaEnvio = $rowmsnuser["hora"];
                $horaAtual = date("H:i");
                if ($horaEnvio < $horaAtual && !empty($mensagemuser) && !empty($numerowppuser)) {
                    $mensagemuser = strip_tags($mensagemuser);
                    $mensagemuser = str_replace("<br>", "\n", $mensagemuser);
                    $mensagemuser = str_replace("<br><br>", "\n", $mensagemuser);
                    $expira = $rowuser["expira"];
                    $expira_formatada = date("d/m/Y", strtotime($expira));
                    $rowuser["expira"] = $expira_formatada;
                    $mensagemuser = str_replace("{login}", $rowuser["login"], $mensagemuser);
                    $mensagemuser = str_replace("{usuario}", $rowuser["login"], $mensagemuser);
                    $mensagemuser = str_replace("{senha}", $rowuser["senha"], $mensagemuser);
                    $mensagemuser = str_replace("{validade}", $rowuser["expira"], $mensagemuser);
                    $mensagemuser = str_replace("{limite}", $rowuser["limite"], $mensagemuser);
                    $mensagemuser = str_replace("{dominio}", $_SERVER["HTTP_HOST"], $mensagemuser);
                    $numerowppuser = $rowuser["whatsapp"];
                    $mensagemuser = addslashes($mensagemuser);
                    $urlsend = "https://" . $dominioserver . "/api/" . $sessaowpp . "/send-message";
                    $headerssend = ["accept: */*", "Authorization: Bearer " . $tokenwpp, "Content-Type: application/json"];
                    $data = ["phone" => $numerowppuser, "isGroup" => false, "message" => $mensagemuser];
                    $ch = curl_init($urlsend);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerssend);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    if ($response) {
                        $response = json_decode($response, true);
                        if ($response["status"] == "success") {
                            $enviadosql = "UPDATE ssh_accounts SET notificado = 'sim' WHERE id = '" . $rowuser["id"] . "'";
                            if (mysqli_query($conn, $enviadosql)) {
                                echo "Notificação enviada com sucesso!";
                            } else {
                                echo "Erro ao atualizar tabela ssh_accounts: " . mysqli_error($conn);
                            }
                        } else {
                            $enviadosql = "UPDATE ssh_accounts SET whatsapp = '' WHERE id = '" . $rowuser["id"] . "'";
                            if (mysqli_query($conn, $enviadosql)) {
                                echo "Notificação enviada com sucesso!";
                            } else {
                                echo "Erro ao atualizar tabela ssh_accounts: " . mysqli_error($conn);
                            }
                        }
                    }
                }
            }
        }
    }
}
echo "                            ";

?>