<?php error_reporting(0);
session_start();
include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

include('headeradmin2.php');


?>
<script src="../app-assets/sweetalert.min.js"></script>
<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <h4 class="card-title">Dados da Categoria</h4>
                    <p class="card-description"></p>
                    
                    <form class="forms-sample" action="adicionarcategoria.php" method="POST">
                      <div class="form-group">
                        <label for="exampleInputUsername1">Nome</label>
                        <input type="text" class="form-control" name="nomecategoria" placeholder="Ex: Servidor 1" value="">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Id da Categoria</label>
                        <input type="text" class="form-control" name="idcategoria" placeholder="Insira o Id" value="1">
                      </div>
                      
                      <button type="submit" id="criarcategoria" name="criarcategoria" class="btn btn-primary mr-2">Criar</button>
                      <a href="home.php" class="btn btn-dark" id="sair" name="sair" >Cancelar</a>
                      
                    </form>

                    <?php
                    function anti_sql($input)
                    {
                        $seg = preg_replace_callback("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i", function($match) {
                            return '';
                        }, $input);
                        $seg = trim($seg);
                        $seg = strip_tags($seg);
                        $seg = addslashes($seg);
                        return $seg;
                    }
                    
if (isset($_POST['criarcategoria'])) {
    $nomecategoria = anti_sql($_POST['nomecategoria']);
    $idcategoria = anti_sql($_POST['idcategoria']);
    //verificar se o subid já existe
    $sql = "SELECT * FROM categorias WHERE subid = '$idcategoria'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>swal('Erro!', 'O ID da categoria já existe!', 'error').then((value) => {window.location='adicionarcategoria.php'});</script>";
        exit;
    }
    $sql = "INSERT INTO categorias (nome, subid) VALUES ('$nomecategoria', '$idcategoria')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>swal('Sucesso!', 'Categoria criada com sucesso!', 'success').then((value) => {window.location='categorias.php'});</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

?>

                  </div>
                </div>
              </div>


        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
