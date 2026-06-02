<?php error_reporting(0);
session_start();
include_once("atlas/conexao.php");
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if(! $conn ) {

  echo $conn->connect_error;
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
             $csspersonali = $row["corfundologo"];
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
                                                <h4 class="text-center mb-2"><?php echo $nomepainel; ?> - Renovação</h4>
                                            </div>
                                        </div>
                                        <?php

if (isset($_POST['submit'])) {
  $login = mysqli_real_escape_string($conn, $_POST['login']);
  $senha = mysqli_real_escape_string($conn, $_POST['senha']);
  
  // Verificações de segurança
  if (strpos($login, "'") !== false || strpos($senha, "'") !== false) {
    echo "<div class='alert alert-danger alert-dismissible mb-2' role='alert'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span>
      </button>
      <center>
      <strong>Erro!</strong> Caracteres inválidos detectados.
      </div>
      ";
  }else{
  
  $sql = "SELECT * FROM ssh_accounts WHERE login = ? AND senha = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "ss", $login, $senha);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['id'] = $row['id'];
    $_SESSION['login'] = $row['login'];
    $_SESSION['senha'] = $row['senha'];
    $_SESSION['byid'] = $row['byid'];
    $_SESSION['limite'] = $row['limite'];
    $_SESSION['expira'] = $row['expira'];
    $_SESSION['categoria'] = $row['categoriaid'];
      echo "<div class='alert alert-success alert-dismissible mb-2' role='alert'>
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
        </button>
        <center>
        <strong>Sucesso!</strong> Crendenciais Corretas, Redirecionando...
        </div>
        <meta http-equiv='refresh' content='2; url=usuario/index.php'>
        ";
  } else {
    echo "<div class='alert alert-danger alert-dismissible mb-2' id='alert' role='alert'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span>
    </button>
    <center>
    <strong>Erro!</strong> Login ou Senha Incorretos!
  </div>";
  echo "<script>setTimeout(function(){ $('#alert').alert('close'); }, 3000);</script>";
  
  }
}
}

?>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="divider">
                                                    <div class="divider-text text-uppercase text-muted"><small>Renovação Usuario</small>
                                                    
                                                    </div>
                                                    <p class="card-description">Preencha os campos abaixo para renovar seu usuario.</p>
                                                </div>
                                                <form action="renovar.php" method="POST">
                                                    <div class="form-group mb-50">
                                                        <label class="text-bold-600">Login</label>
                                                        <input type="text" class="form-control" name="login" placeholder="Seu Login"></div>
                                                    <div class="form-group">
                                                        <label class="text-bold-600">Senha</label>
                                                        <input type="password" class="form-control" name="senha" placeholder="Sua Senha">
                                                    </div>
                                                    <div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                                                        <div class="text-left">
                                                            <div></div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary glow w-100 position-relative" name="submit">Entrar<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                                </form>
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
