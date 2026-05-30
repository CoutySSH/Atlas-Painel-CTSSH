<?php
include('atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname); 
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
error_reporting(0);
set_include_path(get_include_path() . PATH_SEPARATOR . 'lib2');
include ('Net/SSH2.php');
include('vendor/event/autoload.php');
use React\EventLoop\Factory;
date_default_timezone_set('America/Sao_Paulo');

$create = "CREATE TABLE IF NOT EXISTS `bot` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `app` text DEFAULT NULL,
    `sender` text DEFAULT NULL,
    `message` text DEFAULT NULL,
    `data` text DEFAULT NULL,
    `idpagamento` text DEFAULT NULL,
    `access_token` text DEFAULT NULL,
    `quantidadeuser` text DEFAULT NULL,
    `status` text DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    $conn->query($create);

//remove insert delete update from do token
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

//anti sql injection na $_GET['token']
$_GET['token'] = anti_sql($_GET['token']);
$revenda = $_GET['token'];





$pesquisa_revenda = "SELECT * FROM accounts WHERE tokenvenda = '$revenda'";
$pesquisa_revenda = $conn->query($pesquisa_revenda);
if ($pesquisa_revenda->num_rows > 0) {
    $revenda = $pesquisa_revenda->fetch_assoc();
    $valorusuario = $revenda['valorusuario'];
    $access_token = $revenda['accesstoken'];
    $login = $revenda['login'];
    $categoriaadmin = $revenda['tempo'];
    $email = $revenda['contato'];
    $formadepag = $revenda['formadepag'];
$nome = $revenda['nome'];
$acesstokenpaghiper = $revenda['acesstokenpaghiper'];
$tokenpaghiper = $revenda['tokenpaghiper'];
}else{
    $app = anti_sql($_POST['app']);
    $sender = anti_sql($_POST['sender']);
    $message = 'Revendedor nao encontrado';
    $mensagem = "{
        \"app\": \"$app\",
        \"sender\": \"$sender\",
        \"reply\": \"" . addslashes($message) . "\"
    }";
    echo $mensagem;
    exit();
}

if ($login == 'admin'){
   $categoria = $categoriaadmin;
}else{
    $atribuicao_cat = "SELECT * FROM atribuidos WHERE userid = '$revenda[id]'";
    $atribuicao_cat = $conn->query($atribuicao_cat);
    if ($atribuicao_cat->num_rows > 0) {
        $atribuicao_cat = $atribuicao_cat->fetch_assoc();
        $categoria = $atribuicao_cat['categoriaid'];
        $modo = $atribuicao_cat['tipo'];
    }
}


$sql = "SELECT * FROM servidores WHERE subid = '$categoria'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                  $servidores[] = $row;
    }
              
              define('SCRIPT_PATH', './atlasteste.sh');
            define('SSH_PORT', 22);
            define('SCRIPT_PATH2', './atlascreate.sh');
            
            // Função para tentar a conexão SSH com um servidor
$sql2 = "SELECT * FROM configs WHERE id = '1'";
$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();
$textocriado = $row2['cortextcard'];

