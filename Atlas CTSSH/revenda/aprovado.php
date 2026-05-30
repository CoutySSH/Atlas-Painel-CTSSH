session_start();

include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}
error_reporting(0);
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
   
      <h2 class="text-center mb-3 mt-0 mt-md-4">Pagamento Aprovado</h2>
      <p class="text-center">Parabéns, seu pagamento foi aprovado com sucesso!</p>

      <form action='formulariocompra.php' method='post'>
      <div class="row mx-4 gy-3">


<!-- Starter -->
<div class="col-xl mb-lg-0 lg-4">
  <center>
  <script>
            //se for desktop definir width: 18rem; nessa classe card border shadow-none

            if (window.innerWidth > 768) {
                document.write("<style>.card {width: 30rem;}</style>");
            }
        </script>
        <script>
                      function copyDivToClipboard() {
                        var range = document.createRange();
                        range.selectNode(document.getElementById("divToCopy"));
                        window.getSelection().removeAllRanges(); // clear current selection
                        window.getSelection().addRange(range); // to select text
                        document.execCommand("copy");
                        window.getSelection().removeAllRanges();// to deselect
                        //alert
                        swal("Copiado!", "", "success");

                      }
                    </script>

<div class="card border shadow-none">
            <div class="card-body" id="divToCopy">
                
              <h5 class="text-start text-uppercase">Agora Você e um novo Revendedor <?php echo $nomepainel; ?></h5>
              
              <div class="alert alert-alert" role="alert" style="text-align: center; font-size: 18px;">
                                                       <div class="divider divider-success">
                                                            
                                                        <strong class="divider-text" style="font-size: 20px;">🎉 Seu Acesso ao Painel 🎉</strong>
                                                        </div>
                                                        <p>🔎 Usuario: <?php echo $_SESSION['usuario']; ?></p>
                                                        <p>🔑 Senha: <?php echo $_SESSION['senha']; ?></p>
                                                        <p>🎯 Validade: 30 Dias</p>
                                                        <p>🕟 Limite: <?php echo $_SESSION['plano']; ?></p>
                                                        <p>📱 Acesso: https://<?php echo $_SERVER['HTTP_HOST']; ?>/index.php</p>
                                                        <a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/index.php" class="btn btn-primary btn-block">Acessar Painel</a>
                                                        <div class="divider divider-success">
                                                            <p><strong class="divider-text" style="font-size: 20px;"></strong></p>
                                                            <button type="button" class="btn btn-primary " onclick="copyDivToClipboard()">Copiar Dados</button>
                                                        
                                                        
                                                        
            </div>
          </div>
        </div>
        </div>


          
        
        
    </div>
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
<?php

session_destroy();
session_unset();
?>
