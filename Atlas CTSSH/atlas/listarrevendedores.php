 <?php    error_reporting(0);
session_start();
include('../atlas/conexao.php');
include('header2.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
  
  $sql = "SELECT * FROM accounts WHERE byid = '$_SESSION[iduser]'";
  $result = $conn -> query($sql);
          $sql2 = "SELECT * FROM atribuidos where byid = '".$_SESSION['iduser']."' ";
          $result2 = $conn -> query($sql2);

date_default_timezone_set('America/Sao_Paulo');
$sql5 = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[iduser]'";
$sql5 = $conn->query($sql5);
$row = $sql5->fetch_assoc();
$validade = $row['expira'];
$tipo = $row['tipo'];
$_SESSION['tipodeconta'] = $row['tipo'];
//time zone
date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d H:i:s');
if ($_SESSION['tipodeconta'] == 'Credito') {
}else{
if ($validade < $hoje) {
    echo "<script>alert('Sua conta estÃ¡ vencida')</script>";
    echo "<script>window.location.href = '../home.php'</script>";
    unset($_POST['criaruser']);
    unset($_POST['usuariofin']);
    unset($_POST['senhafin']);
    unset($_POST['validadefin']);
}
}
$hoje = date('dmY');
include_once '../admin/suspenderrev.php';
?>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-9O9Sd6Ia1+A0+KwUO1eUg0Fyb3J6UdTo68joKgY9A20+RzI2HfIQK8pk6FyUdxUGpIq3oUItrW8jYVGf9GYZRg==" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" integrity="sha512-HFtTtyTjlELm+B62zspZ8PqKmzvDmCdjLJl/dyK2TlT1Tkbz2eNmv1Gsb8BLYgjKv7/9FFylhL9X8KbW36BvDw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- JavaScript do jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-50RcK1E6jgEnV9P+A5fjaqV6Om4ZK7PO0De/4+i4eEI4GgkKTU1hvvB6KLpU5ij5" crossorigin="anonymous"></script>

<!-- JavaScript do Bootstrap -->
</head>
 
<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout">


  <script>
    /* se a tela for maior */
    if (window.innerWidth < 678) {
      document.getElementById("widgets-Statistics").style.display = "none";
      alert("A tela estÃ¡ muito pequena para exibir os grÃ¡ficos, por favor, aumente a tela!");
    }
    $(document).ready(function(){
});
    </script>
            

                
<div class="app-content content">
    <div class="content-overlay">
      
    </div>
    
    <div class="content-wrapper">
      
      <div class="container-xxl flex-grow-1 container-p-y">
        
        <section id="basic-datatable">
            <div class="row">
              
             
                <div class="col-12">
                  
                    <div class="card">
                      
                        <div class="card-header">
                            <h4 class="card-title">Lista de Revendedores</h4>
                        </div>

                        <script>


if (window.innerWidth < 678) {

    document.write('<div class="alert alert-warning" role="alert"> <strong>AtenÃ§Ã£o!</strong> Mova para lado para Fazer Alguma AÃ§Ã£o! </div>');
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
                                                   
                                                    <th> Login </th>
                                                    <th> Senha </th>
                                                    <th> Modo </th>
                                                    <th> Categoria </th>
                                                    <th> Validade </th>
                                                    <th> Limite </th>
                                                    <th> Status </th>
                                                    <th> AcÃµes </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                          //result e result2
                          while ($user_data = mysqli_fetch_assoc($result))
                          //converter expira para data
                          {
                            $sql2 = "SELECT * FROM atribuidos  WHERE userid = '".$user_data['id']."' ";
                            $result2 = $conn -> query($sql2);
                            $user_data2 = mysqli_fetch_assoc($result2);

                            $expira = $user_data2['expira'];
                            $expira = date('d/m/Y', strtotime($expira2));
                            $user_data['expira'] = $expira2;
                            $expira2 = $user_data2['expira'];
                            $expira2 = date('d/m/Y', strtotime($expira2));
                            $user_data2['expira'] = $expira2;
                            $tipomodal = $user_data2['tipo'];
                            $expira = $user_data2['expira'];
                            //se tiver menos de 1 dia pra expirar
                            
                    
                            //time zone
                            
                            
                            
                            echo "<tr>";
                            
                          
                          

                            echo "<td>".$user_data['login']."</td>";
                            echo "<td>".$user_data['senha']."</td>";
                            if ($user_data2['tipo'] == 'Credito'){
                              $expira = "Nunca";
                            }
                            
                            echo "<td>".$user_data2['tipo']."</td>";
                            echo "<td>".$user_data2['categoriaid']."</td>";

                            date_default_timezone_set('America/Sao_Paulo');
                            if ($expira2 == date('d/m/Y', strtotime('+5 days'))) {
                              $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 5 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('+4 days'))) {
                              $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 4 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('+3 days'))) {
                              $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 3 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('+2 days'))) {
                              $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 2 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('+1 days'))) {
                              $expira = "<label class='badge badge-warning' style='color: #fff;'>Expira em 1 dias</label>";
                            }

                            if ($expira2 == date('d/m/Y')) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expira Hoje</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-1 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou ontem</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-2 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou anteontem</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-3 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 3 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-4 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 4 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-5 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 5 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-6 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 6 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-7 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 7 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-8 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 8 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-9 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 9 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-10 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 10 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-11 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 11 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-12 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 12 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-13 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 13 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-14 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 14 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-15 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 15 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-16 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 16 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-17 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 17 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-18 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 18 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-19 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 19 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-20 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 20 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-21 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 21 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-22 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 22 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-23 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 23 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-24 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 24 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-25 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 25 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-26 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 26 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-27 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 27 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-28 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 28 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-29 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 29 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-30 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 30 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-31 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 31 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-32 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 32 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-33 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 33 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-34 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 34 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-35 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 35 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-36 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 36 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-37 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 37 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-38 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 38 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-39 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 39 dias</label>";
                            }
                            if ($expira2 == date('d/m/Y', strtotime('-40 days'))) {
                              $expira = "<label class='badge badge-danger' style='color: #fff;'>Expirou a 40 dias</label>";
                            }

                            echo "<td>".$expira."</td>";
                            echo "<td>".$user_data2['limite']."</td>";
                            if ($user_data2['suspenso'] == '0'){
                              echo "<td><label class='badge badge-success mr-1 mb-1' style='color: white;'>Ativo</label></td>";
                            }else{
                              echo "<td><label class='badge badge-danger' style='color: white;'>Suspenso</label></td>";
                            }
                            echo "<td>
                            
                            <div class='btn-group'>
                              <button type='button' class='btn btn-primary dropdown-toggle dropdown-toggle-split' id='dropdownMenuReference' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' data-reference='parent'>
                                <span class='sr-only'>Toggle Dropdown</span>AcÃµes
                              </button>
                              <style>
                              .dropdown-item:hover{
                                
                                color: white;
                              }
                              </style>
                              <div class='dropdown-menu' aria-labelledby='dropdownMenuReference'>
                                <a class='dropdown-item' onclick='editar(".$user_data['id'].")'>Editar</a>
                                <a class='dropdown-item' href='javascript:deletar(".$user_data['id'].")'>Deletar</a>
                                "; if ($user_data2['tipo'] == 'Credito'){
                                  
                                }else{
                                  echo "<a class='dropdown-item' href='javascript:renovar(".$user_data['id'].")'>Renovar</a>";
                                }
                                echo "
                                <a class='dropdown-item' href='javascript:suspender(".$user_data['id'].")'>Suspender</a>
                                <a class='dropdown-item' href='javascript:reativar(".$user_data['id'].")'>Reativar</a>
                                <a class='dropdown-item' href='visualizar.php?id=".$user_data['id']."'>Visualizar</a>
                              </div>
                          </td>";
                            echo "</tr>";
                          }
                          
                          ?>
                          
                     
            <!-- modal editar -->
 

            <script>
      
         function editar($id) {
         /* redireciona para outra pagina  */
          window.location.href = "editarrevenda.php?id="+$id;
        }

    function renovar(id) {
                              swal({
                                title: 'Tem certeza?',
                                text: 'VocÃª nÃ£o poderÃ¡ reverter isso!',
                                icon: 'warning',
                                buttons: true,
                                dangerMode: true,
                              })
                              .then((willDelete) => {
                                if (willDelete) {
                                  swal('Poof! Renovado!', {
                                    icon: 'success',
                                  });
                                  window.location.href = 'renovarrevenda.php?id='+id;
                                } else {
                                  swal('Seu Revendedor estÃ¡ seguro!');
                                }
                              });
                            }
    function reativar(id) {
        /* confirma */
        swal({
                                title: 'Tem certeza?',
                                text: 'VocÃª nÃ£o poderÃ¡ reverter isso!',
                                icon: 'warning',
                                buttons: true,
                                dangerMode: true,
                              })
                              .then((willDelete) => {
                                if (willDelete) {
                                  swal('Poof! Reativado!', {
                                    icon: 'success',
                                  });
                                  window.location.href = 'reativarrevenda.php?id='+id;
                                } else {
                                  swal('Seu Revendedor estÃ¡ seguro!');
                                }
                              });
                            }
    function suspender(id) {
                              swal({
                                title: 'Tem certeza?',
                                text: 'VocÃª nÃ£o poderÃ¡ reverter isso!',
                                icon: 'warning',
                                buttons: true,
                                dangerMode: true,
                              })
                              .then((willDelete) => {
                                if (willDelete) {
                                  swal('Poof! Suspenso!', {
                                    icon: 'success',
                                  });
                                  window.location.href = 'suspenderrevenda.php?id='+id;
                                } else {
                                  swal('Seu Revendedor estÃ¡ seguro!');
                                }
                              });
                            }
