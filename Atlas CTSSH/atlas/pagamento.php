error_reporting(0);
session_start();
include 'header2.php';

include('conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
unset($_SESSION['addquantidade']);

$login = $_SESSION['login'];
$senha = $_SESSION['senha'];

$sql4 = "SELECT * FROM accounts WHERE login = '$login' AND senha = '$senha'";
$result4 = $conn->query($sql4);
if ($result4->num_rows > 0) {
    while ($row4 = $result4->fetch_assoc()) {
        $_SESSION['iduser'] = $row4['id'];
        $_SESSION['byid'] = $row4['byid'];
    }
}

include_once '../admin/suspenderrev.php';

$sql5 = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[iduser]'";
$result5 = $conn->query($sql5);
if ($result5->num_rows > 0) {
    while ($row5 = $result5->fetch_assoc()) {
        $_SESSION['validade'] = $row5['expira'];
        $_SESSION['limite'] = $row5['limite'];
        $_SESSION['tipoconta'] = $row5['tipo'];
        
    }
}

if ($_SESSION['tipoconta'] == 'Credito') {
    echo '<script>window.location.href = "adicionar.php";</script>';
}

$sql6 = "SELECT * FROM accounts WHERE id = '$_SESSION[byid]'";
$result6 = $conn->query($sql6);
if ($result6->num_rows > 0) {
    while ($row6 = $result6->fetch_assoc()) {
        $_SESSION['valorrevenda'] = $row6['valorrevenda'];
        $_SESSION['access_token'] = $row6['access_token'];
    }
}
//se valor revenda for 0, nÃ£o tem revenda
if ($_SESSION['valorrevenda'] == 0) {
  echo '<script>alert("Seu revendedor nÃ£o Esta cadrastado para Pagamento Automatico")</script>';
    echo '<script>window.location.href = "../home.php";</script>';
}
//se access token for null, nÃ£o tem revenda


if (isset($_SESSION['valoradd'])) {
    unset($_SESSION['valoradd']);
}

if (isset($_SESSION['valor'])) {
  unset($_SESSION['valor']);
}


$renovacao = $_SESSION['valorrevenda'] * $_SESSION['limite'];


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
                    <div class="row">
                        <!-- invoice view page -->
                        <div class="col-xl-9 col-md-8 col-12">
                            <div class="card invoice-print-area">
                                <div class="card-content">
                                    <div class="card-body pb-0 mx-25">
                                        <!-- header section -->
                                        <div class="row">
                                            <div class="col-xl-8 col-md-12">
                                            </div>
                                        </div>
                                        <!-- logo and title -->
                                        <div class="row my-3">
                                            <div class="col-6">
                                                <h4 class="text-primary">Pagamento</h4>
                                                <span></span>
                                            </div>
                                            <div class="col-6 d-flex justify-content-end">
                                                <img src="<?php echo $logo ?>" alt="logo" height="46" width="134">  
                                            </div>
                                        </div>
                                            <div class="invoice-action-btn">
                                        <button class="btn btn-primary btn-print" onclick="window.location.href = 'adicionar.php';">
                                            <i class='bx bx-dollar'></i>
                                            <span>Adicionar Login</span>
                                        </button>
                                    </div>
                                        </div>

                                    <div class="card-body pt-0 mx-25">
                                        <hr>
                                        <div class="row">
                                            <div class="col-4 col-sm-6 mt-75">
                                                <p>Seu Login: <code><?php echo $_SESSION['login'] ?></code></p>
                                                
                                            </div>
                                            <!-- cupom -->
                                            
                                            
                                            
                                            <div class="col-8 col-sm-6 d-flex justify-content-end mt-75">
                                                <div class="invoice-subtotal">
                                                    <div class="invoice-calc d-flex justify-content-between">
                                                        <span class="invoice-title">Seu Limite Ã© <?php echo $_SESSION['limite'] ?></span>
                                                    </div>
                                                    <div class="invoice-calc d-flex justify-content-between">
                                                        <span class="invoice-title">Sua Mensalidade Ã© <?php echo $renovacao ?> Reais</span>
                                                    </div>
                                                    <hr>
                                                    
                                                    <form action="pagamento.php" method="POST">
                                                    <div class="form-group">
                                                        <label for="cupom">Cupom de desconto:</label>
                                                        <input type="text" class="form-control" id="cupom" name="cupom">
                                                    </div>
                                                    <div class="invoice-action-btn">
                                                        <button class="btn btn-success btn-block" name="renovar">
                                                            <i class='bx bx-dollar'></i>
                                                            <span>Renovar</span>
                                                        </button>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                        </div>
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
                  
                  if (isset($_POST['renovar'])) {
                      //anti sql

                      $_SESSION['valor'] = anti_sql($renovacao);
                      $_SESSION['cupom'] = anti_sql($_POST['cupom']);

                      if ($_SESSION['valor'] == 0) {
                          echo "<script>alert('VocÃª nÃ£o tem limite para renovar')</script>";
                      }else{
                          echo "<script>location.href='processando.php'</script>";
                      }
                  
                  }
                  
                  if (isset($_POST['adicionar'])) {
                      echo "<script>location.href='adicionar.php'</script>";
                      $_SESSION['email'] = anti_sql($_POST['email']);
                  }
                  
                  ?>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
