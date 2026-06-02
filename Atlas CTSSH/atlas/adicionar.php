error_reporting(0);
session_start();
include 'header2.php';
include('conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}
include_once '../admin/suspenderrev.php';
$sql = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[iduser]'";
    $result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $_SESSION['limite'] = $row['limite'];
        $_SESSION['validade'] = $row['expira'];
        $_SESSION['typecont'] = $row['tipo'];
    }
}

$sql2 = "SELECT * FROM accounts WHERE id = '$_SESSION[byid]'";
    $result2 = $conn->query($sql2);
if ($result2->num_rows > 0) {
    while ($row2 = $result2->fetch_assoc()) {
        $_SESSION['valorrevenda'] = $row2['valorrevenda'];
        $_SESSION['valorcredito'] = $row2['mainid'];
    }
}

$slq2 = "SELECT sum(limite) AS limiterevenda  FROM atribuidos where byid='$_SESSION[byid]'";
$result = $conn->prepare($slq2);
$result->execute();
$result->bind_result($limiterevenda);
$result->fetch();
$result->close();

$sql4 = "SELECT * FROM ssh_accounts WHERE byid = '$_SESSION[byid]'";
$sql4 = $conn->prepare($sql4);
$sql4->execute();
$sql4->store_result();
$num_rows = $sql4->num_rows;
$usadousuarios = $num_rows;

$sql55 = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[byid]'";
$result55 = $conn->query($sql55);
if ($result55->num_rows > 0) {
  while ($row55 = $result55->fetch_assoc()) {
      $limite = $row55['limite'];
  }
}

$soma = $usadousuarios + $limiterevenda;

if ($_SESSION['byid'] == '1') {
 $limitefinal = 'Ilimitado';
} else {
  if ($_SESSION['typecont'] == 'Credito') {
    $limitefinal = $limite;
  } else {
    $limitefinal = $limite - $soma;
  }
}

//consulta se o revendedor esta cadastrado
$sql = "SELECT * FROM accounts WHERE id = '$_SESSION[byid]'";
$result = $conn->query($sql);
if ($result->num_rows > 0){
  while ($row = $result->fetch_assoc()) {
    $_SESSION['valorrevenda'] = $row['valorrevenda'];
    $_SESSION['valorcredito'] = $row['mainid'];
    $_SESSION['accesstoken'] = $row['accesstoken'];
  }
}
if ($_SESSION['accesstoken'] == '') {
  echo '<script>sweetAlert("Oops...", "O Revendedor nÃ£o possui uma conta cadastrada!", "error");</script>';
  //redireciona para a pagina de login apÃ³s 3 segundos
  echo '<script>setTimeout(function(){ window.location.href = "../home.php"; }, 3000);</script>';
}

$minimocompra = "1";

$sql_min = "SELECT * FROM configs WHERE id = '1'";
$result_min = $conn->query($sql_min);
if ($result_min->num_rows > 0){
  while ($row_min = $result_min->fetch_assoc()) {
    $minimocompra = $row_min['minimocompra'];
  }
}

?>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout">


    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- app invoice View Page -->
                <section class="invoice-view-wrapper">
                    <form action="processandoadd.php" method="POST">
                    <div class="row">
                        <!-- invoice view page -->
                        <div class="col-xl-9 col-md-8 col-12">
                            <div class="card invoice-print-area">
                                <div class="card-content">
                                    <div class="card-body pb-0 mx-25">
                                    <h4 class="card-title">OlÃ¡ <?php echo $_SESSION['login']?> </h4>
                    <center>
                        <div>
                    <button type="button" class="btn btn-outline-warning btn-fw">Seu Limite Ã© <?php echo $_SESSION['limite']?></button>
                    </div>
                    <br>
                    <p class="card-description" style="font-size: 25px" > Quantos Deseja Adicionar</p>
                     <center>
                     <div class="form-group">
                        <label for="exampleInputUsername1">Quantidade (DISPONIVEL: <?php echo $limitefinal?>) Minimo de Compra: <?php echo $minimocompra ?></label>
                        <input type="number" class="form-control" name="addquantidade" placeholder="Quantidade a Adicionar" required min="<?php echo $minimocompra ?>" max="<?php echo $limitefinal?>">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputUsername1">Cupom</label>
                        <input type="text" class="form-control" name="cupom" placeholder="Cupom">
                        </div>
                    
                    <button type="submit" name="addlogin" class="btn btn-primary btn-rounded btn-fw">Adicionar</button>
                     </center>
                     <br>
                  </div>
</form>
                </div>
                  <?php
                  if (isset($_POST['addlogin'])) {
                     $_SESSION['cupom'] = $_POST['cupom'];
                     $_SESSION['addquantidade'] = $_POST['addquantidade'];
                  }

?>
              </div>
              </div>


        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
