<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
error_reporting(0);
session_start();
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:../index.php");
}
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$id = $_SESSION["iduser"];
include_once "headeradmin2.php";
$sql = "SELECT idtelegram FROM accounts WHERE id = '" . $id . "'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE accounts ADD idtelegram TEXT";
    $result = $conn->query($sql);
    $sql2 = "ALTER TABLE accounts ADD tempo TEXT";
    $result2 = $conn->query($sql2);
}
$sql = "SELECT * FROM accounts WHERE id = '" . $id . "'";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $login = $row["login"];
        $senha = $row["senha"];
        $tempotest = $row["mb"];
        $bottoken = $row["token"];
        $idtelegram = $row["idtelegram"];
    }
}
echo "<div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <!-- users edit start -->\r\n                <section class=\"users-edit\">\r\n                    <div class=\"card\">\r\n                        <div class=\"card-content\">\r\n                            <div class=\"card-body\">\r\n                                <ul class=\"nav nav-tabs mb-2\" role=\"tablist\">\r\n                                    <li class=\"nav-item\">\r\n                                        <h5>Editar Conta</h5>\r\n                                    </li>\r\n                                </ul>\r\n                                <div class=\"tab-content\">\r\n                                    <div class=\"tab-pane active fade show\" id=\"account\" aria-labelledby=\"account-tab\" role=\"tabpanel\">\r\n                                        <form action=\"editconta.php\" method=\"POST\">\r\n                                            <div class=\"row\">\r\n                                                <div class=\"col-12 col-sm-6\">\r\n                                                    <div class=\"form-group\">\r\n                                                        <div class=\"controls\">\r\n                                                            <label>Senha</label>\r\n                                                            <input type=\"text\" class=\"form-control\" name=\"senhaup\" value=\"";
echo $senha;
echo "\" required >\r\n                                                        </div>\r\n                                                    </div>\r\n                                                    <div class=\"form-group\">\r\n                                                        <div class=\"controls\">\r\n                                                            <label>Limite Tempo de Teste em Minutos</label>\r\n                                                            <input type=\"text\" class=\"form-control\" name=\"limitetest\" value=\"";
echo $tempotest;
echo "\" required>\r\n                                                        </div>\r\n                                                    </div>\r\n                                                    <div class=\"form-group\">\r\n                                                        <div class=\"controls\">\r\n                                                            <label>Token Bot Telegram</label>\r\n                                                            <input type=\"text\" class=\"form-control\" name=\"tokenbot\" value=\"";
echo $bottoken;
echo "\" required>\r\n                                                        </div>\r\n                                                    </div>\r\n                                                    <div class=\"form-group\">\r\n                                                        <div class=\"controls\">\r\n                                                            <label>Seu ID Telegram</label>\r\n                                                            <input type=\"text\" name=\"idtelegram\" class=\"form-control\" value=\"";
echo $idtelegram;
echo "\" required>\r\n                                                        </div>\r\n                                                    </div>\r\n                                                    <!-- botoes de salvar -->\r\n                                                    <button type=\"submit\" name=\"mudar\" class=\"btn btn-primary mr-2\">Salvar</button>\r\n                                                    <a href=\"home.php\" class=\"btn btn-outline-secondary\">Cancelar</a>\r\n                                                </div>\r\n                                    </div>\r\n                                    \r\n                                    </div>\r\n                                    ";
if (isset($_POST["mudar"])) {
    $senhaup = $_POST["senhaup"];
    $limitetest = $_POST["limitetest"];
    $bottoken = $_POST["tokenbot"];
    $idtelegram = $_POST["idtelegram"];
    $sql = "UPDATE accounts SET senha='" . $senhaup . "', mb='" . $limitetest . "', token='" . $bottoken . "', idtelegram='" . $idtelegram . "' WHERE id='" . $id . "'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>swal('Sucesso!', 'Acesse seu Bot e de o Comando /start Para começar receber os backups!', 'success').then((value) => {window.location.href = 'home.php';});</script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
echo "                                </div>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                </section>\r\n            </div>\r\n        </div>\r\n    </div>";

?>