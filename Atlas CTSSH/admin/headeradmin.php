<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">

<head>
  
<?php
    error_reporting(0);
    session_start();
    if(!isset($_SESSION['login'])){
      header('Location: ../index.php');
      exit();
    }
    include_once("../atlas/conexao.php");
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
      if ($_SESSION['login'] == 'admin') {
      }else{
        echo "<script>alert('Você não tem permissão para acessar essa página!');window.location.href='../logout.php';</script>";
        exit();
      }
      
    $sql = "SELECT * FROM configs WHERE id = '1'";
    $result = $conn -> query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $nomepainel = $row["nomepainel"];
            $logo = $row["logo"];
            $icon = $row["icon"];
            $csspersonali = $row["corfundologo"];
        }
    }
      
    $sqltoken = "ALTER TABLE accounts ADD COLUMN IF NOT EXISTS tokenvenda TEXT NOT NULL DEFAULT '0'";
    mysqli_query($conn, $sqltoken);
    //deletar tabela limiter
    $sqldeltoken = "DROP TABLE IF EXISTS limiter";
    mysqli_query($conn, $sqldeltoken);
$sql2 = "SELECT * FROM atribuidos WHERE byid = '".$_SESSION['iduser']."'";
//contar quantos atribuidos tem
$result2 = mysqli_query($conn, $sql2);
$totalrevenda = mysqli_num_rows($result2);

$sql452 = "SELECT * FROM accounts WHERE id = '".$_SESSION['iduser']."'";
$result452 = mysqli_query($conn, $sql452);
$row452 = mysqli_fetch_assoc($result452);
$tokenvenda = $row452['tokenvenda'];
$idcategoriacompra = $row452['tempo'];
$acesstoken = $row452['accesstoken'];
$acesstokenpaghiper = $row452['acesstokenpaghiper'];
if ($idcategoriacompra == null || $idcategoriacompra == '') {
    $updatecategoria = "UPDATE accounts SET tempo = '1' WHERE id = '".$_SESSION['iduser']."'";
    mysqli_query($conn, $updatecategoria);
}
$sqltoken2 = "ALTER TABLE pagamentos
ADD COLUMN IF NOT EXISTS formadepag TEXT DEFAULT '1',
ADD COLUMN IF NOT EXISTS tokenpaghiper TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken2);  

$sql3 = "SELECT * FROM ssh_accounts WHERE byid = '".$_SESSION['iduser']."'";
//contar quantos ssh tem
$result3 = mysqli_query($conn, $sql3);
$totalusuarios = mysqli_num_rows($result3);

$sql44 = "SELECT * FROM ssh_accounts";
//contar quantos ssh tem
$result44 = mysqli_query($conn, $sql44);
$totalusuariosglobal = mysqli_num_rows($result44);

$slq3 = "SELECT sum(valor) AS valor  FROM pagamentos where byid='".$_SESSION['iduser']."' and status='Aprovado'";
$result3 = mysqli_query($conn, $slq3);
$row3 = mysqli_fetch_assoc($result3);
$totalvendido = $row3['valor'];
$totalvendido = number_format($totalvendido, 2, ',', '.');
//somar numero de revendas

$sql2 = "SELECT * FROM atribuidos";
//contar quantos atribuidos tem
$result2 = mysqli_query($conn, $sql2);
$totalrevendedores = mysqli_num_rows($result2);

//soma o total de servidores no banco de dados
$sql4 = "SELECT * FROM servidores";
$result4 = mysqli_query($conn, $sql4);
$totalservidores = mysqli_num_rows($result4);

//soma o total de logs no banco de dados
$sql5 = "SELECT * FROM logs";
$result5 = mysqli_query($conn, $sql5);
$totallogs = mysqli_num_rows($result5);




date_default_timezone_set('America/Sao_Paulo');
$data = date('d/m/Y');

//consulta todos pagamentos de hoje d/m/y h:m:s
$slq4 = "SELECT sum(valor) AS valor  FROM pagamentos where byid='".$_SESSION['iduser']."' and status='Aprovado' and data >= '$data 00:00:00' and data <= '$data 23:59:59'";
$result4 = mysqli_query($conn, $slq4);
$row4 = mysqli_fetch_assoc($result4);
$totalvendidohoje = $row4['valor'];
/* if ($totalvendidohoje == null) {
  $totalvendidohoje = 0;
} */
//consulta usuarios expirados
$data = date('Y-m-d H:i:s');
$slq5 = "SELECT * FROM ssh_accounts WHERE byid = '".$_SESSION['iduser']."' and expira < '$data'";
$result5 = mysqli_query($conn, $slq5);
$totalvencidos = mysqli_num_rows($result5);
//todos usuarios da tabela ssh_accounts que estao status Online
$sql16 = "SELECT * FROM ssh_accounts WHERE status = 'Online' AND byid != '".$_SESSION['iduser']."'";
$result16 = mysqli_query($conn, $sql16);
$row16 = mysqli_fetch_assoc($result16);
$totalonline = mysqli_num_rows($result16);


