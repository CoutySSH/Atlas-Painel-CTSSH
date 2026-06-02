<?php session_start();

// Verifica se as variáveis de sessão de login e senha existem
if (!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
    // Se não existirem, destrói a sessão e redireciona para a página de login
    session_destroy();
    header('Location: index.php');
    exit();
}

// Verifica se o usuário logado é um administrador
if ($_SESSION['login'] !== 'admin') {
    // Se não for, destrói a sessão e redireciona para a página de login
    session_destroy();
    header('Location: index.php');
    exit();
}

require_once '../atlas/conexao.php';

// Conecta ao banco de dados usando as credenciais do arquivo de conexão
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Inclui o cabeçalho da página de administração
include 'headeradmin2.php';

// Obtém o ID do servidor da variável de consulta GET e filtra como um número inteiro

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

$id = $_POST['id'];
$id = anti_sql($id);
if (!empty($id)) {
    // Se o ID não estiver vazio, executa uma consulta SQL para excluir o servidor com esse ID
    $sql = "DELETE FROM servidores WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    // Verifica se a consulta SQL foi executada com sucesso
    if ($result) {
        // Se foi, define uma mensagem de sucesso e um tipo de alerta
        $message = 'Servidor deletado com sucesso!';
        $type = 'success';
    } else {
        // Se não foi, define uma mensagem de erro e um tipo de alerta
        $message = 'Erro ao deletar servidor!';
        $type = 'error';
    }

    // Inclui o script do SweetAlert e exibe um alerta com a mensagem e o tipo definidos acima
    echo $message;
} else {
    // Se o ID estiver vazio, define uma mensagem de erro e um tipo de alerta
    $message = 'Não foi possível obter o ID do servidor.';
    $type = 'error';

    // Inclui o script do SweetAlert e exibe um alerta com a mensagem e o tipo definidos acima
    echo $message;
}
?>
