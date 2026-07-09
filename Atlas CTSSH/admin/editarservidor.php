<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
if (!isset($_SESSION)) {
    error_reporting(0);
    session_start();
}
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:index.php");
}
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
if ($_SESSION["login"] == "admin") {
    include "headeradmin2.php";
    $_GET["id"] = anti_sql($_GET["id"]);
    if (!empty($_GET["id"])) {
        $id = $_REQUEST["id"];
        $sql = "SELECT * FROM servidores WHERE id = '" . $id . "'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $ip = $row["ip"];
        $_SESSION["ipedit"] = $ip;
        $nome = $row["nome"];
        $porta = $row["porta"];
        $usuario = $row["usuario"];
        $senha = $row["senha"];
        $categoria = $row["subid"];
    }
    echo "\r\n<div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui você pode editar o Servidor.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\">\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Editando Servidor ";
    echo $ip;
    echo "</h4>\r\n                                </div>\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <form class=\"form form-horizontal\" action=\"editarservidor.php\" method=\"POST\">\r\n                                            <div class=\"form-body\">\r\n                                            \r\n                                                <div class=\"row\">\r\n                                                    \r\n\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Nome</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"nomeservidor\" placeholder=\"Login\" value=\"";
    echo $nome;
    echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Ip</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"ipservidor\" placeholder=\"Senha\" value=\"";
    echo $ip;
    echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Usuario</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" name=\"usuarioservidor\" value =\"";
    echo $usuario;
    echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Senha</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                    <input type=\"text\" class=\"form-control\" name=\"senhaservidor\" value =\"";
    echo $senha;
    echo "\">\r\n                                                    </div>\r\n                                            \r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Porta</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <input type=\"text\" class=\"form-control\" name=\"portaservidor\" value =\"";
    echo $porta;
    echo "\">\r\n                                                    </div>\r\n                                                    <div class=\"col-md-4\">\r\n                                                        <label>Categoria Atual ID: ";
    echo $categoria;
    echo "</label>\r\n                                                    </div>\r\n                                                    <div class=\"col-md-8 form-group\">\r\n                                                        <select class=\"form-control\" name=\"categoriaservidor\">\r\n                                                            ";
    $sql2 = "SELECT * FROM categorias WHERE subid = '" . $categoria . "'";
    $result2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($result2);
    $nomecat = $row2["nome"];
    $idcat = $row2["subid"];
    echo "<option value='" . $idcat . "'>" . $nomecat . "</option>";
    $sql = "SELECT * FROM categorias WHERE subid != '" . $categoria . "' ORDER BY nome";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $nomecat = $row["nome"];
        $idcat = $row["subid"];
        echo "<option value='" . $idcat . "'>" . $nomecat . "</option>";
    }
    echo "                                                        </select>\r\n                                                    </div>\r\n                                                    <div class=\"custom-control custom-checkbox\">\r\n                                                        <input type=\"checkbox\" class=\"custom-control-input\" id=\"customCheck1\" name=\"confirma\" value=\"6\">\r\n                                                        <label class=\"custom-control-label\" for=\"customCheck1\">Confirmar Edição</label>\r\n                                                    </div>\r\n\r\n                                                    <div class=\"col-12 col-md-8 offset-md-4 form-group\">\r\n                                                        <fieldset>\r\n                                                            \r\n                                                        </fieldset>\r\n                                                    </div>\r\n                                                    <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                        \r\n                                                        <button type=\"submit\" class=\"btn btn-primary mr-1 mb-1\" name=\"editservidor\">Editar</button>\r\n                                                        <a href=\"home.php\" class=\"btn btn-light-secondary mr-1 mb-1\">Cancelar</a>\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </form>\r\n                                    </div>\r\n                                </div>\r\n                                ";
    if (isset($_POST["editservidor"])) {
        $ipservidor = $_POST["ipservidor"];
        $nomeservidor = $_POST["nomeservidor"];
        $usuarioservidor = $_POST["usuarioservidor"];
        $senhaservidor = $_POST["senhaservidor"];
        $categoriaservidor = $_POST["categoriaservidor"];
        $portaservidor = $_POST["portaservidor"];
        $confirma = $_POST["confirma"];
        if ($confirma == 6) {
            $sql4 = "UPDATE servidores SET nome = '" . $nomeservidor . "', ip = '" . $ipservidor . "', usuario = '" . $usuarioservidor . "', senha = '" . $senhaservidor . "', porta = '" . $portaservidor . "', subid = '" . $categoriaservidor . "' WHERE ip = '" . $_SESSION["ipedit"] . "'";
            $result4 = mysqli_query($conn, $sql4);
            if ($result4) {
                echo "<script>swal('Sucesso!', 'Servidor Editado com Sucesso!', 'success').then((value) => {window.location.href = 'servidores.php';});</script>";
            } else {
                echo "<script>swal('Erro!', 'Erro ao Editar Servidor!', 'error').then((value) => {window.location.href = 'servidores.php';});</script>";
            }
        } else {
            echo "<script>swal('Erro!', 'Você não confirmou a edição!', 'error').then((value) => {window.location.href = 'servidores.php';});</script>";
        }
    }
    echo "                            </div>\r\n                        </div>\r\n                         <script src=\"../app-assets/js/scripts/forms/number-input.js\"></script>";
} else {
    header("location:../index.php");
    exit;
}
function anti_sql($input)
{
    $seg = preg_replace_callback("/(from|select|insert|delete|where|drop table|show tables|#|\\*|--|\\\\)/i", function ($match) {
        return "";
    }, $input);
    $seg = trim($seg);
    $seg = strip_tags($seg);
    $seg = addslashes($seg);
    return $seg;
}

?>