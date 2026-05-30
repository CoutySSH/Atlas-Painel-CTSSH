
<?php
//time zone
error_reporting(0);
session_start();
// Verifica e atualiza a atividade da sessão
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1200)) {
    echo "<script>alert('Sessão expirada por inatividade!');</script>";
    session_unset();
    session_destroy(); 
    echo "<script>setTimeout(function(){ window.location.href='../index.php'; }, 500);</script>";
    exit();
}

$_SESSION['last_activity'] = time();

date_default_timezone_set('America/Sao_Paulo');

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
?>
<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
   <!-- BEGIN: Vendor CSS-->
   <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css">
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
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <script src="../app-assets/sweetalert.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../../../atlas-assets/css/style.css">
    <!-- END: Custom CSS-->
<head>
<?php
    

    if(!isset($_SESSION['login'])){
      header('Location: ../index.php');
      exit();
    }
    include_once("../atlas/conexao.php");
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
      
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
    if($_SESSION['login'] == 'admin'){
        header('Location: ../admin/home.php');
      }
          if(!isset($_SESSION['login']) and !isset($_SESSION['senha'])){
              session_destroy();
              unset($_SESSION['login']);
              unset($_SESSION['senha']);
              header('location: ../index.php');
              exit();
          }
          ?>
        
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title><?php echo $nomepainel; ?> - Painel Revendedor</title>
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
.start {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999; /* Z-index alto para ficar acima de outros elementos */
  }
  .blurry-background {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  backdrop-filter: blur(5px); /* Ajuste o valor para controlar a intensidade do desfoque */
  z-index: -1; /* Coloque o elemento abaixo do conteúdo do body */
}


    /* Estilos para o SVG Gegga */
    .start .gegga {
      width: 0;
    }
    /* Estilos para o SVG Snurra */
    .start .snurra {
      filter: url(#gegga);
    }
    .start .stopp1 {
      stop-color: #5A8DEE;
    }
    .start .stopp2 {
      stop-color: #6b5aee;
    }
    .start .halvan {
      animation: Snurra1 10s infinite linear;
      stroke-dasharray: 180 800;
      fill: none;
      stroke: url(#gradient);
      stroke-width: 23;
      stroke-linecap: round;
    }
    .start .strecken {
      animation: Snurra1 3s infinite linear;
      stroke-dasharray: 26 54;
      fill: none;
      stroke: url(#gradient);
      stroke-width: 23;
      stroke-linecap: round;
    }
    /* Estilos para o SVG Skugga */
    .start .skugga {
      filter: blur(5px);
      opacity: 0.3;
      position: absolute;
      transform: translate(3px, 3px);
    }
    @keyframes Snurra1 {
      0% {
        stroke-dashoffset: 0;
      }
      100% {
        stroke-dashoffset: -403px;
      }
    }

    </style>
<style>
        <?php echo $csspersonali; ?>
    </style>
<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout">
<div id='start' class="start" >
<svg class="gegga">
      <defs>
        <filter id="gegga">
          <feGaussianBlur in="SourceGraphic" stdDeviation="7" result="blur" />
          <feColorMatrix
            in="blur"
            mode="matrix"
            values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 20 -10"
            result="inreGegga"
          />
          <feComposite in="SourceGraphic" in2="inreGegga" operator="atop" />
        </filter>
      </defs>
    </svg>
<svg class="snurra" width="200" height="200" viewBox="0 0 200 200">
      <defs>
        <linearGradient id="linjärGradient">
          <stop class="stopp1" offset="0" />
          <stop class="stopp2" offset="1" />
        </linearGradient>
        <linearGradient
          y2="160"
          x2="160"
          y1="40"
          x1="40"
          gradientUnits="userSpaceOnUse"
          id="gradient"
          xlink:href="#linjärGradient"
        />
      </defs>
      <path
        class="halvan"
        d="m 164,100 c 0,-35.346224 -28.65378,-64 -64,-64 -35.346224,0 -64,28.653776 -64,64 0,35.34622 28.653776,64 64,64 35.34622,0 64,-26.21502 64,-64 0,-37.784981 -26.92058,-64 -64,-64 -37.079421,0 -65.267479,26.922736 -64,64 1.267479,37.07726 26.703171,65.05317 64,64 37.29683,-1.05317 64,-64 64,-64"
      />
      <circle class="strecken" cx="100" cy="100" r="64" />
    </svg>
    
</div>
<style>
  .back-button {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #007bff;
  color: #fff;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
  z-index: 9999;
  text-decoration: none;
}

.arrow {
  border: solid white;
  border-width: 0 3px 3px 0;
  display: inline-block;
  padding: 3px;
  transform: rotate(135deg);
  -webkit-transform: rotate(135deg);
}
</style>
<?php if (isset($_SESSION['admin564154156'])) { ?>
<form method="post" action="header2.php">
  <button type="submit" name="voltaradmin" class="back-button btn btn-outline-primary">
    <span class="arrow"></span>
  </button>
</form>
<?php } ?>

<?php 
//se voltaadmin e admin564154156 existir
if (isset($_POST['voltaradmin']) && isset($_SESSION['admin564154156'])) {
    $sqladmin = "SELECT * FROM accounts WHERE id = '1'";
    $resultadmin = $conn->query($sqladmin);
    $rowadmin = $resultadmin->fetch_assoc();
       //destrói as sessões existentes
       $_SESSION['login'] = $rowadmin['login'];
       $_SESSION['senha'] = $rowadmin['senha'];
       $_SESSION['iduser'] = $rowadmin['id']; 
       echo "<script>window.location.href='../admin/home.php';</script>";
} ?>
    <!-- BEGIN: Header-->
    <div class="header-navbar-shadow" id="inicialeditor"></div>
    <nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top navbar-dark">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon bx bx-menu"></i></a></li>
                        </ul>
                    </div>
                    <li class="nav-item dropdown d-none d-lg-block">
                <a class="btn btn-outline-success" href="criarteste.php">+ Teste Rapido</a>
              </li>
                    <ul class="nav navbar-nav float-right">
                       
                        
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none"><span class="user-name"><?php echo $_SESSION['login'] ?></span></div><span><div class="avatar bg-success mr-1">
                                            <div class="avatar-content">
                                            <?php
                                            $nome = $_SESSION['login'];
                                            $primeira_letra = $nome[0];
                                            echo $primeira_letra;
                                            ?>
                                            </div>
                                        </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right pb-0"><a class="dropdown-item" href="editconta.php"><i class="bx bx-user mr-50"></i> Conta</a>
                                <div class="dropdown-divider mb-0"></div><a class="dropdown-item" href="../logout.php"><i class="bx bx-power-off mr-50"></i> Sair</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="../home.php">
                <style>
                    .logo {
                      width: 170px;

                    }
                  </style>
                  <center>
                        <img class="logo" src="<?php echo $logo; ?>" /></center>
                        <!-- <h2 class="brand-text mb-0"><img class="logo" src="<?php echo $logo; ?>" /></h2> -->
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="bx bx-x d-block d-xl-none font-medium-4 primary"></i><i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary" data-ticon="bx-disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
                <li class=" nav-item"><a href="../home.php"><i class="menu-livicon" data-icon="desktop"></i><span class="menu-title" data-i18n="Dashboard">Pagina Inicial</span></a>

                </li>
                <li class=" navigation-header"><span>Usuarios</span>
                </li>
                <li class=" nav-item"><a href="#"><i class="menu-livicon" data-icon="user"></i><span class="menu-title">Gerenciar Usuarios</span></a>
                <ul class="menu-content">
                        <li><a href="criarusuario.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Criar Usuario</span></a>
                        </li>
                        <li><a href="criarteste.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Criar Teste</span></a>
                        </li>
                        <li><a href="listarusuarios.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Lista de Usuarios</span></a>
                        </li>
                        <li><a href="listaexpirados.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Lista de Expirados</span></a>
                        </li>
                        <li><a href="onlines.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Lista de Onlines</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="menu-livicon" data-icon="users"></i><span class="menu-title">Revendedores</span></a>
                    <ul class="menu-content">
                        <li><a href="criarrevenda.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" >Criar Revenda</span></a>
                        </li>
                        <li><a href="listarrevendedores.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Listar Revendedores</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" navigation-header"><span>Pagamentos</span>
                </li>
                <li class=" nav-item"><a href="#"><i class="menu-livicon" data-icon="us-dollar"></i><span class="menu-title">Pagamentos</span></a>
                <ul class="menu-content">
                    <li><a href="formaspag.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Configurar Pagamentos</span></a>
                </li>
                <li><a href="listadepag.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Typography">Listar Seus Pagamentos</span></a>
            </li>
            <li><a href="cupons.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Syntax Highlighter">Cupom de Desconto</span></a>
        </li>
        <li><a href="pagamento.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Text Utilities">Pagamento</span></a>
    </li>

</ul>
</li>
<li class=" navigation-header"><span>Whatsapp</span>
</li>
                <li class=" nav-item"><a href="whatsconect.php"><i class="menu-livicon" data-icon="bell"></i><span class="menu-title">WhatsApp</span></a>
                </li>
<li class=" navigation-header"><span>Logs</span>
                </li>
                <li class=" nav-item"><a href="logs.php"><i class="menu-livicon" data-icon="priority-low"></i><span class="menu-title">Logs</span></a>
                </li>
                <li class=" navigation-header"><span>Configurações</span>
                <li class=" nav-item"><a href="editconta.php"><i class="menu-livicon" data-icon="wrench"></i><span class="menu-title">Conta</span></a>
                </li>
                <li class=" nav-item"><a href="../logout.php"><i class="menu-livicon" data-icon="morph-login2"></i><span class="menu-title" data-i18n="Form Validation">Sair</span></a>
                </li>
                
            </ul>
        </div>
    </div>
    

            </div>
        </div>
    </div>
    <br>
    
   
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

    <script>
setInterval(() => {
  fetch('../admin/suspenderauto.php', {
    method: 'POST',
  })
    .then(response => {
      // Tratar a resposta, se necessário
    })
    .catch(error => {
      // Tratar o erro, se necessário
    });
}, 10000); // 10000 milissegundos = 10 segundos
</script>
<?php
        $sql = "SELECT * FROM configs";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
                $csspersonali = $row["corfundologo"];
                $textopersonali = $row["textoedit"];
        }
    
    // Recupere o conteúdo da variável $tradutor e converta-o para um array em PHP
    $tradutor = $textopersonali;
    $linhas = explode("\n", $tradutor);
    $substituicoes = array();
    foreach ($linhas as $linha) {
        $par = explode("=", $linha);
        if (count($par) === 2) {
            $textoOriginal = trim($par[0]);
            $textoSubstituto = trim($par[1]);
            $substituicoes[] = array('original' => $textoOriginal, 'substituto' => $textoSubstituto);
        }
    }
    ?>
<script>
window.addEventListener('DOMContentLoaded', function() {
        // Define as substituições desejadas
        var substituicoes = <?php echo json_encode($substituicoes); ?>;

        // Recursivamente percorre os elementos e substitui o texto dentro deles
        function percorrerElementos(elemento) {
            if (elemento.nodeType === Node.TEXT_NODE) {
                substituicoes.forEach(function(substituicao) {
                    elemento.textContent = elemento.textContent.replace(substituicao.original, substituicao.substituto);
                });
            } else {
                for (var i = 0; i < elemento.childNodes.length; i++) {
                    percorrerElementos(elemento.childNodes[i]);
                }
            }
        }

        // Obtém o elemento pai dos elementos onde deseja aplicar as substituições
        var paiElemento = document.getElementById('inicialeditor').parentNode;

        // Percorre os elementos dentro do pai e realiza as substituições
        percorrerElementos(paiElemento);
});
// Função para esconder o loading
function esconderLoading() {
  var loadingElement = document.getElementById('start');
  if (loadingElement) {
    loadingElement.style.display = 'none';
  }
}
function esconderbudd() {
  var loadingElement = document.getElementById('blurry-background');
    if (loadingElement) {
        loadingElement.style.display = 'none';
    }

}

window.addEventListener('load', esconderbudd);
// Associar a função ao evento load do objeto window
window.addEventListener('load', esconderLoading);
</script>
</body>
</html>
