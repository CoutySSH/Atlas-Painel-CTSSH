<?php session_start();
error_reporting(0);
include_once 'atlas/conexao.php';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

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

//anti sql injection na $_GET['token']
$_GET['token'] = anti_sql($_GET['token']);

  $_SESSION['tokenrevenda'] = $_GET['token'];

if (isset($_SESSION['tokenrevenda'])) {
  $token = $_SESSION['tokenrevenda'];
  $sqltoken = "ALTER TABLE accounts ADD COLUMN IF NOT EXISTS tokenvenda TEXT NOT NULL DEFAULT '0'";
  mysqli_query($conn, $sqltoken);
  $sql = "SELECT * FROM accounts WHERE tokenvenda = '$token'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  if (mysqli_num_rows($result) > 0) {
    $_SESSION['valorrevenda'] = $row['valorrevenda'];
  } else {
    echo "<script>alert('LINK INVÁLIDO!');</script>";
    exit;
  }
}
if ($_SESSION['valorrevenda'] == 0) {
  echo "<script>alert('Não Cadrastado!');</script>";
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
    <title><?php echo $nomepainel; ?> - Planos</title>
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

  <!-- Pricing Plans -->

  <body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout" >
      <h2 class="text-center mb-3 mt-0 mt-md-4">Planos Para Revendedores</h2>
      <p class="text-center"> Escolha o plano que melhor se encaixa no seu perfil e comece a vender agora mesmo!</p>

      <form action='revenda.php?token=<?php echo $_SESSION['tokenrevenda']; ?>' method='POST'>
      <div class="row mx-4 gy-3">
        <!-- Starter -->
        <div class="col-xl mb-lg-0 lg-4">
          <div class="card border shadow-none">
            <div class="card-body">
              <h5 class="text-start text-uppercase">Inicial</h5>

              <div class="text-center position-relative mb-4 pb-1">
                <div class="mb-2 d-flex">
                  <h1 class="price-toggle text-primary price-yearly mb-0">R$ <?php echo $_SESSION['valorrevenda'] * 10; ?></h1>
                  <sub class="h5 text-muted pricing-duration mt-auto mb-2">/mês</sub>
                </div>
              </div>

              <p>Plano inicial para quem está começando.</p>

              <hr>

              <ul class="list-unstyled pt-2 pb-1">
                <li class="mb-2">
                  <span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2">
                    <i style='color: #fff;' class="bx bx-check bx-xs"></i>
                  </span>
                  Limites de 10 usuários
                </li>
                <li class="mb-2">
                  <span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2">
                    <i style='color: #fff;' class="bx bx-check bx-xs"></i>
                  </span>
                    Painel de Controle
                </li>
              </ul>
              <input type="hidden" name="tokenrevenda" value="<?php echo $_SESSION['tokenrevenda']; ?>">
              <button type="submit" name="plano10" class="btn btn-primary d-grid w-100">Comprar</button>
            </div>
          </div>
        </div>
        <?php
          session_start();
          if (isset($_POST['plano10'])) {
              $_SESSION['plano'] = 10;
              $_SESSION['tokenrevenda'] = $_POST['tokenrevenda'];
              echo "<script>location.href='revenda/formulariocompra.php';</script>";
          }
?>
        
        <!-- Exclusive -->
        <div class="col-xl mb-lg-0 lg-4">
          <div class="card border border-2 border-primary">
            <div class="card-body">
              <div class="d-flex justify-content-between flex-wrap mb-3">
                <h5 class="text-start text-uppercase mb-0">Intermediário</h5>
                <span style='color: #fff;' class="badge bg-primary rounded-pill">+ Vendido</span>
              </div>

              <div class="text-center position-relative mb-4 pb-1">
                <div class="mb-2 d-flex">
                  <h1 class="price-toggle text-primary price-yearly mb-0">R$<?php echo $_SESSION['valorrevenda'] * 20; ?></h1>
                  <sub class="h5 text-muted pricing-duration mt-auto mb-2">/mês</sub>
                </div>
              </div>
              <p>Plano intermediário para quem já tem uma base de clientes.</p>

              <hr>

              <ul class="list-unstyled pt-2 pb-1">
              <li class="mb-2">
                  <span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2">
                    <i style='color: #fff;' class="bx bx-check bx-xs"></i>
                  </span>
                  Limites de 20 usuários
                </li>
                <li class="mb-2">
                  <span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2">
                    <i style='color: #fff;' class="bx bx-check bx-xs"></i>
                  </span>
                    Painel de Controle
                </li>
              </ul>
              <input type="hidden" name="tokenrevenda" value="<?php echo $_SESSION['tokenrevenda']; ?>">
              <button type="submit" name="plano20" class="btn btn-primary d-grid w-100">Comprar</button>
            </div>
          </div>
        </div>
        <?php if (isset($_POST['plano20'])) {
          $_SESSION['tokenrevenda'] = $_POST['tokenrevenda'];
          $_SESSION['plano'] = 20;
          echo "<script>location.href='revenda/formulariocompra.php'</script>";
        } ?>

        <!-- Enterprise -->
        <div class="col-xl mb-lg-0 lg-4">
          <div class="card border shadow-none">
            <div class="card-body">
              <h5 class="text-start text-uppercase">Avançado</h5>

              <div class="text-center position-relative mb-4 pb-1">
                <div class="mb-2 d-flex">
                  <h1 class="price-toggle text-primary price-yearly mb-0">R$<?php echo $_SESSION['valorrevenda'] * 30; ?></h1>
                  <sub class="h5 text-muted pricing-duration mt-auto mb-2">/mês</sub>
                </div></div>
              <p>Plano avançado para quem já possui muitos clientes.</p>

              <hr>

              <ul class="list-unstyled pt-2 pb-1">
              <li class="mb-2">
                  <span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2">
                    <i style='color: #fff;' class="bx bx-check bx-xs"></i>
                  </span>
                  Limites de 30 usuários
                </li>

                <li class="mb-2">
                  <span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2">
                    <i style='color: #fff;' class="bx bx-check bx-xs"></i>
                  </span>
                    Painel de Controle
                </li>
              </ul>
              <input type="hidden" name="tokenrevenda" value="<?php echo $_SESSION['tokenrevenda']; ?>">
              <button type="submit" name="plano30" class="btn btn-primary d-grid w-100">Comprar</button>
            </div>
          </div>
        </div>
        <?php if (isset($_POST['plano30'])) {
          $_SESSION['tokenrevenda'] = $_POST['tokenrevenda'];
          $_SESSION['plano'] = 30;
          echo "<script>location.href='revenda/formulariocompra.php'</script>";
        } ?>
      </div>
    </div>
</form>
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
 
</body>
</html>