if ($totalonline == null) {
  $totalonline = 0;
}
if ($totalvendido == null) {
  $totalvendido = 0;
}

$sql17 = "SELECT * FROM ssh_accounts WHERE status = 'Online' AND byid = '".$_SESSION['iduser']."'";
$result17 = mysqli_query($conn, $sql17);
if ($result17->num_rows > 0) {
   //quantidade de usuarios online
  $seusonlines = mysqli_num_rows($result17);
  }else{
    $seusonlines = 0;
  }


//verifica se a tabela access_token existe se nao existir, ele cria
$sqltoken = "ALTER TABLE accounts
    ADD COLUMN IF NOT EXISTS acesstokenpaghiper TEXT DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS formadepag TEXT DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS tokenpaghiper TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken);
$sqltoken2 = "ALTER TABLE pagamentos
ADD COLUMN IF NOT EXISTS formadepag TEXT DEFAULT '1',
ADD COLUMN IF NOT EXISTS tokenpaghiper TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken2);  
$sqltoken3 = "ALTER TABLE atribuidos
ADD COLUMN IF NOT EXISTS valormensal TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken3);  
$sqltoken4 = "ALTER TABLE ssh_accounts
ADD COLUMN IF NOT EXISTS valormensal TEXT DEFAULT NULL";
mysqli_query($conn, $sqltoken4); 


mysqli_query($conn, $sqltoken);


date_default_timezone_set('America/Sao_Paulo');
//data atual menos 1 hora
$data = date('d-m-Y H:i:s', strtotime('-1 hour'));
$slq5 = "DELETE FROM pagamentos WHERE status = 'Aguardando Pagamento' and data < '$data'";
$result5 = mysqli_query($conn, $slq5);

    ?>
    <?php
date_default_timezone_set('America/Sao_Paulo');
session_start();
if (!isset($_SESSION['token'])) {
	include_once '../atlas/conexao.php';
}

if (!file_exists('suspenderrev.php')) {
  exit ("<script>alert('Token Invalido!');</script>");
}else{
  include_once 'suspenderrev.php'; 
}
?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title><?php echo $nomepainel; ?> - Painel Administrativo</title>
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
                        <div class="header-msg">
						<h7 class="header-title">
						<marquee class="dhr-marquee" direction="left">Seja Bem Vindo ao <?php echo $nomepainel; ?></marquee></h7>
						</div> <!-- FIM -->
                       
                    </div>
                    <li class="nav-item dropdown d-none d-lg-block">
            <a class="btn btn-outline-success" href="criarteste.php">+ Teste Rapido</a>
          </li>
                    <ul class="nav navbar-nav float-right">    
                        </li>
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
    <br>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="home.php">
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
                <li class=" nav-item"><a href="home.php"><i class="menu-livicon" data-icon="desktop"></i><span class="menu-title" data-i18n="Dashboard">Pagina Inicial</span></a>

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
                        <li><a href="listaglobaluser.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Lista de Todos Usuarios</span></a>
                        </li>
                        <li><a href="listaexpirados.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Lista de Expirados</span></a>
                        </li>
                        <li><a href="onlines.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Lista de Onlines</span></a>
                        </li>
                        <li><a href="limiter.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Listar Limiter</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="menu-livicon" data-icon="users"></i><span class="menu-title">Revendedores</span></a>
                    <ul class="menu-content">
                        <li><a href="criarrevenda.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" >Criar Revenda</span></a>
                        </li>
                        <li><a href="listarrevendedores.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Listar Revendedores</span></a>
                        </li>
                        <li><a href="listartodosrevendedores.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Listar Todos Revendedores</span></a>
                        </li>
                    </ul>
                </li>
                <!-- <li class=" nav-item"><a href="#"><i class="menu-livicon" data-icon="box-add"></i><span class="menu-title">Planilha Clientes</span></a>
                    <ul class="menu-content">
                        <li><a href="criarrevenda.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" >Clientes</span></a>
                        </li>
                        <li><a href="listarrevendedores.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Planos</span></a>
                        </li>
                        <li><a href="listartodosrevendedores.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Mensagens</span></a>
                        </li>
                    </ul>
                </li> -->
                <li class=" navigation-header"><span>Pagamentos</span>
                </li>
                <li class=" nav-item"><a href="#"><i class="menu-livicon" data-icon="us-dollar"></i><span class="menu-title">Pagamentos</span></a>
                <ul class="menu-content">
                    <li><a href="formaspag.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Configurar Pagamentos</span></a>
                </li>
                <li><a href="listadepag.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Typography">Listar Seus Pagamentos</span></a>
            </li>
            <li><a href="listadetodospag.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Text Utilities">Listar Todos Pagamentos</span></a>
        </li>
        <li><a href="cupons.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Syntax Highlighter">Cupom de Desconto</span></a>
    </li>
    

