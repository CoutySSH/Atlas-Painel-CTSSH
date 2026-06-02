<?php error_reporting(0);
include '../atlas/conexao.php';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$usuario = mysqli_real_escape_string($conn, $_GET['user']);
$deviceid = mysqli_real_escape_string($conn, $_GET['deviceID']);

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

$usuario = anti_sql($usuario);
$deviceid = anti_sql($deviceid);

$sql = "SELECT * FROM ssh_accounts WHERE login = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $usuario); 
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$validade = $row['expira'];
$limite = $row['limite'];
$validade = date('d/m/Y', strtotime($validade));
$sql2 = "SELECT * FROM atlasdeviceid WHERE nome_user = ? AND deviceid = ?";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_bind_param($stmt2, "ss", $usuario, $deviceid);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$linhas = mysqli_num_rows($result2);

if ($linhas > 0) {
    $deviceblocked = "0";
} else {
    $cont = "SELECT COUNT(*) FROM atlasdeviceid WHERE nome_user = '$usuario'";
    $resultado_cont = mysqli_query($conn, $cont);
    $row = mysqli_fetch_assoc($resultado_cont);
    $quantidade = $row['COUNT(*)'];
    // A quantidade não pode ser maior que o limite
    if ($quantidade >= $limite) {
        $deviceblocked = "1";
    } else {
        // Insere o deviceid na tabela
        $sql3 = "INSERT INTO atlasdeviceid (nome_user, deviceid) VALUES (?, ?)";
        $stmt3 = mysqli_prepare($conn, $sql3);
        mysqli_stmt_bind_param($stmt3, "ss", $usuario, $deviceid);
        mysqli_stmt_execute($stmt3);
        $deviceblocked = "0";
    }
}






$dados = array(
    "validade" => $validade,
    "limite" => $limite,
    "deviceblocked" => $deviceblocked
);

//converte para json
echo json_encode($dados);




?>
