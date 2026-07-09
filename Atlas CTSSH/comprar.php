<?php


include "atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
error_reporting(0);
set_include_path(get_include_path() . PATH_SEPARATOR . "lib2");
include "Net/SSH2.php";
include "vendor/event/autoload.php";
date_default_timezone_set("America/Sao_Paulo");
$create = "CREATE TABLE IF NOT EXISTS `bot` (\r\n    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,\r\n    `app` text DEFAULT NULL,\r\n    `sender` text DEFAULT NULL,\r\n    `message` text DEFAULT NULL,\r\n    `data` text DEFAULT NULL,\r\n    `idpagamento` text DEFAULT NULL,\r\n    `access_token` text DEFAULT NULL,\r\n    `quantidadeuser` text DEFAULT NULL,\r\n    `status` text DEFAULT NULL,\r\n    PRIMARY KEY (`id`)\r\n  ) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
$conn->query($create);
$_GET["token"] = anti_sql($_GET["token"]);
$revenda = $_GET["token"];
$pesquisa_revenda = "SELECT * FROM accounts WHERE tokenvenda = '" . $revenda . "'";
$pesquisa_revenda = $conn->query($pesquisa_revenda);
if (0 < $pesquisa_revenda->num_rows) {
    $revenda = $pesquisa_revenda->fetch_assoc();
    $valorusuario = $revenda["valorusuario"];
    $access_token = $revenda["accesstoken"];
    $login = $revenda["login"];
    $categoriaadmin = $revenda["tempo"];
    $email = $revenda["contato"];
    $formadepag = $revenda["formadepag"];
    $nome = $revenda["nome"];
    $acesstokenpaghiper = $revenda["acesstokenpaghiper"];
    $tokenpaghiper = $revenda["tokenpaghiper"];
    if ($login == "admin") {
        $categoria = $categoriaadmin;
    } else {
        $atribuicao_cat = "SELECT * FROM atribuidos WHERE userid = '" . $revenda["id"] . "'";
        $atribuicao_cat = $conn->query($atribuicao_cat);
        if (0 < $atribuicao_cat->num_rows) {
            $atribuicao_cat = $atribuicao_cat->fetch_assoc();
            $categoria = $atribuicao_cat["categoriaid"];
            $modo = $atribuicao_cat["tipo"];
        }
    }
    $sql = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $servidores[] = $row;
    }
    define("SCRIPT_PATH", "./atlasteste.sh");
    define("SSH_PORT", 22);
    define("SCRIPT_PATH2", "./atlascreate.sh");
    $sql2 = "SELECT * FROM configs WHERE id = '1'";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    $textocriado = $row2["cortextcard"];
    $data = date("Y-m-d H:i:s");
    if ($_POST["message"] == "Menu" || $_POST["message"] == "menu" || $_POST["message"] == "MENU") {
        $app = $_POST["app"];
        $sender = $_POST["sender"];
        $message = "👋 Bem-vindo ao Menu de Testes!\r\n    \r\n    Escolha uma opção:\r\n    1️⃣ Gerar Teste\r\n    2️⃣ Renovar Teste\r\n    3️⃣ Verificar Validade\r\n    4️⃣ Compra de Plano Revendedor\r\n    5️⃣ Renovar Usuário\r\n    \r\n    Para selecionar uma opção, envie o número correspondente.\r\n    ";
        $mensagem = "{\r\n        \"app\": \"" . $app . "\",\r\n        \"sender\": \"" . $sender . "\",\r\n        \"reply\": \"" . addslashes($message) . "\"\r\n    }";
        echo $mensagem;
        exit;
    }
    if ($_POST["message"] == "1") {
        $dominio = $_SERVER["HTTP_HOST"];
        $app = $_POST["app"];
        $sender = $_POST["sender"];
        $message = "📌 Para gerar um teste, acesse: https://" . $dominio . "/criarteste.php?token=" . $_GET["token"] . "";
        $mensagem = "{\r\n       \"app\": \"" . $app . "\",\r\n       \"sender\": \"" . $sender . "\",\r\n       \"reply\": \"" . addslashes($message) . "\"\r\n   }";
        echo $mensagem;
        exit;
    }
    if ($_POST["message"] == "2") {
        $dominio = $_SERVER["HTTP_HOST"];
        $app = $_POST["app"];
        $sender = $_POST["sender"];
        $message = "📌 Para renovar um usuário, acesse: https://" . $dominio . "/renovar.php";
        $mensagem = "{\r\n        \"app\": \"" . $app . "\",\r\n        \"sender\": \"" . $sender . "\",\r\n        \"reply\": \"" . addslashes($message) . "\"\r\n    }";
        echo $mensagem;
        exit;
    }
    if ($_POST["message"] == "3") {
        $mensage = "📌 Para saber a validade do seu plano, digite: Validade mais o seu login. Exemplo: Validade maria";
        $app = $_POST["app"];
        $sender = $_POST["sender"];
        $message = $mensage . "\n\n" . " ";
        $mensagem = "{\r\n        \"app\": \"" . $app . "\",\r\n        \"sender\": \"" . $sender . "\",\r\n        \"reply\": \"" . addslashes($message) . "\"\r\n    }";
        echo $mensagem;
        exit;
    }
    if (preg_match("/^validade\\s(\\w+)\$/i", $_POST["message"]) || preg_match("/^Validade\\s(\\w+)\$/i", $_POST["message"]) || preg_match("/^VALIDADE\\s(\\w+)\$/i", $_POST["message"])) {
        $login = preg_replace("/^validade\\s(\\w+)\$/i", "\$1", $_POST["message"]);
        if (!$login) {
            $login = preg_replace("/^Validade\\s(\\w+)\$/i", "\$1", $_POST["message"]);
        }
        if (!$login) {
            $login = preg_replace("/^VALIDADE\\s(\\w+)\$/i", "\$1", $_POST["message"]);
        }
        $verifica = "SELECT * FROM ssh_accounts WHERE login = '" . $login . "'";
        $result = mysqli_query($conn, $verifica);
        if (0 < mysqli_num_rows($result)) {
            $validade = mysqli_fetch_assoc($result);
            $validade = $validade["expira"];
            $validade = date("d/m/Y H:i:s", strtotime($validade));
            $app = $_POST["app"];
            $sender = $_POST["sender"];
            $message = "📌 A validade do login " . $login . " é: " . $validade . "\n\n" . " ";
            $mensagem = "{\r\n            \"app\": \"" . $app . "\",\r\n            \"sender\": \"" . $sender . "\",\r\n            \"reply\": \"" . addslashes($message) . "\"\r\n        }";
            echo $mensagem;
        } else {
            $app = $_POST["app"];
            $sender = $_POST["sender"];
            $message = "🚫 Login não encontrado.\n\n📌 Envie Validade mais o seu login. Exemplo: Validade maria\n\n ";
            $mensagem = "{\r\n            \"app\": \"" . $app . "\",\r\n            \"sender\": \"" . $sender . "\",\r\n            \"reply\": \"" . addslashes($message) . "\"\r\n        }";
            echo $mensagem;
        }
    } else {
        if ($_POST["message"] == "validade" || $_POST["message"] == "Validade" || $_POST["message"] == "VALIDADE") {
            $app = $_POST["app"];
            $sender = $_POST["sender"];
            $message = "🚫 Login não encontrado.\n\n📌 Envie Validade mais o seu login. Exemplo: Validade maria\n\n ";
            $mensagem = "{\r\n        \"app\": \"" . $app . "\",\r\n        \"sender\": \"" . $sender . "\",\r\n        \"reply\": \"" . addslashes($message) . "\"\r\n    }";
            echo $mensagem;
            exit;
        }
        if ($_POST["message"] == "4") {
            $domain = $_SERVER["HTTP_HOST"];
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") {
                $protocol = "https://";
            } else {
                $protocol = "http://";
            }
            $url = $protocol . $domain . "/revenda.php?token=" . $_GET["token"];
            $mensage = "📌 Esse é o nosso link para comprar o plano revendedor de forma automatica: " . $url;
            $app = $_POST["app"];
            $sender = $_POST["sender"];
            $message = $mensage . "\n\n" . " ";
            $mensagem = "{  \r\n        \"app\": \"" . $app . "\",\r\n        \"sender\": \"" . $sender . "\",\r\n        \"reply\": \"" . addslashes($message) . "\"\r\n    }";
            echo $mensagem;
            exit;
        }
        if ($_POST["message"] == "5") {
            $domain = $_SERVER["HTTP_HOST"];
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") {
                $protocol = "https://";
            } else {
                $protocol = "http://";
            }
            $url = $protocol . $domain . "/renovar.php";
            $mensage = "📌 Esse é o nosso link para renovação automatica: " . $url;
            $app = $_POST["app"];
            $sender = $_POST["sender"];
            $message = $mensage . "\n\n" . " ";
            $mensagem = "{  \r\n        \"app\": \"" . $app . "\",\r\n        \"sender\": \"" . $sender . "\",\r\n        \"reply\": \"" . addslashes($message) . "\"\r\n    }";
            echo $mensagem;
            exit;
        }
    }
} else {
    $app = $_POST["app"];
    $sender = $_POST["sender"];
    $message = "Revendedor nao encontrado";
    $mensagem = "{\r\n        \"app\": \"" . $app . "\",\r\n        \"sender\": \"" . $sender . "\",\r\n        \"reply\": \"" . addslashes($message) . "\"\r\n    }";
    echo $mensagem;
    exit;
}
function anti_sql($input)
{
    $seg = preg_replace_callback("/(from|select|insert|delete|where|drop table|show tables|#|\\*|--|\\\\)/i", function ($match) {
        return "";
    }, $input);
    $seg = trim($seg);
    $seg = strip_tags($seg);
    $seg = addslashes($seg);
    return $seg;
}

?>