</ul>
</li>

<li class=" navigation-header"><span>Servidores</span>
                </li>
<li class=" nav-item"><a href="servidores.php"><i class="menu-livicon" data-icon="cpu"></i><span class="menu-title">Servidores</span></a>
</li>

<li class=" nav-item"><a href="gerarpay.php"><i class="menu-livicon" data-icon="diagram"></i><span class="menu-title">Gerar Payload</span></a>
</li>
<li class="nav-item"><a href="geradorany.php"><i class="menu-livicon" data-icon="briefcase"></i><span class="menu-title textmod">Gerador AnyVpn</span></a>



<li class=" navigation-header"><span>Logs</span>
                </li>
                <li class=" nav-item"><a href="logs.php"><i class="menu-livicon" data-icon="priority-low"></i><span class="menu-title">Logs</span></a>
                </li>

                <li class=" navigation-header"><span>Grupo Suporte</span>
                </li>
                <li class=" nav-item"><a href="https://chat.whatsapp.com/ETGQKEqmQNWDhrP5j2nqLK"><i class="menu-livicon" data-icon="phone"></i><span class="menu-title">Grupo 1</span></a>
                </li>
                <li class=" nav-item"><a href="https://chat.whatsapp.com/JOfLVycZZed2GG9unthy58"><i class="menu-livicon" data-icon="phone"></i><span class="menu-title">Grupo 2</span></a>
                </li>
                <li class=" navigation-header"><span>Configurações</span>
                <li class=" nav-item"><a href="editconta.php"><i class="menu-livicon" data-icon="wrench"></i><span class="menu-title">Conta</span></a>
                </li>
                </li>
                
                <li class=" nav-item"><a href="configpainel.php"><i class="menu-livicon" data-icon="settings"></i><span class="menu-title">Editar Painel</span></a>
                </li>
                <li class=" nav-item"><a href="editorpainel.php"><i class="menu-livicon" data-icon="brush"></i><span class="menu-title">Editor Css</span></a>
                </li>
                <li class=" nav-item"><a href="whatsconect.php"><i class="menu-livicon" data-icon="bell"></i><span class="menu-title">WhatsApp</span></a>
                </li>
                <li class=" nav-item"><a href="checkuserconf.php"><i class="menu-livicon" data-icon="unlink"></i><span class="menu-title" data-i18n="Form Wizard">CheckUser</span></a>
                </li>
                <li class=" nav-item"><a href="../logout.php"><i class="menu-livicon" data-icon="morph-login2"></i><span class="menu-title" data-i18n="Form Validation">Sair</span></a>
                </li>
                
            </ul>
        </div>
    </div>
    
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
        
            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                
              </div>
            </div>
            <style>
            .card-body {
              border: 1px solid #ebedf2;
              border-radius: 0.25rem;
              /* sombra  */
              
  
  }
  .col-xl-3.col-sm-6.grid-margin.stretch-card {
    .animacao-mouse {
  transition: background-color 0.3s ease; /* Define a duração e o easing da transição */
}

.animacao-mouse:hover {
  background-color: red; /* Define a cor de fundo quando o mouse passar por cima */
}
  }
</style>
            <div class="row">
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card" >
                <div class="card" onclick="redirecionaroonlines()" >
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $totalonline ?>/<?php echo $seusonlines ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Onlines</p>
                        </div>
                        <script>
