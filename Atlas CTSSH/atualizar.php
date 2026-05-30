    session_start();
    error_reporting(0);
    include('atlas/conexao.php');
ini_set('memory_limit', '-1');

//se senha nao existir
if (!isset($_SESSION['senhaatualizar'])) {
    header('Location: index.php');
    exit;
}else{
    if ($_POST['versao'] == 'ultima') {
        $url = '#';
    }elseif ($_POST['versao'] == '3.8.6') {
        $url = '#';
    }elseif ($_POST['versao'] == '4.4.2') {
        $url = '#';
    }
}
echo 'Atualizado com sucesso!';


?>
