<script src="../app-assets/sweetalert.min.js"></script>
<?php error_reporting(0);
if (session_status() == PHP_SESSION_NONE) { session_start(); }
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

?>

<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <p class="text-primary">Aqui Você Pode Pegar os Links do CheckUser.</p>
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
                                    <h4 class="card-title">CheckUser</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-horizontal" action="checkuserconf.php" method="POST">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                          <label>Observação</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <p class="card-description">ENTRE EM SUA HOSPEDAGEM ,EM SSL DEFINA PARA NÃO FORÇAR HTTPS</code></p>
                                                            <img src="https://cdn.discordapp.com/attachments/1051302877987086437/1070571617891131432/dddddd.png?ex=65e94c03&is=65d6d703&hm=a9b172502c08f5cdb2e4d89afde1300dc4bfac4eee2d438133adc36ae74cf93e&" width="80%">
                                                            <br>
                                                            <br>
                                                            </div>

                                                    <div class="col-md-4">
                                                        <label>Link AnyVpn</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input style="color: #ff0015;" type="text" class="form-control" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>/checkuser" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Link Conecta4g</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input style="color: #ff0015;" type="text" class="form-control" value="http://<?php echo $_SERVER['SERVER_NAME']; ?>/checkuser/conecta4g.php" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Link GLMOD</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input style="color: #ff0015;" type="text" class="form-control" value="http://<?php echo $_SERVER['SERVER_NAME']; ?>/checkuser/glmod.php?user=" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Link Dtunnel</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input style="color: #ff0015;" type="text" class="form-control" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>/checkuser/dtunnel.php?user=" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Link Dtunnel V2Ray</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input style="color: #ff0015;" type="text" class="form-control" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>/checkuser/dtunnelv2ray.php?user=" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Link Studio / M2</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input style="color: #ff0015;" type="text" class="form-control" value="/checkuser/atlant.php" readonly>
                                                    <img src="https://cdn.discordapp.com/attachments/1051302877987086437/1072314999583801456/Screenshot_1.png?ex=65e6692a&is=65d3f42a&hm=7299f392d43a2ff648ff92f6287ce0cd080445335d355c1e344cb27bcaae8ec2&" width="100%">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Link Miracle</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input style="color: #ff0015;" type="text" class="form-control" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>/checkuser" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Link Atlas App</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input style="color: #ff0015;" type="text" class="form-control" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Link Rocket</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                    <input style="color: #ff0015;" type="text" class="form-control" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>/checkuser/rocket.php" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Device ID</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <select class="form-control" name="deviceativo">
                                                            <?php
                                                            $sql = "SELECT * FROM configs WHERE id = 1";
                                                            $result = $conn->query($sql);
                                                            $row = $result->fetch_assoc();
                                                            if ($row['deviceativo'] == 1) {
                                                                echo '<option value="1">Ativado</option>';
                                                                echo '<option value="0">Desativado</option>';
                                                            } else {
                                                                echo '<option value="0">Desativado</option>';
                                                                echo '<option value="1">Ativado</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                        
                                                        <div class="col-sm-12 d-flex justify-content-end">
                                                        <button type="submit" name="salvar" class="btn btn-primary mr-1 mb-1">Salvar</button>
                                                        <a href="home.php" class="btn btn-light-secondary mr-1 mb-1">Voltar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <?php
                    if (isset($_POST['salvar'])) {
                            $deviceativo = $_POST['deviceativo'];
                            $stmt = $conn->prepare("UPDATE configs SET deviceativo = ? WHERE id = 1");
                            $stmt->bind_param("s", $deviceativo);
                            if ($stmt->execute()){
                            echo "<script>swal('Sucesso!', 'Configurações Atualizadas!', 'success').then((value) => {
                            window.location.href = 'checkuserconf.php';
                            });</script>";
                            }
                            }
                    ?>
                                </div>
                            </div>
                        </div>

                       