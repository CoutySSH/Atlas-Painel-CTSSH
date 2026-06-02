<?php session_start();
error_reporting(0);
include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}
include_once '../admin/suspenderrev.php';

  $sql = "SELECT * FROM accounts WHERE tokenvenda = '$_SESSION[tokenrevenda]'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  if (mysqli_num_rows($result) > 0) {
    $_SESSION['valorrevenda'] = $row['valorrevenda'];
    $_SESSION['byid'] = $row['id'];
  } else {
    echo "<script>sweetAlert('Oops...', 'Link invÃ¡lido!', 'error');</script>";
    exit;
  }
  if ($_SESSION['byid'] == '1'){

  }else{
      $sql2 = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[byid]'";
  $result2 = $conn->query($sql2);
  if ($result2->num_rows > 0) {
      while ($row2 = $result2->fetch_assoc()) {
          $_SESSION['tipo'] = $row2['tipo'];

      }
  }
  }
  if ($_SESSION['tipo'] == 'Credito'){
    echo "<script>alert('Modo Credito nÃ£o disponÃ­vel para Compra!');</script>";
    exit;
  }
    
?>

<?php

$sql = "SELECT * FROM configs";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$nomepainel = $row['nomepainel'];
$icon = $row['icon'];
$csspersonali = $row["corfundologo"];
?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title><?php echo $nomepainel; ?> - Compra</title>
    <link rel="apple-touch-icon" href="<?php echo $icon; ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $icon; ?>">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/extensions/dragula.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/semi-dark-layout.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/dashboard-analytics.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../../atlas-assets/css/style.css">
    <!-- END: Custom CSS-->

</head>
<style>
        <?php echo $csspersonali; ?>
    </style>     
<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout" >
   
      <h2 class="text-center mb-3 mt-0 mt-md-4">Preecha as InformaÃ§Ãµes</h2>
      <p class="text-center">Preencha os campos abaixo para realizar a compra.</p>

      <form action='formulariocompra.php' method='post'>
      <div class="row mx-4 gy-3">
        <script>
            //se for desktop definir width: 18rem; nessa classe card border shadow-none

            if (window.innerWidth > 768) {
                document.write("<style>.card {width: 30rem;}</style>");
            }
        </script>
        <!-- Starter -->
        <div class="col-xl mb-lg-0 lg-4">
            <center>
          <div class="card border shadow-none">
            <div class="card-body">
              <h5 class="text-start text-uppercase">Plano de <?php echo $_SESSION['plano']; ?> Usuarios</h5>
             
              <div class="col-md-4">
              <label>Email</label>
              </div>
               <div class="col-md-8 form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
              </div>  
              <div class="col-md-4">
              <label>UsuÃ¡rio</label>
              </div>
               <div class="col-md-8 form-group">
                <input type="text" class="form-control" name="usuario" placeholder="UsuÃ¡rio" required>
              </div> 
              <div class="col-md-4">
              <label>Senha</label>
              </div>
               <div class="col-md-8 form-group">
                <input type="text" class="form-control" name="senha" placeholder="Senha" required>
              </div> 
              <div class="col-md-4">
              <label>Possui um Cupom?</label>
              </div>
               <div class="col-md-8 form-group">
                <input type="text" class="form-control" name="cupom" placeholder="Cupom">
              </div> 
              <p>Salve seu usuÃ¡rio e senha, pois vocÃª precisarÃ¡ dele para acessar o painel.</p>
              
                                                                                                                                

              <button type="submit" name="comprar" class="btn btn-primary d-grid w-100">Comprar</button>
            </div>
          </div>
        </div>
        </div>


          
        
        
    </div>
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
    
    if (isset($_POST['comprar'])){
        $_POST['usuario'] = anti_sql($_POST['usuario']);
        $verifica = "SELECT * FROM accounts WHERE login = '".$_POST['usuario']."'";
        $verifica = $conn->query($verifica);    
        if ($verifica->num_rows > 0) {
            echo "<script>alert('UsuÃ¡rio jÃ¡ existe!');</script>";
            echo "<script>location.href='formulariocompra.php'</script>";
            exit;
        }
        //
        $_POST['cupom'] = anti_sql($_POST['cupom']);
    $verifica = "SELECT * FROM cupons WHERE cupom = '".$_POST['cupom']."' AND byid = '".$_SESSION['byid']."'";
    $verifica = $conn->query($verifica);
    if ($verifica->num_rows > 0) {
        echo "<script>alert('Cupom Aplicado!');</script>";
    }
    $_SESSION['email'] = anti_sql($_POST['email']);
    $_SESSION['usuario'] = anti_sql($_POST['usuario']);
    $_SESSION['senha'] = anti_sql($_POST['senha']);
    $_SESSION['cupom'] = anti_sql($_POST['cupom']);
     

    echo "<script>location.href='processapag.php'</script>";
    }
    ?>
  </div>
  <!--/ Pricing Plans -->
  </div>
  </body>

    <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <script src="../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
    <script src="../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
    <script src="../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <script src="../../../app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
    <script src="../../../app-assets/js/scripts/configs/vertical-menu-dark.js"></script>
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <script src="../../../app-assets/js/scripts/components.js"></script>
    <script src="../../../app-assets/js/scripts/footer.js"></script>
    <script src="../../../app-assets/js/scripts/forms/number-input.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="../app-assets/sweetalert.min.js"></script>
 
 
</body>
</html>
