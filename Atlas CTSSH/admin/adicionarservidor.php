<?php error_reporting(0);

if (session_status() === PHP_SESSION_NONE) {
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
}

if (!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
    session_destroy();
    header('location:index.php');
    exit();
}

require_once '../atlas/conexao.php';

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function onlyDigits($v) {
    return preg_replace('/\D+/', '', (string)$v);
}

function normText($v, $maxLen = 120) {
    $v = trim((string)$v);
    $v = preg_replace('/\s+/', ' ', $v);
    if ($maxLen > 0) $v = mb_substr($v, 0, $maxLen, 'UTF-8');
    return $v;
}

function normIpOrHost($v, $maxLen = 180) {
    $v = trim((string)$v);
    $v = preg_replace('/\s+/', '', $v);
    if ($maxLen > 0) $v = mb_substr($v, 0, $maxLen, 'UTF-8');
    return $v;
}

function isValidPort($p) {
    if (!is_numeric($p)) return false;
    $p = (int)$p;
    return $p >= 1 && $p <= 65535;
}

function looksLikeIpOrHost($v) {
    $v = trim((string)$v);
    if ($v === '') return false;
    if (filter_var($v, FILTER_VALIDATE_IP)) return true;
    return (bool)preg_match('/^([a-z0-9-]+\.)+[a-z]{2,}$/i', $v);
}

include('headeradmin2.php');

$posted = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adcservidor']));

$ipservidor = '';
$nomeservidor = '';
$usuarioservidor = 'root';
$senhaservidor = '';
$portaservidor = '22';
$categoriaservidor = '1';

if ($posted) {
    $ipservidor = normIpOrHost($_POST['ipservidor'] ?? '', 180);
    $nomeservidor = normText($_POST['nomeservidor'] ?? '', 120);
    $usuarioservidor = normText($_POST['usuarioservidor'] ?? 'root', 80);
    $senhaservidor = (string)($_POST['senhaservidor'] ?? '');
    $portaservidor = normText($_POST['portaservidor'] ?? '22', 6);
    $categoriaservidor = normText($_POST['categoriaservidor'] ?? '1', 20);

    $_SESSION['ipservidor'] = $ipservidor;
    $_SESSION['nomeservidor'] = $nomeservidor;
    $_SESSION['usuarioservidor'] = $usuarioservidor;
    $_SESSION['senhaservidor'] = $senhaservidor;
    $_SESSION['portaservidor'] = $portaservidor;
    $_SESSION['confirma'] = 6;

    $erros = [];

    if ($nomeservidor === '') $erros[] = "Informe o nome do servidor.";
    if ($ipservidor === '') $erros[] = "Informe o IP/Host do servidor.";
    if (!looksLikeIpOrHost($ipservidor)) $erros[] = "IP/Host inválido.";
    if ($usuarioservidor === '') $erros[] = "Informe o usuário do servidor.";
    if ($senhaservidor === '') $erros[] = "Informe a senha do servidor.";
    if ($portaservidor === '' || !isValidPort($portaservidor)) $erros[] = "Porta inválida (1 a 65535).";
    if ($categoriaservidor === '') $erros[] = "Informe a categoria do servidor.";

    if (empty($erros)) {
        $stmt = $conn->prepare("SELECT id FROM servidores WHERE ip = ? LIMIT 1");
        $stmt->bind_param("s", $ipservidor);
        $stmt->execute();
        $res = $stmt->get_result();
        $jaExiste = ($res && $res->num_rows > 0);
        $stmt->close();

        if ($jaExiste) {
            echo "<script>swal('Erro!', 'Servidor já cadastrado!', 'error').then(function(){window.location.href='adicionarservidor.php';});</script>";
            $posted = false;
        } else {
            $stmt = $conn->prepare("INSERT INTO servidores (ip, usuario, nome, senha, porta, subid) VALUES (?, ?, ?, ?, ?, ?)");
            $portaInt = (int)$portaservidor;
            $subid = $categoriaservidor;

            $stmt->bind_param("ssssii", $ipservidor, $usuarioservidor, $nomeservidor, $senhaservidor, $portaInt, $subid);
            $ok = $stmt->execute();
            $stmt->close();

            if ($ok) {
                echo "<script>
                    swal('Sucesso!', 'Servidor cadastrado! Iniciando Instalação dos Drivers', 'success');
                    setTimeout(function(){ window.location.href='installserv.php'; }, 1000);
                </script>";
                $posted = false;
            } else {
                echo "<script>swal('Erro!', 'Falha ao cadastrar servidor no banco.', 'error');</script>";
                $posted = false;
            }
        }
    } else {
        $msg = implode("\\n", array_map('addslashes', $erros));
        echo "<script>swal('Atenção!', '{$msg}', 'warning');</script>";
        $posted = false;
    }
}
?>
<script src="../app-assets/sweetalert.min.js"></script>

<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
    <p class="text-primary">Novo Servidor</p>
    <div class="content-header row"></div>

    <div class="content-body">
      <section id="dashboard-ecommerce">
        <div class="row">
          <section id="basic-horizontal-layouts">
            <div class="row match-height">
              <div class="col-md-6 col-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Cadastro de Novo Servidor</h4>
                  </div>

                  <div id="alerta"></div>

                  <div class="card-content">
                    <div class="card-body">
                      <p class="card-description">Preencha os dados do servidor para cadastrar.</p>

                      <form class="form form-horizontal" action="adicionarservidor.php" method="POST" autocomplete="off">
                        <div class="form-body">
                          <div class="row">

                            <div class="col-md-4">
                              <label>Nome</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="text" class="form-control" name="nomeservidor" placeholder="Insira o Nome do Servidor" value="<?php echo h($nomeservidor); ?>" required>
                            </div>

                            <div class="col-md-4">
                              <label>IP / Host</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="text" class="form-control" name="ipservidor" placeholder="Insira o IP ou Host do Servidor" value="<?php echo h($ipservidor); ?>" required>
                            </div>

                            <div class="col-md-4">
                              <label>Usuário</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="text" class="form-control" name="usuarioservidor" placeholder="Usuário do Servidor" value="<?php echo h($usuarioservidor); ?>" required>
                            </div>

                            <div class="col-md-4">
                              <label>Senha</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="text" class="form-control" name="senhaservidor" placeholder="Senha do Servidor" value="<?php echo h($senhaservidor); ?>" required>
                            </div>

                            <div class="col-md-4">
                              <label>Porta</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="number" class="form-control" name="portaservidor" placeholder="Porta do Servidor" value="<?php echo h($portaservidor); ?>" min="1" max="65535" required>
                            </div>

                            <div class="col-md-4">
                              <label>Categoria do Servidor</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="number" class="form-control" name="categoriaservidor" placeholder="Categoria" value="<?php echo h($categoriaservidor); ?>" min="1" required>
                            </div>

                            <div class="col-sm-12 d-flex justify-content-end">
                              <button type="submit" class="btn btn-primary mr-1 mb-1" name="adcservidor">Salvar</button>
                              <a href="home.php" class="btn btn-light-secondary mr-1 mb-1">Cancelar</a>
                            </div>

                          </div>
                        </div>
                      </form>

                    </div>
                  </div>

                </div>
              </div>
            </div>
          </section>
        </div>
      </section>
    </div>
  </div>
</div>
