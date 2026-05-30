header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
header('content-type: application/json; charset=utf-8');
ini_set('error_reporting', 1);
ob_start();
include '../atlas/conexao.php';
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
session_start();

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

$getRequest = mysqli_real_escape_string($conn, $_GET['request']);
$userid = mysqli_real_escape_string($conn, $_GET['slot1']);
$device = mysqli_real_escape_string($conn, $_GET['slot3']);
$passw = mysqli_real_escape_string($conn, $_GET['slot2']);

$getRequest = anti_sql($getRequest);
$userid = anti_sql($userid);
$device = anti_sql($device);
$passw = anti_sql($passw);


$date = date('Y-m-d H:i:s');

//check $getRequest is not empty


$data = json_decode(file_get_contents('php://input'), true);
// Get the user input and current time
$ip = mysqli_real_escape_string($conn, $data['ip']);
$username = mysqli_real_escape_string($conn, $data['user']);
$password = mysqli_real_escape_string($conn, $data['password']);
$deviceId = mysqli_real_escape_string($conn, $data['deviceid']);

$ip = anti_sql($ip);
$username = anti_sql($username);
$password = anti_sql($password);
$deviceId = anti_sql($deviceId);


$currentTime = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')));

$pesquisa_user = "SELECT * FROM ssh_accounts WHERE login = '$username' AND senha = '$password'";
$resultado_user = mysqli_query($conn, $pesquisa_user);
if (mysqli_num_rows($resultado_user) > 0) {
    while ($row = mysqli_fetch_assoc($resultado_user)) {
        $id_user = $row['id'];
        $limite = $row['limite'];
        $categoria = $row['categoriaid'];
        $login = $row['login'];
        $senha = $row['senha'];
        $expira = $row['expira'];
        $byid = $row['byid'];
    }
}

$pesquisa_device = "SELECT * FROM atlasdeviceid WHERE nome_user = '$login' AND deviceid = '$deviceId'";
$resultado_device = mysqli_query($conn, $pesquisa_device);
if (mysqli_num_rows($resultado_device) > 0) {
    $startDate = new DateTime($currentTime);
    $timeRemaining = $startDate->diff(new DateTime($expira));
    $months = $timeRemaining->m;
    $days = $timeRemaining->d;
    $hours = $timeRemaining->h;
    $minutes = $timeRemaining->i;
    $response = array(
        'Status' => "searched",
        'Days' => "$days",
        'Hours' => "$hours",
        'Minutes' => "$minutes",
        'Months' => "$months",
        'Limit' => "$limite",
    );
    echo json_encode($response);
}else{
    $cont = "SELECT COUNT(*) FROM atlasdeviceid WHERE nome_user = '$login'";
    $resultado_cont = mysqli_query($conn, $cont);
    #a quantidade nao pode ser maior que o limite
    if (mysqli_num_rows($resultado_cont) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_cont)) {
            $quantidade = $row['COUNT(*)'];
        }
    }
    if($quantidade < $limite){
        $insert = "INSERT INTO atlasdeviceid (nome_user, deviceid, byid) VALUES ('$login', '$deviceId', '$byid')";
        $resultado_insert = mysqli_query($conn, $insert);
        $startDate = new DateTime($currentTime);
        $timeRemaining = $startDate->diff(new DateTime($expira));
        $months = $timeRemaining->m;
        $days = $timeRemaining->d;
        $hours = $timeRemaining->h;
        $minutes = $timeRemaining->i;
        $response = array(
            'Status' => "searched",
            'Days' => "$days",
            'Hours' => "$hours",
            'Minutes' => "$minutes",
            'Months' => "$months",
            'Limit' => "$limite",
        );
        echo json_encode($response);
    }else{
        $response = array('Status' => "blockdevice");
        echo json_encode($response);
    }
}

?>
