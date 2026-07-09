<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
error_reporting(0);
session_start();
include "../atlas/conexao.php";
include "headeradmin2.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$sql = "SELECT * FROM categorias";
$result = $conn->query($sql);
$sql2 = "SELECT * FROM servidores";
$result2 = $conn->query($sql2);
include "tema.php";
echo "          <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n        <p class=\"text-primary\">Aqui Você Pode Editar os Detalhes do Painel.</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n            <h4 class=\"card-title\">Categorias</h4><!-- botao adicionar servidor -->\r\n                    <a href=\"adicionarcategoria.php\" style=\"font-size: 12px;\" class=\"btn btn-primary btn-md\">Add Categoria</a><a href=\"adicionarservidor.php\" style='margin: 0 10px; font-size: 12px;' class=\"btn btn-primary btn-md\">Add Servidor</a><br><br>\r\n                    <!-- <p class=\"card-description\"> Add class <code>Usuarios</code>\r\n                    </p> -->\r\n                    <div class=\"table-responsive\">\r\n                      <table class=\"table table-striped\">\r\n                        <thead>\r\n                          <tr>\r\n                            \r\n                            <th> Nome </th>\r\n                            <th> Id Categoria </th>\r\n                            <th> Deletar </th>\r\n                          </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                          ";
while ($user_data = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $user_data["nome"] . "</td>";
    echo "<td>" . $user_data["subid"] . "</td>";
    echo "<td><a style='margin: 0 15px;' class='btn btn-danger btn-md' onclick='deletecategoria(" . $user_data["id"] . ")'>Deletar</a>\r\n                            <script>\r\n                            function deletecategoria(id){\r\n                              swal({\r\n                                title: 'Tem certeza que deseja deletar essa categoria?',\r\n                                text: 'Você não poderá recuperar essa categoria depois!',\r\n                                icon: 'warning',\r\n                                buttons: true,\r\n                                dangerMode: true,\r\n                              })\r\n                              .then((willDelete) => {\r\n                                if (willDelete) {\r\n                                  window.location.href = 'dellcategoria.php?id=' + id;\r\n                                } else {\r\n                                  swal('A categoria não foi deletada!');\r\n                                }\r\n                              });\r\n                            }\r\n                            </script>\r\n                            </td>";
    echo "</tr>";
}
echo "                          \r\n                        </tbody>\r\n                      </table>\r\n                      \r\n                  </div>\r\n                </div>\r\n              </div>\r\n\r\n\r\n        </div>\r\n        <!-- main-panel ends -->\r\n      </div>\r\n      <!-- page-body-wrapper ends -->\r\n    </div>";

?>