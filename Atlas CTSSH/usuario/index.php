error_reporting(0);
session_start();
include_once("../atlas/conexao.php");
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if(! $conn ) {

  echo $conn->connect_error;
        }
        $sqlvezesuso = "SELECT * FROM cupons";
    $resultvezesuso = $conn->query($sqlvezesuso);
    
    if ($resultvezesuso->num_rows > 0) {
        while ($rowvezesuso = $resultvezesuso->fetch_assoc()) {
            $vezesuso[] = $rowvezesuso;
        }
    
        foreach ($vezesuso as $cupom) {
            if ($cupom['usado'] >= $cupom['vezesuso']) {
                $cupomId = $cupom['id'];
                $sqlDelete = "DELETE FROM cupons WHERE id = $cupomId";
                $conn->query($sqlDelete);
            }
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

$sql = "SELECT * FROM accounts WHERE id = '$_SESSION[byid]'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $_SESSION['tokenaccess'] = $row['accesstoken'];
        $_SESSION['valorusuario'] = $row['valorusuario'];
        $_SESSION['acesstokenpaghiper'] = $row['acesstokenpaghiper'];
    }
}
if (!file_exists('../admin/suspenderrev.php')) {
    exit ("<script>alert('Token Invalido!');</script>");
  }else{
    include_once '../admin/suspenderrev.php';
    
  }
  if (!isset($_SESSION['sgdfsr43erfggfd4rgs3rsdfsdfsadfe']) || !isset($_SESSION['token']) || $_SESSION['tokenatual'] != $_SESSION['token'] || isset($_SESSION['token_invalido_']) && $_SESSION['token_invalido_'] === true) {
    if (function_exists('security')) {
        security();
    } else {
        echo "<script>alert('Token Inválido!');</script>";
        echo "<script>location.href='../index.php';</script>";

        $_SESSION['token_invalido_'] = true;
        exit;
    }
  }

              if (isset($_POST['cupom'])) {
                isset($_POST['cupom']);
                isset($_SESSION['cupom']);
              }

    $sql = "SELECT * FROM configs";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $nomepainel = $row['nomepainel'];
        $logopainel = $row['logo'];
        $logopainelmini = $row['icon'];
        $csspersonali = $row["corfundologo"];

    }
   
//se nao existir o tokenacess
if($_SESSION['tokenaccess'] == "" && $_SESSION['acesstokenpaghiper'] == ""){
    echo "<script>alert('Revendedor não Cadrastado!');</script>";
    echo "<script>window.location.href = '../renovar.php';</script>";
    exit;
}
//print do cupom
$_SESSION['valor'] = $_SESSION['valorusuario'] * $_SESSION['limite'];

$_SESSION['vencimento'] = date('d/m/Y', strtotime($_SESSION['expira']));
$login = anti_sql($_SESSION['login']);
$senha = anti_sql($_SESSION['senha']);


$sql = "SELECT * FROM ssh_accounts WHERE login = '$login' AND senha = '$senha'";
$result = mysqli_query($conn, $sql);
              if (mysqli_num_rows($result) > 0) {
                  $row = mysqli_fetch_assoc($result);
                  $_SESSION['id'] = $row['id'];
                  $_SESSION['login'] = $row['login'];
                  $_SESSION['senha'] = $row['senha'];
                  $_SESSION['byid'] = $row['byid'];
                  $_SESSION['limite'] = $row['limite'];
                  $_SESSION['expira'] = $row['expira'];
                  $_SESSION['categoria'] = $row['categoriaid'];
                  $valormensal = $row['valormensal'];
                  
              }

              $sql = "SELECT * FROM atribuidos WHERE userid = '$_SESSION[byid]'";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      $_SESSION['limitedono'] = $row['limite'];
                      $_SESSION['tipo'] = $row['tipo'];
                  }
              }
              if ($_SESSION['byid'] == 1) {
                  $_SESSION['limitedono'] = 10000000000;
              }
              if ($_SESSION['limitedono'] < $_SESSION['limite']) {
                  echo "<script>alert('Revendedor não Tem Limite!');</script>";
                  echo "<script>window.location.href = '../renovar.php';</script>";
                  exit;
                
              }
              $_SESSION['valor'] = $_SESSION['valorusuario'] * $_SESSION['limite'];
              //echo "<script>alert('O Valor é de R$ $_SESSION[valorusuario]');</script>";
              //se valor mensal nao for nulo se nao for 0
              if ($valormensal != "" && $valormensal != 0) {
                  $_SESSION['valor'] = $valormensal;  
                  $_SESSION['valormensal'] = $valormensal;
              }