function redirecionaroonlines() {
    window.location.href = "onlines.php";
}
</script>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success ">
                          <a href="onlines.php" class="mdi mdi-arrow-top-right icon-item"></a>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Revendedores / Meus Onlines</h6>
                  </div>
                </div>
              </div>
              
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0">Versão</h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">4.5.6</p>
                          <p style='color: #ffff; margin-left: 20px; margin-bottom: 1px;  font-size: 12px;'>Vencimento: <?php echo $_SESSION['datavencimentotoken']; ?></p>
                        </div>
                        
                        <!-- <button type="button" class="badge rounded-pill bg-success" onclick="atualizar()">ATUALIZAR</button> -->
                        <button type="button" class="badge rounded-pill bg-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="feather icon-settings"></i> Opções </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" onclick="atualizar('ultima')">Atualizar Ultima Versão</a>
                                <a class="dropdown-item" onclick="atualizar('4.4.2')">Voltar para Versão 4.4.2</a>
                                <a class="dropdown-item" onclick="atualizar('3.8.6')">Voltar para Versão 3.8.6</a>
                      </div></div>
                      <div class="col-3">
                        <div class="icon icon-box-success ">
                        <i class="mdi mdi-format-vertical-align-bottom"></i>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
              <?php
              //gerar senha
              function gerar_token()
              {
                  $tokenvenda = md5(uniqid(random_int(0, PHP_INT_MAX), true));
                  return $tokenvenda;
              }
              $_SESSION['senhaatualizar'] = gerar_token();
              $domain = $_SERVER['HTTP_HOST'];
              /* verifica se tem https */
              if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
                  $protocol = 'https://';
              } else {
                  $protocol = 'http://';
              }

?>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

              <script src="../app-assets/sweetalert.min.js"></script>
            <script>
            function atualizar(versao) {
    var senhaatualizar = "<?php echo $_SESSION['senhaatualizar'] ?>";
    var domain = "<?php echo $domain ?>";

    // Exibe uma mensagem de confirmação antes de atualizar
    swal({
        title: "Deseja realmente atualizar?",
        text: "Ao clicar em OK, os dados serão atualizados.",
        icon: "warning",
        buttons: ["Cancelar", "OK"],
        dangerMode: true,
    }).then(function (confirm) {
        if (confirm) {
            // O usuário confirmou a atualização, envie a solicitação AJAX
            $.ajax({
                url: '<?php echo $protocol ?><?php echo $domain ?>/atualizar.php',
                type: 'POST',
                data: {
                    senhaatualizar: senhaatualizar,
                    domain: domain,
                    versao: versao
                },
                success: function (data) {
                    if (data) {
                        // Mostra a mensagem de sucesso
                        swal("Atualizado com sucesso!", "Clique em OK para atualizar a página!", "success").then(function () {
                            location.reload();
                        });
                    } else {
                        // Mostra a mensagem de erro
                        swal("Erro na atualização!", "Houve um erro ao atualizar os dados.", "error");
                    }
                },
                error: function () {
                    // Mostra a mensagem de erro em caso de falha na requisição AJAX
                    swal("Erro na atualização!", "Houve um erro na requisição.", "error");
                }
            });
        }
    });
}
</script>

              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card" onclick="redirecionarrevendedores()">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <script>
function redirecionarrevendedores() {
    window.location.href = "listarrevendedores.php";
}
</script>
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $totalrevenda ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Revendedores</p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <a href="listarrevendedores.php" class="mdi mdi-arrow-top-right icon-item"></a>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Revendedores do Admin</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
              <div class="card" >
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $totalusuariosglobal ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Global</p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Todos Usuarios</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card" onclick="redirecionarusuarios()">
                  <div class="card-body">
                    <div class="row">
                      <script>
function redirecionarusuarios() {
    window.location.href = "listarusuarios.php";
}
</script>
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $totalusuarios ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Usuarios</p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <a href="listarusuarios.php" class="mdi mdi-arrow-top-right icon-item"></a>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Seus Usuarios</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card" onclick="redirecionarservidores()">
                  <div class="card-body">
                    <div class="row">
                      <script>
