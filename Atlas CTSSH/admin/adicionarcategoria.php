<?php


error_reporting(0);
session_start();
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
include "headeradmin2.php";
echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n<div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <h4 class=\"card-title\">Dados da Categoria</h4>\r\n                    <p class=\"card-description\"></p>\r\n                    \r\n                    <form class=\"forms-sample\" action=\"adicionarcategoria.php\" method=\"POST\">\r\n                      <div class=\"form-group\">\r\n                        <label for=\"exampleInputUsername1\">Nome</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"nomecategoria\" placeholder=\"Ex: Servidor 1\" value=\"\">\r\n                      </div>\r\n                      <div class=\"form-group\">\r\n                        <label for=\"exampleInputPassword1\">Id da Categoria</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"idcategoria\" placeholder=\"Insira o Id\" value=\"1\">\r\n                      </div>\r\n                      \r\n                      <button type=\"submit\" id=\"criarcategoria\" name=\"criarcategoria\" class=\"btn btn-primary mr-2\">Criar</button>\r\n                      <a href=\"home.php\" class=\"btn btn-dark\" id=\"sair\" name=\"sair\" >Cancelar</a>\r\n                      \r\n                    </form>\r\n\r\n                    ";
if (isset($_POST["criarcategoria"])) {
    $nomecategoria = $_POST["nomecategoria"];
    $idcategoria = $_POST["idcategoria"];
    $sql = "SELECT * FROM categorias WHERE subid = '" . $idcategoria . "'";
    $result = mysqli_query($conn, $sql);
    if (0 < mysqli_num_rows($result)) {
        echo "<script>swal('Erro!', 'O ID da categoria já existe!', 'error').then((value) => {window.location='adicionarcategoria.php'});</script>";
        exit;
    }
    $sql = "INSERT INTO categorias (nome, subid) VALUES ('" . $nomecategoria . "', '" . $idcategoria . "')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>swal('Sucesso!', 'Categoria criada com sucesso!', 'success').then((value) => {window.location='categorias.php'});</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
echo "\r\n                  </div>\r\n                </div>\r\n              </div>\r\n\r\n\r\n        </div>\r\n        <!-- main-panel ends -->\r\n      </div>\r\n      <!-- page-body-wrapper ends -->\r\n    </div>";

?>