function deletar(id) {
        /* confirma */
        swal({
                                title: 'Tem certeza?',
                                text: 'VocÃª nÃ£o poderÃ¡ reverter isso!',
                                icon: 'warning',
                                buttons: true,
                                dangerMode: true,
                              })
                              .then((willDelete) => {
                                if (willDelete) {
                                  swal('Poof! Deletando Revendedor!', {
                                    icon: 'success',
                                  });
                                  window.location.href = 'excluirrevenda.php?id='+id;
                                } else {
                                  swal('Seu Revendedor estÃ¡ seguro!');
                                }
                              });
                            }
  </script>
  <script>
    function reativar(id){
                              swal({
                                title: 'Tem certeza?',
                                text: 'VocÃª nÃ£o poderÃ¡ reverter isso!',
                                icon: 'warning',
                                buttons: true,
                                dangerMode: true,
                              })
                              .then((willDelete) => {
                                if (willDelete) {
                                  swal('Poof! Reativado!', {
                                    icon: 'success',
                                  });
                                  window.location.href = 'reativarrevenda.php?id='+id;
                                } else {
                                  swal('Seu Revendedor estÃ¡ seguro!');
                                }
                              });
                            }
  </script>
  </tbody>
    </table>
    </div>
    
    
    </div>
    </div>
    

                                        
                    
                          
                                        
                        
    <!-- END: Content-->
    <script src="cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="../app-assets/js/scripts/datatables/datatable.js"></script>
    <script>
    $('#myTable').DataTable({

        /* traduzir somente */
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por pÃ¡gina",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando pÃ¡gina _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro disponÃ­vel",
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
<script src="../../../app-assets/js/scripts/pages/bootstrap-toast.js"></script>
