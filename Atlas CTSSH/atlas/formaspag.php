<?php


error_reporting(0);
session_start();
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:index.php");
}
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
include "header2.php";
$id = $_SESSION["iduser"];
$sql = "SELECT * FROM accounts WHERE id = '" . $id . "'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$nome = $row["nome"];
$email = $row["contato"];
$accesstoken = $row["accesstoken"];
$valorrevenda = $row["valorrevenda"];
$valorusuario = $row["valorusuario"];
$valordocredito = $row["mainid"];
$tokenpaghiper = $row["acesstokenpaghiper"];
$metodopag = $row["formadepag"];
$tokenapipaghiper = $row["tokenpaghiper"];
echo "                       <script src=\"../app-assets/sweetalert.min.js\"></script>\r\n <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui você pode Editar o Revendedor.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\">\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Configurações de Pagamento</h4>\r\n                                </div>\r\n\r\n                                <div id=\"alerta\">\r\n                                </div>\r\n                                \r\n                                \r\n                                <div class=\"card-content\">\r\n                                    \r\n                                    <div class=\"card-body\">\r\n                                    <p class=\"card-description\">Aqui Você Pode Editar Suas Formas De Pagamento</code></p>\r\n                                        <form class=\"form form-horizontal\" action=\"formaspag.php\" method=\"POST\">\r\n                                            <div class=\"form-body\">\r\n                                                <div class=\"row\">\r\n                                                  \r\n                                                <div class=\"col-md-4\">\r\n                                                        <label>Nome no Comprovante</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"nomepag\" placeholder=\"Nome\" value=\"";
echo $nome;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Seu Email</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"email\" class=\"form-control\" name=\"emailpag\" placeholder=\"Email\" value=\"";
echo $email;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                                <label>Metodo de Pagamento (selecione e salva)</label>\r\n                                                            </div>\r\n                                                            <div class=\"col-md-8 form-group\">\r\n                                                                <select class=\"form-control\" name=\"metodopag\">\r\n                                                                    <option value=\"1\" ";
if ($metodopag == 1) {
    echo "selected";
}
echo ">Mercado Pago</option>\r\n                                                                    <!-- <option value=\"2\" >PagHiper</option> -->\r\n                                                                </select>\r\n                                                            </div>\r\n\r\n                                                            ";
if ($metodopag == 1) {
    echo "                                                                <div class=\"col-md-4\">\r\n                                                                    <label>Token Mercado Pago</label>\r\n                                                                </div>\r\n                                                                <div class=\"col-md-8 form-group\">\r\n                                                                    <input type=\"text\" class=\"form-control\" name=\"tokenpag\" placeholder=\"Token\" value=\"";
    echo $accesstoken;
    echo "\">\r\n                                                                </div>\r\n                                                            ";
}
echo "                                                    \r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Valor do Usuario Final</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" name=\"valoruser\" placeholder=\"Valor\" value=\"";
echo $valorusuario;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Valor De 1 Usuario Para Revendedor</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" name=\"valorrev\" placeholder=\"Valor\" value=\"";
echo $valorrevenda;
echo "\">\r\n                                                    </div>\r\n                                                    \r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Valor De Cada Crédito</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" name=\"valorcredit\" placeholder=\"Token\" value=\"";
echo $valordocredito;
echo "\">\r\n                                                    </div>\r\n                                                <div class=\"col-12 col-md-8 offset-md-4 form-group\">\r\n                                                    <fieldset>\r\n                                                        \r\n                                                    </fieldset>\r\n                                                </div>\r\n                                                <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                    <button type=\"submit\" class=\"btn btn-primary mr-1 mb-1\" name=\"salvar\">Salvar</button>\r\n                                                    <a href=\"home.php\" class=\"btn btn-light-secondary mr-1 mb-1\">Cancelar</a>\r\n                                                </div>\r\n                                                </div>\r\n                                            </div>\r\n                                            \r\n                                        </form>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        ";
if (isset($_POST["salvar"])) {
    $nomepag = $_POST["nomepag"];
    $emailpag = $_POST["emailpag"];
    $tokenpag = $_POST["tokenpag"];
    $valoruser = $_POST["valoruser"];
    $valorrev = $_POST["valorrev"];
    $valorcredit = $_POST["valorcredit"];
    $minimoadd = $_POST["minimoadd"];
    $tokenpaghiperd = $_POST["tokenpaghiper"];
    $metodopag = $_POST["metodopag"];
    $tokenapicheckpaghiper = $_POST["tokenpagpaghiper"];
    date_default_timezone_set("America/Sao_Paulo");
    $datahoje = date("d-m-Y H:i:s");
    $sql10 = "INSERT INTO logs (revenda, validade, texto, userid) VALUES ('" . $_SESSION["login"] . "', '" . $datahoje . "', 'Alterou a Forma de Pagamento', '" . $_SESSION["iduser"] . "')";
    $result10 = mysqli_query($conn, $sql10);
    $sql = "UPDATE accounts SET formadepag='" . $metodopag . "' WHERE id='" . $id . "'";
    $query = mysqli_query($conn, $sql);
    $sql = "UPDATE accounts SET nome='" . $nomepag . "', contato='" . $emailpag . "', valorusuario='" . $valoruser . "', valorrevenda='" . $valorrev . "', mainid='" . $valorcredit . "', formadepag='" . $metodopag . "' , accesstoken='" . $tokenpag . "', acesstokenpaghiper='" . $tokenpaghiperd . "', tokenpaghiper='" . $tokenapicheckpaghiper . "' WHERE id='" . $id . "'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        echo "<script>swal('Sucesso!', 'Dados Alterados Com Sucesso!', 'success').then((value) => {window.location.href = 'formaspag.php'});;</script>";
    } else {
        echo "<script>alert('Erro ao Alterar Dados!');</script>";
    }
}
echo "  \r\n                     \r\n <script src=\"../app-assets/js/scripts/forms/number-input.js\"></script>\r\n                         <!--scrolling content Modal -->\r\n                       \r\n \r\n                       <script src=\"../../../app-assets/js/scripts/pages/bootstrap-toast.js\"></script>\r\n                       <script src=\"../../../app-assets/js/scripts/extensions/sweet-alerts.js\"></script>\r\n";

?>