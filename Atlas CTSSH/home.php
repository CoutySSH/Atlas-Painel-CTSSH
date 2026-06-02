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
       echo "<script>window.location.href='admin/home.php';</script>";
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
                      <!-- botao para voltar pro admin -->
                      

                <a class="btn btn-outline-success" href="atlas/criarteste.php">+ Teste Rapido</a>
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
                            <div class="dropdown-menu dropdown-menu-right pb-0"><a class="dropdown-item" href="atlas/editconta.php"><i class="bx bx-user mr-50"></i> Conta</a>
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
                        <li><a href="atlas/criarusuario.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Criar Usuario</span></a>
                        </li>
                        <li><a href="atlas/criarteste.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Criar Teste</span></a>
                        </li>
                        <li><a href="atlas/listarusuarios.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Lista de Usuarios</span></a>
                        </li>
                        <li><a href="atlas/listaexpirados.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Lista de Expirados</span></a>
                        </li>
                        <li><a href="atlas/onlines.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Lista de Onlines</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="menu-livicon" data-icon="users"></i><span class="menu-title">Revendedores</span></a>
                    <ul class="menu-content">
                        <li><a href="atlas/criarrevenda.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" >Criar Revenda</span></a>
                        </li>
                        <li><a href="atlas/listarrevendedores.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Listar Revendedores</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" navigation-header"><span>Pagamentos</span>
                </li>
                <li class=" nav-item"><a href="#"><i class="menu-livicon" data-icon="us-dollar"></i><span class="menu-title">Pagamentos</span></a>
                <ul class="menu-content">
                    <li><a href="atlas/formaspag.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">Configurar Pagamentos</span></a>
                </li>
                <li><a href="atlas/listadepag.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Typography">Listar Seus Pagamentos</span></a>
            </li>
            <li><a href="atlas/cupons.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Syntax Highlighter">Cupom de Desconto</span></a>
        </li>
        <li><a href="atlas/pagamento.php"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Text Utilities">Pagamento</span></a>
    </li>

</ul>
</li>
<li class=" navigation-header"><span>Whatsapp</span>
</li>
                <li class=" nav-item"><a href="atlas/whatsconect.php"><i class="menu-livicon" data-icon="bell"></i><span class="menu-title">WhatsApp</span></a>
                </li>