$data = date('Y-m-d H:i:s');
if ($_POST['message'] == 'Menu' || $_POST['message'] == 'menu' || $_POST['message'] == 'MENU') {
    $app = $_POST['app'];
    $sender = $_POST['sender'];
    $message = "👋 Bem-vindo ao Menu de Testes!
    
    Escolha uma opção:
    1️⃣ Gerar Teste
    2️⃣ Renovar Teste
    3️⃣ Verificar Validade
    4️⃣ Compra de Plano Revendedor
    5️⃣ Renovar Usuário
    
    Para selecionar uma opção, envie o número correspondente.
    ";
    $mensagem = "{
        \"app\": \"$app\",
        \"sender\": \"$sender\",
        \"reply\": \"" . addslashes($message) . "\"
    }";
    echo $mensagem;
    exit();
    
} elseif ($_POST['message'] == '1') {
    $dominio = $_SERVER['HTTP_HOST'];
      $app = $_POST['app'];
      $sender = $_POST['sender'];
      
        $message = '📌 Para gerar um teste, acesse: https://' . $dominio . '/criarteste.php?token=' . $_GET['token'] . '';
        
   $mensagem = "{
       \"app\": \"$app\",
       \"sender\": \"$sender\",
       \"reply\": \"" . addslashes($message) . "\"
   }";
   echo $mensagem;

    exit();
   
} elseif ($_POST['message'] == '2') {
   $dominio = $_SERVER['HTTP_HOST'];
    $app = $_POST['app'];
    $sender = $_POST['sender'];
    $message = '📌 Para renovar um usuário, acesse: https://' . $dominio . '/renovar.php';
    $mensagem = "{
        \"app\": \"$app\",
        \"sender\": \"$sender\",
        \"reply\": \"" . addslashes($message) . "\"
    }";
    echo $mensagem;
    exit();

}elseif ($_POST['message'] == '3') {
    $mensage = '📌 Para saber a validade do seu plano, digite: Validade mais o seu login. Exemplo: Validade maria';
    $app = $_POST['app'];
    $sender = $_POST['sender'];
    $message = $mensage . "\n\n" .
        ' ';
    $mensagem = "{
        \"app\": \"$app\",
        \"sender\": \"$sender\",
        \"reply\": \"" . addslashes($message) . "\"
    }";
    echo $mensagem;
    exit();
}elseif (preg_match('/^validade\s(\w+)$/i', $_POST['message']) || preg_match('/^Validade\s(\w+)$/i', $_POST['message']) || preg_match('/^VALIDADE\s(\w+)$/i', $_POST['message'])) {
    $login = preg_replace('/^validade\s(\w+)$/i', '$1', $_POST['message']);
    if (!$login) {
        $login = preg_replace('/^Validade\s(\w+)$/i', '$1', $_POST['message']);
    }
    if (!$login) {
        $login = preg_replace('/^VALIDADE\s(\w+)$/i', '$1', $_POST['message']);
    }
    $login = anti_sql($login);
    $verifica = "SELECT * FROM ssh_accounts WHERE login = '$login'";
    $result = mysqli_query($conn, $verifica);
    if (mysqli_num_rows($result) > 0) {
        $validade = mysqli_fetch_assoc($result);
        $validade = $validade['expira'];
        //validade para d/m/Y H:i:s
        $validade = date('d/m/Y H:i:s', strtotime($validade));
        $app = $_POST['app'];
        $sender = $_POST['sender'];
        $message = '📌 A validade do login ' . $login . ' é: ' . $validade . "\n\n" .
            ' ';
        $mensagem = "{
            \"app\": \"$app\",
            \"sender\": \"$sender\",
            \"reply\": \"" . addslashes($message) . "\"
        }";
        echo $mensagem;
        //exit();
    }else{
        $app = $_POST['app'];
        $sender = $_POST['sender'];
        $message = '🚫 Login não encontrado.' . "\n\n" .
            '📌 Envie Validade mais o seu login. Exemplo: Validade maria' . "\n\n" .
            ' ';
        $mensagem = "{
            \"app\": \"$app\",
            \"sender\": \"$sender\",
            \"reply\": \"" . addslashes($message) . "\"
        }";
        echo $mensagem;
        //exit();
    }
}elseif ($_POST['message'] == 'validade' || $_POST['message'] == 'Validade' || $_POST['message'] == 'VALIDADE') {
    $app = $_POST['app'];
    $sender = $_POST['sender'];
    $message = '🚫 Login não encontrado.' . "\n\n" .
        '📌 Envie Validade mais o seu login. Exemplo: Validade maria' . "\n\n" .
        ' ';
    $mensagem = "{
        \"app\": \"$app\",
        \"sender\": \"$sender\",
        \"reply\": \"" . addslashes($message) . "\"
    }";
    echo $mensagem;
    exit();
}elseif ($_POST['message'] == '4'){
    $domain = $_SERVER['HTTP_HOST'];
    /* verifica se tem https */
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }
    $url = $protocol . $domain . '/revenda.php?token=' . $_GET['token'];
    $mensage = '📌 Esse é o nosso link para comprar o plano revendedor de forma automatica: ' . $url;
    $app = $_POST['app'];
    $sender = $_POST['sender'];
    $message = $mensage . "\n\n" .
        ' ';
    $mensagem = "{  
        \"app\": \"$app\",
        \"sender\": \"$sender\",
        \"reply\": \"" . addslashes($message) . "\"
    }";
    echo $mensagem;
    exit();
}elseif ($_POST['message'] == '5'){
    $domain = $_SERVER['HTTP_HOST'];
    /* verifica se tem https */
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }
    $url = $protocol . $domain . '/renovar.php';
    $mensage = '📌 Esse é o nosso link para renovação automatica: ' . $url;
    $app = $_POST['app'];
    $sender = $_POST['sender'];
    $message = $mensage . "\n\n" .
        ' ';
    $mensagem = "{  
        \"app\": \"$app\",
        \"sender\": \"$sender\",
        \"reply\": \"" . addslashes($message) . "\"
    }";
    echo $mensagem;
    exit();
}
