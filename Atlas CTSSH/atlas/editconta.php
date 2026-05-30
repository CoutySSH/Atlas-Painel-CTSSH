    
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
include('conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }

$id = $_SESSION['iduser'];
include_once 'header2.php';
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

//verifica se a coluna idtelegram existe se nao cria
$sql = "SELECT idtelegram FROM accounts WHERE id = '$id'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
  $sql = "ALTER TABLE accounts ADD idtelegram TEXT";
  $result = $conn->query($sql);
  $sql2 = "ALTER TABLE accounts ADD tempo TEXT";
  $result2 = $conn->query($sql2);
}

$sql = "SELECT * FROM accounts WHERE id = '$id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $login = $row['login'];
        $senha = $row['senha'];
        $tempotest = $row['mb'];
        $bottoken = $row['token'];
        $idtelegram = $row['idtelegram'];

    }
}




?>
<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- users edit start -->
                <section class="users-edit">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <ul class="nav nav-tabs mb-2" role="tablist">
                                    <li class="nav-item">
                                    <li class="nav-item">
                                        <h5>Editar Conta</h5>
                                    </li>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active fade show" id="account" aria-labelledby="account-tab" role="tabpanel">
                                        <form action="editconta.php" method="POST">
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Senha</label>
                                                            <input type="text" class="form-control" name="senhaup" value="<?php echo $senha ?>" required >
                                                        </div>
                                                    </div>
                                                
                                                    <!-- botoes de salvar -->
                                                    <button type="submit" name="mudar" class="btn btn-primary mr-2">Salvar</button>
                                                    <a href="../home.php" class="btn btn-outline-secondary">Cancelar</a>
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
                                    
                if(isset($_POST['mudar'])){
                    $senhaup = $_POST['senhaup'];
                    $limitetest = $_POST['limitetest'];
                    $bottoken = $_POST['tokenbot'];
                    $idtelegram = $_POST['idtelegram'];
                    //anti sql injection
                    $senhaup = anti_sql($senhaup);
                    $limitetest = anti_sql($limitetest);
                    $bottoken = anti_sql($bottoken);
                    $idtelegram = anti_sql($idtelegram);
                    $id = anti_sql($id);
                        $sql = "UPDATE accounts SET senha='$senhaup' WHERE id='$id'";
                        if (mysqli_query($conn, $sql)) {
                            echo "<script>swal('Sucesso!', 'Dados alterados com sucesso!', 'success').then((value) => {window.location.href = 'editconta.php';});</script>";
                        } else {
                            echo "Error updating record: " . mysqli_error($conn);
                        }
                    }
              ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
