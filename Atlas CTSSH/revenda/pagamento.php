session_start();
error_reporting(0);
include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}
$sql = "SELECT * FROM configs";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$csspersonali = $row["corfundologo"];

$expiracaopix = $_SESSION['expiracaopix'];
$valor = $_SESSION['valor'];
if ($_SESSION['formadepag'] == 1) {
    $expiracaopix = $_SESSION['expiracaopix'];
    echo "<script>
    function atualizarTempoRestante() {
        var agora = new Date();
        var expira = new Date('$expiracaopix');
        var diferenca = expira - agora;
        var minutos = Math.floor((diferenca / 1000) / 60);
        var segundos = Math.floor((diferenca / 1000) % 60);

        if (diferenca > 0) {
            document.getElementById('tempo-restante').innerHTML = 'Tempo restante : ' + minutos + 'm ' + segundos + 's';
        } else {
            document.getElementById('tempo-restante').innerHTML = 'Tempo expirado';
        }
    }

    setInterval(atualizarTempoRestante, 1000);
</script>";
}
?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title> Pagamento</title>
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
   
      <h2 class="text-center mb-3 mt-0 mt-md-4">Pagamento</h2>
      <p class="text-center">Faça o Pagamento via pix Qrcode ou Copia e Cola.</p>

      <form action='formulariocompra.php' method='post'>
      <div class="row mx-4 gy-3">
      <script>
                 function copyDivToClipboard() {
                     let textoCopiado = document.getElementById("qrcode");
                            textoCopiado.select();
                            textoCopiado.setSelectionRange(0, 99999)
                            document.execCommand("copy");
                            alert("Copiado com Sucesso!");
                     
                    }
                    </script>
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
              <h5 class="text-start text-uppercase">N° Pedido: <?= $_SESSION['payment_id']?></h5>
              <div class="divider divider-success">
                                                            
                                                        <strong class="divider-text" style="font-size: 20px;">INFORMAÇÕES</strong>
                                                        </div>
                                                        <p>Valor a Pagar: <?= $valor ?> R$</p>
                                                        <p>Após Efetuar o Pagamento Aguarde o Pagamento ser Concluido</p>
                                                        

                                                        <img style="width: 160px;" class="qr_code" id='imgqr' src="data:image/png;base64,<?= $_SESSION['qr_code_base64']?>">
                                                        <hr>
                                                        <input type="text" name="texto" id="qrcode" class="form-control" value="<?= $_SESSION['qr_code']?>">
                                                        <br>
                                                        <div id="tempo-restante" style="text-align: center; font-size: 18px;"></div>
                                                        <br>
                                                        <button type="button" class="btn btn-primary" onclick="copyDivToClipboard()">Copiar</button>
             
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">

//Calling function
repeatAjax();


function repeatAjax(){
jQuery.ajax({
          type: "POST",
          url: 'verifica.php',
          dataType: 'text',
          success: function(resp) {
          	if(resp == 'Aprovado')
          	{
              $(".qr_code").attr('src','https://www.pngplay.com/wp-content/uploads/2/Approved-PNG-Photos.png');
          	  window.location.replace("aprovado.php");

                    jQuery('.teste').html(resp);
                    }

          },
          complete: function() {
                setTimeout(repeatAjax,1000); //After completion of request, time to redo it after a second
             }
        });
}
</script>
</body>
</html>
