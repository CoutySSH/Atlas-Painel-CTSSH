<?php
error_reporting(0);
session_start();
include_once("../atlas/conexao.php");
date_default_timezone_set('America/Sao_Paulo');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if ($_SESSION['aproved'] == true) {
    echo "Aprovado";
    exit;
}
$_SESSION['aproved'] = false;
$access_token = $_SESSION['tokenaccess'];
$byid = $_SESSION['byid'];
//se byid nao for igual a 1
if ($byid != 1) {
  $sql = "SELECT * FROM atribuidos WHERE userid = '$byid'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $categoriaid = $row['categoriaid'];
}else{
  $sql = "SELECT * FROM accounts WHERE id = '$byid'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $categoriaid = $row['tempo'];
}
$login = $_SESSION['usuario'];  
$senha = $_SESSION['senha'];
if ($_SESSION['formadepag'] == '1') {
                      if (isset($_SESSION['payment_id'])) {
                        $url = "https://api.mercadopago.com/v1/payments/" . $_SESSION['payment_id'];
                        $token = $access_token;
                        $header = array(
                          'Authorization: Bearer ' . $token,
                          'Content-Type: application/json'
                        );
                        set_time_limit(0); // Limite de tempo de execução: 2h. Deixe 0 (zero) para sem limite
                        ignore_user_abort(true);
          
          
                        $ch = curl_init();
          
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_ENCODING, '');
                        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                        $result = curl_exec($ch);
          
                        curl_close($ch);
                        $status = json_decode($result);
                         if ($status->status == "approved") {
                            echo 'Aprovado';
                        $expira = date('Y-m-d H:i:s', strtotime('+30 days'));
                          //verifica login senha byid se nao existirem nao cria
                          if ($login != '' && $senha != '' && $byid != '') {
                            $create = "INSERT INTO accounts (login, senha, byid ) VALUES ('$login', '$senha', '$byid')";
                              $result = mysqli_query($conn, $create);
                              if ($result) {
                                $_SESSION['sucess'] = true;
                              }
                              $consultaid = "SELECT id FROM accounts WHERE login = '$login' AND senha = '$senha'";
                              $result = mysqli_query($conn, $consultaid);
                              $row = mysqli_fetch_assoc($result);
                              $id = $row['id'];
                              $updatpag = "UPDATE pagamentos SET status = 'Aprovado', iduser = '$id' WHERE idpagamento = '$_SESSION[payment_id]'";
                                $result = mysqli_query($conn, $updatpag);
                              $createatribuidos = "INSERT INTO atribuidos (categoriaid, userid, byid, limite, tipo, expira) VALUES ('$categoriaid', '$id', '$byid', '$_SESSION[plano]', 'Validade', '$expira')";
                              $result = mysqli_query($conn, $createatribuidos);
                              if ($result) {
                                $_SESSION['sucess'] = true;
                              }
                              if ($_SESSION['sucess'] == true) {
                                  $_SESSION['aproved'] = true;
                              }
                            }
                        }else{
                          echo 'Pendente';
                        }
                      }
                    }elseif ($_SESSION['formadepag'] == '2') {
                      //paghiper
                      $transaction_id = $_SESSION['payment_id'];
                      $api_key = $_SESSION['acesstokenpaghiper'];
                      $token = $_SESSION['tokenpaghiper'];
                      $data = array(
                          'transaction_id' => $transaction_id,
                          'apiKey' => $api_key,
                          'token' => $token
                      );
                      $json_data = json_encode($data);
                      $headers = array(
                          'Content-Type: application/json',
                          'Content-Length: ' . strlen($json_data)
                      );
                      $url = 'https://pix.paghiper.com/invoice/status/';
                      $ch = curl_init($url);
                      curl_setopt($ch, CURLOPT_POST, true);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                      $result = curl_exec($ch);
                      $json = json_decode($result, true);
                      curl_close($ch);
                      $status = $json['status_request']['status'];
                      if ($status == "paid") { 
                          $aprovado = 'sim'; 
                          echo 'Aprovado';
                          $expira = date('Y-m-d H:i:s', strtotime('+30 days'));
                            //verifica login senha byid se nao existirem nao cria
                            if ($login != '' && $senha != '' && $byid != '') {
                              $create = "INSERT INTO accounts (login, senha, byid ) VALUES ('$login', '$senha', '$byid')";
                                $result = mysqli_query($conn, $create);
                                if ($result) {
                                  $_SESSION['sucess'] = true;
                                }
                                $consultaid = "SELECT id FROM accounts WHERE login = '$login' AND senha = '$senha'";
                                $result = mysqli_query($conn, $consultaid);
                                $row = mysqli_fetch_assoc($result);
                                $id = $row['id'];
                                $updatpag = "UPDATE pagamentos SET status = 'Aprovado', iduser = '$id' WHERE idpagamento = '$_SESSION[payment_id]'";
                                  $result = mysqli_query($conn, $updatpag);
                                $createatribuidos = "INSERT INTO atribuidos (categoriaid, userid, byid, limite, tipo, expira) VALUES ('$categoriaid', '$id', '$byid', '$_SESSION[plano]', 'Validade', '$expira')";
                                $result = mysqli_query($conn, $createatribuidos);
                                if ($result) {
                                  $_SESSION['sucess'] = true;
                                }
                                if ($_SESSION['sucess'] == true) {
                                    $_SESSION['aproved'] = true;
                                }
                              } 
                      }else{
                        echo 'Pendente';
                      }
                    }
          ?>