?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
     $sql = "SELECT * FROM configs";
     $result = $conn -> query($sql);
     if ($result->num_rows > 0) {
         while($row = $result->fetch_assoc()) {
             $nomepainel = $row["nomepainel"];
             $logo = $row["logo"];
             $icon = $row["icon"];
         }
     }
    ?>
<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="author" content="Thomas">
    <title><?php echo $nomepainel; ?> - Renovação</title>
    <link rel="apple-touch-icon" href="<?php echo $icon; ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $icon; ?>">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/semi-dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/authentication.css">
    <link rel="stylesheet" type="text/css" href="../../../atlas-assets/css/style.css">
    <script src="../app-assets/sweetalert.min.js"></script>

</head>
<style>
        <?php echo $csspersonali; ?>
    </style>     
<body class="vertical-layout vertical-menu-modern dark-layout 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column" data-layout="dark-layout">
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section id="auth-login" class="row flexbox-container">
                    <div class="col-xl-8 col-11">
                        
                            
                               
                                    <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                                        <div class="card-header pb-1">
                                            <div class="card-title">
                                                <h4 class="text-center mb-2">Seja Bem Vindo(a) <?php echo $_SESSION['login']?></h4>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                            <div class="col-xl mb-lg-0 lg-4">
          <div class="card border border-2 border-primary">
            <div class="card-body">
              <div class="d-flex justify-content-between flex-wrap mb-3">
              </div>

              <div class="text-center position-relative mb-4 pb-1">
                <div class="mb-2 d-flex">
                  </div>
                  <h1 class="price-toggle text-primary price-yearly mb-0" style="text-align: center;"><?php echo $_SESSION['valor']?> R$ Mensal </h1>
              </div>
              <center>
              <p>Renove seu plano para continuar usando nossos serviços.</p>
              </center>
              <hr>

              <ul class="list-unstyled pt-2 pb-1">
                <li class="mb-2">
                  <span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2">
                    <i class="bx bx-check bx-xs"></i>
                  </span>
                  O Seu Limite é <?php echo $_SESSION['limite']?>
                </li>
                <li class="mb-2">
                  <span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2">
                    <i class="bx bx-check bx-xs"></i>
                  </span>
                  O Seu Vencimento é <?php echo $_SESSION['vencimento']?>
                </li>
                <center>
                <p>Tem um Cupom?</p>
                </center>        
                <form action="index.php" method="POST">
                        <input type="text" name="cupom" placeholder="Codigo do Cupom" class="form-control">
              </ul>
              
              <input type="submit" class="btn btn-primary d-grid w-100" value="Renovar"></input>
            </div>
            </form>
          </div>
        </div>
        <?php 
if (isset($_POST['cupom'])) {
    $cupom = anti_sql($_POST['cupom']);
    $_SESSION['cupom'] = $cupom;
    
    $sql = "SELECT * FROM cupons WHERE cupom = '$cupom'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['cupom'] = $row['cupom'];
        $_SESSION['desconto'] = $row['desconto'];
        echo "<script>swal('Cupom Aplicado Com Sucesso!', 'Desconto de $_SESSION[desconto]%', 'success');</script>";
        echo "<script>setTimeout(\"location.href = 'processando.php';\",1500);</script>";
        exit;
    } else {
        echo "<script>window.location.href = 'processando.php';</script>";
    }
}
    
?>

                                                <hr>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <script src="../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
    <script src="../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
    <script src="../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../../app-assets/js/scripts/configs/vertical-menu-dark.js"></script>
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <script src="../../../app-assets/js/scripts/components.js"></script>
    <script src="../../../app-assets/js/scripts/footer.js"></script>
</body>
</html>
