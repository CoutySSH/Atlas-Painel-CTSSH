 <?php    error_reporting(0);
session_start();
include('../atlas/conexao.php');
include('headeradmin2.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$sql = "SELECT * FROM accounts WHERE login = '".$_SESSION['login']."' AND senha = '".$_SESSION['senha']."'";
$result = $conn -> query($sql);
if ($result -> num_rows > 0){
    while ($row = $result -> fetch_assoc()){
        $iduser = $row['id'];
        
    }
}
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
//anti sql injection na $_GET['id']
$_GET['id'] = anti_sql($_GET['id']);
$id = $_GET['id'];
 $sql = "SELECT * FROM accounts WHERE id = '$id'";
$result2 = $conn -> query($sql);
if ($result2 -> num_rows > 0){
    while ($row = $result2 -> fetch_assoc()){
        $login = $row['login'];
    }
}


            $sql = "SELECT * FROM ssh_accounts WHERE byid = '$id'";
            $result = $conn -> query($sql);

?>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout">
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <section id="basic-datatable">
            <div class="row">
             
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Detalhes do Revendedor <?php echo $login ?></h4>
                        </div>
                        <script>


if (window.innerWidth < 678) {

    document.write('<div class="alert alert-warning" role="alert"> <strong>Atenção!</strong> Mova para lado para Fazer Alguma Ação! </div>');
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 3000);
}

</script>
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <!-- nao mostar o sroll -->
                                <div class="table-responsive" style=" overflow: auto; overflow-y: hidden;">
                                    <table class="table zero-configuration" id="myTable">
                                                <thead>
                                                    <tr>
                                                    <th> Usuario</th>
                                                    <th> Senha </th>
                                                    <th> Limite </th>
                                                    <th> Vencimento </th>
                                                    <th> Status </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                          //result e result2
                          while ($user_data = mysqli_fetch_assoc($result))
                          //converter expira para data
                          {
                          $user_data['expira'] = date('d/m/Y H:i:s', strtotime($user_data['expira']));
                            //input pesquisar
                            echo "<tr>";
                            echo "<td>".$user_data['login']."</td>";
                            echo "<td>".$user_data['senha']."</td>";
                            echo "<td>".$user_data['limite']."</td>";
                            echo "<td>".$user_data['expira']."</td>";
                            //mostrar onlines primeiro
                            
                            if ($user_data['mainid'] == 'Suspenso') {
                              echo "<td><label class='badge badge-danger'>Suspenso</label></td>";
                          }else{
                          if($user_data['status'] == 'Online'){
                              echo "<td><label class='badge badge-success'>Online</label></td>";
                          }else{
                              echo "<td><label class='badge badge-danger'>Offline</label></td>";
                                }
                          echo "</tr>";
                              }
                        }
                        
                        ?>
  </tbody>
    </table>
    
    </div>
    </div>
    </div>
    <div class="card-body"><br>
                    <h4 class="card-title">Revendedores do <?php echo $login ?></h4>
                    <div class="table-responsive">
                     
                        </div>
                 
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>  </th>
                            <th> Usuario</th>
                            <th> Senha </th>
                            <th> Limite </th>
                            <th> Vencimento </th>
                            <th> Status </th>
                          </tr>
                        </thead>
                        <tbody id="myTable">
                        <?php
                          //result e result2
                          $sql = "SELECT * FROM accounts where byid = '$id'";
                           $result3 = $conn -> query($sql);
                           $sql = "SELECT * FROM atribuidos where byid = '$id'";
                            $result4 = $conn -> query($sql);

                          while ($user_data = mysqli_fetch_assoc($result3) and $user_data2 = mysqli_fetch_assoc($result4))
                          //converter expira para data
                          {
                          $user_data2['expira'] = date('d/m/Y H:i:s', strtotime($user_data2['expira']));
                            //input pesquisar
                            echo "<tr>";
                            echo "<td></td>";
                            echo "<td>".$user_data['login']."</td>";
                            echo "<td>".$user_data['senha']."</td>";
                            echo "<td>".$user_data2['limite']."</td>";
                            if ($user_data2['tipo'] == 'Credito') {
                              $user_data2['expira'] = 'Nunca';
                            }
                            echo "<td>".$user_data2['expira']."</td>";
                            if ($user_data2['suspenso'] == '1') {
                              echo "<td><label class='badge badge-danger'>Suspenso</label></td>";
                          }else{
                            echo "<td><label class='badge badge-success'>Ativo</label></td>";
                          }
                            //mostrar onlines primeiro
                            echo "</tr>";
                        }
                        
                        ?>

                          
                        </tbody>
                      </table>
                   
                        </div>
                        </div>
                        
                  <div class="card-body">
                    <h4 class="card-title">Pagamentos Recebidos</h4>
                    <!-- <p class="card-description"> Add class <code>Usuarios</code>
                    </p> -->
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th> Login </th>
                            <th> Id do Pagamento </th>
                            <th> Valor </th>
                            <th> Detalhes </th>
                            <th> Data e Hora </th>
                            <th> Status </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                           $sql = "SELECT * FROM pagamentos  where byid = '$id'";
                           $result = $conn -> query($sql);
                          //result e result2
                          while ($user_data = mysqli_fetch_assoc($result)){
                          //converter expira para data
                          if($user_data['status'] == 'Aprovado'){
                            $status = "<label class='badge badge-success'>Aprovado</label>";
                            }else{
                            $status = "<label class='badge badge-danger'>Pendente</label>";
                            }
                          
                            
                          
                            echo "<td>".$user_data['login']."</td>";
                            echo "<td>".$user_data['idpagamento']."</td>";
                            echo "<td>".$user_data['valor']."</td>";
                            echo "<td>".$user_data['texto']."</td>";
                            echo "<td>".$user_data['data']."</td>";
                            echo "<td>".$status."</td>";
                            
                            echo "</tr>";
                          }
                          
                          ?>
                          
                        </tbody>
                      </table>
    

                                        
                    
                          
                                        
                        
    <!-- END: Content-->
    <script src="cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="../app-assets/js/scripts/datatables/datatable.js"></script>
    <script>
    $('#myTable').DataTable({

        /* traduzir somente */
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro disponível",
            "infoFiltered": "(filtrado de _MAX_ registros no total)",
            "search": "Pesquisar:",
            "paginate": {
                "first": "",
                "last": "",
                "next": "",
                "previous": ""
            }
        }
    
    });

</script>
<script src="../app-assets/sweetalert.min.js"></script>
<!-- ajax -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
