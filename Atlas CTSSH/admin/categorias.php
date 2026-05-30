    
<script src="../app-assets/sweetalert.min.js"></script>
<?php
error_reporting(0);
session_start();
include('../atlas/conexao.php');
include('headeradmin2.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$sql = "SELECT * FROM categorias";
          $result = $conn -> query($sql);

          $sql2 = "SELECT * FROM servidores";
            $result2 = $conn -> query($sql2);
?>
<?php
include('tema.php');
?>
          <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <p class="text-primary">Aqui Você Pode Editar os Detalhes do Painel.</p>
            <div class="content-header row">
            </div>
            <div class="content-body">
            <h4 class="card-title">Categorias</h4><!-- botao adicionar servidor -->
                    <a href="adicionarcategoria.php" style="font-size: 12px;" class="btn btn-primary btn-md">Add Categoria</a><a href="adicionarservidor.php" style='margin: 0 10px; font-size: 12px;' class="btn btn-primary btn-md">Add Servidor</a><br><br>
                    <!-- <p class="card-description"> Add class <code>Usuarios</code>
                    </p> -->
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            
                            <th> Nome </th>
                            <th> Id Categoria </th>
                            <th> Deletar </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          //result e result2
                          while ($user_data = mysqli_fetch_assoc($result)){

                            echo "<tr>";

                            echo "<td>" . $user_data['nome'] . "</td>";
                            echo "<td>" . $user_data['subid'] . "</td>";
                            echo "<td><a style='margin: 0 15px;' class='btn btn-danger btn-md' onclick='deletecategoria(" . $user_data['id'] . ")'>Deletar</a>
                            <script>
                            function deletecategoria(id){
                              swal({
                                title: 'Tem certeza que deseja deletar essa categoria?',
                                text: 'Você não poderá recuperar essa categoria depois!',
                                icon: 'warning',
                                buttons: true,
                                dangerMode: true,
                              })
                              .then((willDelete) => {
                                if (willDelete) {
                                  window.location.href = 'dellcategoria.php?id=' + id;
                                } else {
                                  swal('A categoria não foi deletada!');
                                }
                              });
                            }
                            </script>
                            </td>";
                            echo "</tr>";


                          }
                            
                        
                          
                        
                          ?>
                          
                        </tbody>
                      </table>
                      
                  </div>
                </div>
              </div>


        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
