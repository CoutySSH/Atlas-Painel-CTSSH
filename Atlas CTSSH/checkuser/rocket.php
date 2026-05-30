require_once '../atlas/conexao.php';
session_start();
error_reporting(0);

$atlantusPost = json_decode(file_get_contents('php://input'), true);
$slot1 = $atlantusPost['username'];
$slot2 = $atlantusPost['password'];
$slot3 = $atlantusPost['randomid'];
$slot4 = $atlantusPost['apikey'];


$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$stmt2xxxx2s = $mysqli->prepare("SELECT * FROM ssh_accounts WHERE login = ? AND senha = ?");
$stmt2xxxx2s->bind_param("ss", $slot1, $slot2);
$stmt2xxxx2s->execute();
$result = $stmt2xxxx2s->get_result();
$room53s = $result->fetch_assoc();
//fecha
$stmt2xxxx2s->close();


$stmt2xxxx2ss = $mysqli->prepare("SELECT * FROM atlasdeviceid WHERE nome_user = ? AND deviceid = ?");
$stmt2xxxx2ss->bind_param("ss", $room53s['login'], $slot3);
$stmt2xxxx2ss->execute();
$result = $stmt2xxxx2ss->get_result();
$room53ss = $result->fetch_assoc();



$ssf = $mysqli->prepare("SELECT COUNT(*) FROM atlasdeviceid WHERE nome_user = ?");
$ssf->bind_param("s", $room53s['login']);
$ssf->execute();
$ssf->bind_result($count);
$ssf->fetch();
$ssf->close();

if ($room53ss['id'] == null) {
    // Check ssh accounts limit and insert
    if ($count >= $room53s['limite'] && $room53s['id'] != null) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 200,
            'message' => 'done',
            'reaction' => 'overflow'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    } elseif ($room53s['id'] != null) {
        // Insert into miracle_deviceid
        $stmt2xxxx2sss = $mysqli->prepare("INSERT INTO atlasdeviceid (nome_user, deviceid, byid) VALUES (?, ?, ?)");
        $stmt2xxxx2sss->bind_param("sss", $room53s['login'], $slot3, $room53s['byid']);
        $stmt2xxxx2sss->execute();
    }
}

if ($room53s['id'] != null) {
    $tempo = null;
    $start_date = new DateTime($tempo); // Make sure $tempo is defined
    $since_start = $start_date->diff(new DateTime($room53s['expira']));

    // Get total days difference between two dates
    $dias = $since_start->days;

    header('Content-Type: application/json; charset=utf-8');
    $responseData = [
        'full_time' => $room53s['expira'],
        'days_left' => $dias,
        'limit' => $room53s['limite'],
        'connections' => 0,
        'device_limit' => $room53s['limite'],
    ];

    echo json_encode([
        'status' => 200,
        'message' => 'done',
        'reaction' => $responseData
    ], JSON_UNESCAPED_UNICODE);
}

// Close the MySQLi connection
$mysqli->close();
?>