function redirecionarservidores() {
    window.location.href = "servidores.php";
}
</script>
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $totalservidores ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Servidores</p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Total de Servidores</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card" onclick="redirecionarrevendedores()">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <script>
function redirecionarrevendedores() {
    window.location.href = "listarrevendedores.php";
}
</script>
                            <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $totalrevendedores ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Revendedores</p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <a href="listarrevendedores.php" class="mdi mdi-arrow-top-right icon-item"></a>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Revendedores no Painel</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $totalvendido ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">R$</p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Total Vendido</h6>
                  </div>
                </div>
              </div>
              
              </div>
              
              <?php if ($acesstoken != '' || $acesstokenpaghiper != '') { ?>
              <div class="content-body">
                    <section id="divider-colors">
                            <div class="col-12">
                            <div class="card"style="border: 2px solid #5A8DEF;">
                                <div class="card-header">
                                    <h4 class="card-title">Link de Compra</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <p>
                                            Use esses Links para seus clientes comprarem seus produtos.
                                        </p>
                                        <div class="divider divider-primary">
                                            <div class="divider-text">Para Novos Revendedores</div>
                                            <input type="text" class="form-control" value="https://<?php echo $_SERVER['HTTP_HOST'];  ?>/revenda.php?token=<?php echo $tokenvenda; ?>" readonly>
                                        </div>
                                        <div class="divider divider-primary">
                                            <div class="divider-text">Link Bot Vendas</div>
                                            <input type="text" class="form-control" value="https://<?php echo $_SERVER['HTTP_HOST'];  ?>/comprar.php?token=<?php echo $tokenvenda; ?>" readonly>
                                        </div>
                                        <div class="divider divider-primary">
                                            <div class="divider-text">Link Teste Automatico</div>
                                            <input type="text" class="form-control" value="https://<?php echo $_SERVER['HTTP_HOST'];  ?>/criarteste.php?token=<?php echo $tokenvenda; ?>" readonly>
                                        </div>
                                              <form action="headeradmin.php" method="post">
                                        <div class="divider divider-warning">
                                            <button class="btn btn-warning" type="submit" name="gerarlink" id="gerarlink">Gerar Novo Link</button>
                                        </div>
                                        <div class="divider divider-success">
                                            <div class="divider-text">Id da Categoria Para Compra Automatica</div>
                                            <input type="text" class="form-control" name="categoriacompra" value="<?php echo $idcategoriacompra; ?>">
                                        </div>
                                        <div class="divider divider-warning">
                                            <button class="btn btn-warning" type="submit" name="salvarcate" id="salvarcate">Salvar Categoria</button>
                                        
                                        </form>

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
                                        
                                        if(isset($_POST['gerarlink'])){
                                            $codigo = random_int(100000000000,999999999999);
                                            $id = $_SESSION['iduser'];
                                            $categoriacompra = $_POST['categoriacompra'];
                                            //anti sql
                                            $codigo = anti_sql($codigo);
                                            $id = anti_sql($id);
                                            $categoriacompra = anti_sql($categoriacompra);
                                            $sql = "UPDATE accounts SET tokenvenda = '$codigo', tempo = '$categoriacompra' WHERE id = '$id'";
                                            $result = $conn -> query($sql);
                                            echo "<meta http-equiv='refresh' content='0'>";
                                        }
                                        if(isset($_POST['salvarcate'])){
                                            $id = $_SESSION['iduser'];
                                            $categoriacompra = $_POST['categoriacompra'];
                                            //anti sql
                                            $id = anti_sql($id);
                                            $categoriacompra = anti_sql($categoriacompra);
                                            $sql = "UPDATE accounts SET tempo = '$categoriacompra' WHERE id = '$id'";
                                            $result = $conn -> query($sql);
                                            echo "<meta http-equiv='refresh' content='0'>";
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                        </div>
                </section>
                <!-- Divider Colors Ends -->
            </div>
        </div>
        <?php } ?>



        

                    
                        
                <!-- table Transactions start -->
                <section id="table-transactions">
                    <div class="card">
                        <div class="card-header">
                            <!-- head -->
                            <h5 class="card-title">Servidores</h5>
                            <!-- Single Date Picker and button -->
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <input type="text" class="form-control" placeholder="Pesquisar" aria-label="Pesquisar" aria-describedby="button-addon2" id="pesquisar" onkeyup="pesquisar()">
                                </ul>
                            </div>

                            


                        </div>
                        <!-- datatable start -->
                                <?php
$sql = "SELECT * FROM servidores ";
         $result = $conn -> query($sql);
?>
                        <div class="table-responsive">
                            <table id="table-extended-transactions" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Categoria</th>
                                        <th>ip</th>
                                        <th>tamanho</th>
                                        <th>Onlines</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($user_data = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        
                                        echo "<td class='text-bold-600'>" . $user_data['nome'] . "</td>";
                                        echo "<td>" . $user_data['subid'] . "</td>";
                                        echo "<td>" . $user_data['ip'] . "</td>";
                                        echo "<td>".$user_data['serverram']." RAM/ " . $user_data['servercpu'] . " CPU</td>";
                                        echo "<td>" . $user_data['onlines'] . "</td>";
                            
                                        echo "</tr>";
                                    }
                                    ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div></div>

            </div>
        </div>
    </div>

    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

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
<script>
setInterval(() => {
  fetch('suspenderauto.php', {
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
</script>

</html>