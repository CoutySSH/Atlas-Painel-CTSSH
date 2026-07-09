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
include_once "headeradmin2.php";
echo "\r\n<div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui Você Pode Pegar os Links do CheckUser.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\">\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">CheckUser</h4>\r\n                                </div>\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <form class=\"form form-horizontal\" action=\"checkuserconf.php\" method=\"POST\">\r\n                                            <div class=\"form-body\">\r\n                                                <div class=\"row\">\r\n                                                    <div class=\"col-md-4\">\r\n                                                          <label>Observação</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <p class=\"card-description\">ENTRE EM SUA HOSPEDAGEM ,EM SSL DEFINA PARA NÃO FORÇAR HTTPS</code></p>\r\n                                                            <img src=\"https://cdn.discordapp.com/attachments/1051302877987086437/1070571617891131432/dddddd.png\" width=\"80%\">\r\n                                                            <br>\r\n                                                            <br>\r\n                                                            </div>\r\n\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Link AnyVpn</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input style=\"color: #ff0015;\" type=\"text\" class=\"form-control\" value=\"https://";
echo $_SERVER["SERVER_NAME"];
echo "/checkuser\" readonly>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Link Conecta4g</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input style=\"color: #ff0015;\" type=\"text\" class=\"form-control\" value=\"http://";
echo $_SERVER["SERVER_NAME"];
echo "/checkuser/conecta4g.php\" readonly>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Link ProtectionVPN</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input style=\"color: #ff0015;\" type=\"text\" class=\"form-control\" value=\"http://";
echo $_SERVER["SERVER_NAME"];
echo "/checkuser/protection.php?user=\" readonly>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Link Dtunnel</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input style=\"color: #ff0015;\" type=\"text\" class=\"form-control\" value=\"https://";
echo $_SERVER["SERVER_NAME"];
echo "/checkuser/dtunnel.php?user=\" readonly>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Link Studio / M2</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input style=\"color: #ff0015;\" type=\"text\" class=\"form-control\" value=\"/checkuser/atlant.php\" readonly>\r\n                          <img src=\"https://cdn.discordapp.com/attachments/1051302877987086437/1072314999583801456/Screenshot_1.png\" width=\"100%\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Link Miracle</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input style=\"color: #ff0015;\" type=\"text\" class=\"form-control\" value=\"https://";
echo $_SERVER["SERVER_NAME"];
echo "/checkuser\" readonly>\r\n                                                    </div>\r\n                                                    \r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Device ID</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <select class=\"form-control\" name=\"deviceativo\">\r\n                                                            ";
$sql = "SELECT * FROM configs WHERE id = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row["deviceativo"] == 1) {
    echo "<option value=\"1\">Ativado</option><option value=\"0\">Desativado</option>";
} else {
    echo "<option value=\"0\">Desativado</option><option value=\"1\">Ativado</option>";
}
echo "                                                        </select>\r\n                                                    </div>\r\n                                                        \r\n                                                        <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                        <button type=\"submit\" name=\"salvar\" class=\"btn btn-primary mr-1 mb-1\">Salvar</button>\r\n                                                        <a href=\"home.php\" class=\"btn btn-light-secondary mr-1 mb-1\">Voltar</a>\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </form>\r\n                                    </div>\r\n                                    ";
if (isset($_POST["salvar"])) {
    $deviceativo = $_POST["deviceativo"];
    $sql = "UPDATE configs SET deviceativo = '" . $deviceativo . "' WHERE id = 1";
    $result = $conn->query($sql);
    if ($result) {
        echo "<script>swal('Sucesso!', 'Configurações Atualizadas!', 'success').then((value) => {\r\n                            window.location.href = 'checkuserconf.php';\r\n                            });</script>";
    }
}
echo "                                </div>\r\n                            </div>\r\n                        </div>\r\n";

?>