<li class=" navigation-header"><span>Logs</span>
                </li>
                <li class=" nav-item"><a href="atlas/logs.php"><i class="menu-livicon" data-icon="priority-low"></i><span class="menu-title">Logs</span></a>
                </li>
                <li class=" navigation-header"><span>Configurações</span>
                <li class=" nav-item"><a href="atlas/editconta.php"><i class="menu-livicon" data-icon="wrench"></i><span class="menu-title">Conta</span></a>
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
            <div class="row">
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card" onclick="window.location='atlas/onlines.php';">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $totalOnlineRevendedores ?>/<?php echo $seusonlines ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Onlines</p>
                        </div>
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
                <div class="card" onclick="window.location='atlas/listarrevendedores.php';">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $totalrevenda ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Revendedores</p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <a href="atlas/listarrevendedores.php" class="mdi mdi-arrow-top-right icon-item"></a>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Total de Revendedores</h6>
                  </div>
                </div>
              </div>
          
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card" onclick="window.location='atlas/listarusuarios.php';">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $totalusuarios ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Usuarios</p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <a href="listarrevendedores.php" class="mdi mdi-arrow-top-right icon-item"></a>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Total de Usuarios</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card" >
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
                    <h6 class="text-muted font-weight-normal">Total de Vendas</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $_SESSION['expira'] ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium"></p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <a href="listarusuarios.php" class="mdi mdi-arrow-top-right icon-item"></a>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Vencimento</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card" onclick="window.location='atlas/listaexpirados.php';">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $totalvencidos ?></h3>  
                          <p class="text-success ml-2 mb-0 font-weight-medium">Usuarios</p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Total de Vencidos</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo $_SESSION['limite'] ?></h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Seu Limite</p>   
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <a href="listarrevendedores.php" class="mdi mdi-arrow-top-right icon-item"></a>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Seu Limite Atual
                  </div>
                </div>
              </div>
              <?php
              if ($_SESSION['tipo'] == 'Seu Limite') {
                echo '<div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0">'.$restante.'</h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Restante</p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Limite Restante</h6>
                  </div>
                </div>
              </div>
              ';
              } else {
                echo '<div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card" >
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0">'.$total_logs.'</h3>
                          <p class="text-success ml-2 mb-0 font-weight-medium">Logs</p>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Total de Logs</h6>
                  </div>
                </div>
                </div>
              </div>';
              
              }
              ?>
              
              <?php if ($accesstoken != '' || $acesstokenpaghiper != '') { ?>
              <div class="content-body" style="width: 100%; margin: 0 auto;">
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
                                        <div class="divider divider-warning">
                                            <div class="divider-text">Link Bot Vendas</div>
                                            <input type="text" class="form-control" value="https://<?php echo $_SERVER['HTTP_HOST'];  ?>/comprar.php?token=<?php echo $tokenvenda; ?>" readonly>
                                        </div>
                                        <div class="divider divider-primary">
                                            <div class="divider-text">Link Teste Automatico</div>
                                            <input type="text" class="form-control" value="https://<?php echo $_SERVER['HTTP_HOST'];  ?>/criarteste.php?token=<?php echo $tokenvenda; ?>" readonly>
                                        </div>
                                        <form action="home.php" method="post">
                                        <div class="divider divider-warning">
                                            <button class="btn btn-warning" type="submit" name="gerarlink" id="gerarlink">Gerar Novo Link</button>
                                        
                                        </form>

                                        </div>
                                        <?php
                                        if(isset($_POST['gerarlink'])){
                                            $codigo = rand(100000000000,999999999999);
                                            $id = $_SESSION['iduser'];
                                            $sql = "UPDATE accounts SET tokenvenda = '$codigo' WHERE id = '$id'";
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
        



        

                    
                        
</div>
                    <div class="content-body">
                        
                <!-- table Transactions start -->
                <section id="table-transactions">
                    <div class="card">
                        <div class="card-header">
                            <!-- head -->
                            <h5 class="card-title">Pagamentos</h5>
                            <!-- Single Date Picker and button -->
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <input type="text" class="form-control" placeholder="Pesquisar" aria-label="Pesquisar" aria-describedby="button-addon2" id="pesquisar" onkeyup="pesquisar()">
                                </ul>
                            </div>

                            


                        </div>
                        <!-- datatable start -->
                                <?php
 $sql = "SELECT * FROM pagamentos  where byid = '".$_SESSION['iduser']."' ";
          $result = $conn -> query($sql);
?>
                        <div class="table-responsive">
                            <table id="table-extended-transactions" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th> Login </th>
                            <th> Id do Pagamento </th>
                            <th> Valor </th>
                            <th> Detalhes </th>
                            <th> Data e Hora </th>
                            <th> Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                          //result e result2
                          while ($user_data = mysqli_fetch_assoc($result)){
                          //converter expira para data
                          if($user_data['status'] == 'Aprovado'){
                            $status = "<label class='badge badge-success'>Aprovado</label>";
                            }else{
                            $status = "<label class='badge badge-danger'>Pendente</label>";
                            }
                          
                            
                          
                            echo "<td>".$user_data['login']."</td>";
                            echo "<td>".$user_data['idpagamento']."</td>";
                            echo "<td>".$user_data['valor']."</td>";
                            echo "<td>".$user_data['texto']."</td>";
                            echo "<td>".$user_data['data']."</td>";
                            echo "<td>".$status."</td>";
                            
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
        </div>
    </div>

            </div>
        </div>
    </div>
    <!-- BEGIN: Vendor JS-->
    <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <script src="../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
    <script src="../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
    <script src="../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../../app-assets/vendors/js/charts/apexcharts.min.js"></script>
    <script src="../../../app-assets/vendors/js/extensions/swiper.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../../app-assets/js/scripts/configs/vertical-menu-dark.js"></script>
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <script src="../../../app-assets/js/scripts/components.js"></script>
    <script src="../../../app-assets/js/scripts/footer.js"></script>
    <!-- END: Theme JS-->
    <script>
                             $(document).ready(function(){
                            $("#pesquisar").on("keyup", function() {
                            var value = $(this).val().toLowerCase();
                            $("#table-extended-transactions tr").filter(function() {
                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                            });
                            });
                            });
                                                    </script>
    <!-- BEGIN: Page JS-->
    <script src="../../../app-assets/js/scripts/pages/dashboard-ecommerce.js"></script>
    <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>
<script>
setInterval(() => {
  fetch('admin/suspenderauto.php', {
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
