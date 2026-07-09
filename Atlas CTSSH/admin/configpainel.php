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
$sql = "SELECT * FROM configs";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $nomepainel = $row["nomepainel"];
        $logo = $row["logo"];
        $icon = $row["icon"];
        $imagelogin = $row["imglogin"];
        $cornavsuperior = $row["cornavsuperior"];
        $corfundologo = $row["corfundologo"];
        $corcard = $row["corcard"];
        $linkapp = $row["cortextcard"];
        $tempolimiter = $row["corletranav"];
        $limiter = $row["corbarranav"];
    }
}
echo "          <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui Você Pode Editar os Detalhes do Painel.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\">\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Editar Painel</h4>\r\n                                </div>\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <form class=\"form form-horizontal\" action=\"configpainel.php\" method=\"POST\">\r\n                                            <div class=\"form-body\">\r\n                                                <div class=\"row\">\r\n                                                  \r\n\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Nome do Painel <code>(maximo: 12 caracteres)</code></label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"nomepainel\" placeholder=\"Ex: Atlas Painel\" value=\"";
echo $nomepainel;
echo "\" maxlength=\"12\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Logo Pagina de Login <code>(tamanho: 488x113)</code></label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"imagemlogo\" placeholder=\"Ex: https://i.imgur.com/1Q2w3e4.png\" value=\"";
echo $logo;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Icone Painel <code>(tamanho: 372x362)</code></label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" name=\"icon\" placeholder=\"Ex: https://i.imgur.com/1Q2w3e4.png\" value=\"";
echo $icon;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Texto Usuario Criado</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" name=\"applink\" placeholder=\"Ex: Proibido Uso de Torrent\" value=\"";
echo $linkapp;
echo "\">\r\n                                                    </div>\r\n                                                     <div class=\"col-md-4\">\r\n                                                        <label>Limiter</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                                                    ";
if ($limiter == "1") {
    echo "<select class=\"form-control\" name=\"limiter\">\r\n                                                        <option value=\"1\">Ativado</option>\r\n                                                        <option value=\"0\">Desativado</option>\r\n                                                    </select>";
} else {
    echo "<select class=\"form-control\" name=\"limiter\">\r\n                                                        <option value=\"0\">Desativado</option>\r\n                                                        <option value=\"1\">Ativado</option>\r\n                                                    </select>";
}
echo "                                                    </div> \r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Tempo em que o usuario pode usar a mais antes de ser deletado</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"tempolimiter\" placeholder=\"Ex: 10\" value=\"";
echo $tempolimiter;
echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Suspender Auto</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                                                    ";
if ($imagelogin == "1") {
    echo "<select class=\"form-control\" name=\"suspenderauto\">\r\n                                                        <option value=\"1\">Ativado</option>\r\n                                                        <option value=\"0\">Desativado</option>\r\n                                                    </select>";
} else {
    echo "<select class=\"form-control\" name=\"suspenderauto\">\r\n                                                        <option value=\"0\">Desativado</option>\r\n                                                        <option value=\"1\">Ativado</option>\r\n                                                    </select>";
}
echo "                                                    </div> \r\n                                            \r\n                                                    <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                        <button type=\"submit\" class=\"btn btn-primary mr-1 mb-1\" name=\"salvar\">Editar</button>\r\n                                                        <a href=\"home.php\" class=\"btn btn-light-secondary mr-1 mb-1\">Voltar</a>\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </form>\r\n                                    </div>\r\n                                    ";
if (isset($_POST["salvar"])) {
    $imagemlogo = $_POST["imagemlogo"];
    $icon = $_POST["icon"];
    $imglogin = $_POST["imglogin"];
    $nomepainel = $_POST["nomepainel"];
    $applink = $_POST["applink"];
    $suspenderauto = $_POST["suspenderauto"];
    $limiter = $_POST["limiter"];
    $tempolimiter = $_POST["tempolimiter"];
    $sql = "UPDATE configs SET nomepainel='" . $nomepainel . "', logo='" . $imagemlogo . "', icon='" . $icon . "', imglogin='" . $imglogin . "', corbarranav='" . $limiter . "', cortextcard='" . $applink . "', corletranav='" . $tempolimiter . "', imglogin='" . $suspenderauto . "' WHERE id='1'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>swal('Sucesso!', 'Configurações Salvas!', 'success').then((value) => {\r\n                          window.location.href = 'configpainel.php';\r\n                        });</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
echo "                                </div>\r\n                            </div>\r\n                        </div>";

?>