    
<script src="../app-assets/sweetalert.min.js"></script>
<?php 
error_reporting(0);
session_start();
//se a sessão não existir, redireciona para o login
if(!isset($_SESSION['login']) and !isset($_SESSION['senha'])){
    session_destroy();
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('location:../index.php');
}

include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
include_once 'headeradmin2.php';

$sql = "SELECT * FROM configs";
$result = $conn -> query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $nomepainel = $row["nomepainel"];
        $logo = $row["logo"];
        $icon = $row["icon"];
        $imagelogin = $row["imglogin"];
        $cornavsuperior = $row["cornavsuperior"];
        $corfundologo = $row["corfundologo"];
        $corcard = $row["corcard"];
        $linkapp = $row["cortextcard"];
        $tempolimiter = $row["corletranav"];
        $limiter = $row["corbarranav"];
    }
}

?>
          <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <p class="text-primary">Aqui Você Pode Editar os Detalhes do Painel.</p>
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section id="dashboard-ecommerce">
                    <div class="row">
                <section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Editar Painel</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-horizontal" action="configpainel.php" method="POST">
                                            <div class="form-body">
                                                <div class="row">
                                                  

                                                    <div class="col-md-4">
                                                        <label>Nome do Painel <code>(maximo: 12 caracteres)</code></label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="nomepainel" placeholder="Ex: Atlas Painel" value="<?php echo $nomepainel; ?>" maxlength="12">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Logo Pagina de Login <code>(tamanho: 488x113)</code></label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="imagemlogo" placeholder="Ex: https://i.imgur.com/1Q2w3e4.png" value="<?php echo $logo; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Icone Painel <code>(tamanho: 372x362)</code></label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="text" class="form-control" name="icon" placeholder="Ex: https://i.imgur.com/1Q2w3e4.png" value="<?php echo $icon; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Texto Usuario Criado</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input type="text" class="form-control" name="applink" placeholder="Ex: Proibido Uso de Torrent" value="<?php echo $linkapp; ?>">
                                                    </div>
                                                     <div class="col-md-4">
                                                        <label>Limiter</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                                                    <?php
                                                     if ($limiter == "1") {
                                                        echo '<select class="form-control" name="limiter">
                                                        <option value="1">Ativado</option>
                                                        <option value="0">Desativado</option>
                                                    </select>';
                                                    } else {
                                                        echo '<select class="form-control" name="limiter">
                                                        <option value="0">Desativado</option>
                                                        <option value="1">Ativado</option>
                                                    </select>';
                                                    } 
                                                    ?>
                                                    </div> 
                                                    <div class="col-md-4">
                                                        <label>Tempo em que o usuario pode usar a mais antes de ser deletado</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="tempolimiter" placeholder="Ex: 10" value="<?php echo $tempolimiter; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Suspender Auto</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                                                    <?php
                                                     if ($imagelogin == "1") {
                                                        echo '<select class="form-control" name="suspenderauto">
                                                        <option value="1">Ativado</option>
                                                        <option value="0">Desativado</option>
                                                    </select>';
                                                    } else {
                                                        echo '<select class="form-control" name="suspenderauto">
                                                        <option value="0">Desativado</option>
                                                        <option value="1">Ativado</option>
                                                    </select>';
                                                    } 
                                                    ?>
                                                    </div> 
                                            
                                                    <div class="col-sm-12 d-flex justify-content-end">
                                                        <button class="btn btn-primary mr-1 mb-1" name="reset">Resetar</button>
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1" name="salvar">Editar</button>
                                                        <a href="home.php" class="btn btn-light-secondary mr-1 mb-1">Voltar</a>
                                                    </div>
                                                </div>
                                            </div>
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
                                    
                    if (isset($_POST['salvar'])) {
                      $imagemlogo = $_POST['imagemlogo'];
                      $icon = $_POST['icon'];
                      $imglogin = $_POST['imglogin'];
                      $nomepainel = $_POST['nomepainel'];
                      $applink = $_POST['applink'];
                      $suspenderauto = $_POST['suspenderauto'];
                      $limiter = $_POST['limiter'];
                        $tempolimiter = $_POST['tempolimiter'];
                        //anti sql injection
                        $imagemlogo = anti_sql($imagemlogo);
                        $icon = anti_sql($icon);
                        $imglogin = anti_sql($imglogin);
                        $nomepainel = anti_sql($nomepainel);
                        $suspenderauto = anti_sql($suspenderauto);
                        $limiter = anti_sql($limiter);
                        $tempolimiter = anti_sql($tempolimiter);
                        
                      $sql = "UPDATE configs SET nomepainel='$nomepainel', logo='$imagemlogo', icon='$icon', imglogin='$imglogin', corbarranav='$limiter', cortextcard='$applink', corletranav='$tempolimiter', imglogin='$suspenderauto' WHERE id='1'";
                      if (mysqli_query($conn, $sql)) {
                        echo "<script>swal('Sucesso!', 'Configurações Salvas!', 'success').then((value) => {
                          window.location.href = 'configpainel.php';
                        });</script>";
                      } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                      }
                    }
                    if (isset($_POST['reset'])) {
                      $sql = "UPDATE configs SET nomepainel='Atlas Painel', logo='https://cdn.discordapp.com/attachments/1051302877987086437/1070581060821340250/logo.png?ex=65e954cf&is=65d6dfcf&hm=3a1ac71b2bfd8ebe60bb1be2c7f5ff932792db6d3302df6dd24ada53b5bce024&', icon='https://cdn.discordapp.com/attachments/1051302877987086437/1070581061014274088/logo-mini.png?ex=65e954cf&is=65d6dfcf&hm=0101e733116e025664cd6d0baa1c0b29e31590bd4e465b8b0e1b74a90351bb12&', imglogin='0', corbarranav='0', cortextcard='', corletranav='5', imglogin='0' WHERE id='1'";
                      if (mysqli_query($conn, $sql)) {
                        echo "<script>swal('Sucesso!', 'Configurações Resetadas!', 'success').then((value) => {
                          window.location.href = 'configpainel.php';
                        });</script>";
                      } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                      }
                    }
                    ?>
                                </div>
                            </div>
                        </div>
