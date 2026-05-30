require_once ('../atlas/conexao.php');

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0"); 
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
ini_set('error_reporting', 1);
//timezona
date_default_timezone_set('America/Sao_Paulo');
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if (!file_exists("../admin/suspenderrev.php")) {
    echo ("ist index out of range");
    exit;
}
foreach ($_REQUEST as $key => $value) {
    $data = $value;
}

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


$user = $_REQUEST['user'];
$username = explode('?', explode('/', $user)[2])[0];

$username = $mysqli->real_escape_string($username);

$username = anti_sql($username);
$query = $mysqli->query("SELECT *
FROM
ssh_accounts
WHERE
login
='".$username."'
");


function dateDiffInDays($date1, $date2) {
    $datetime1 = new DateTime($date1);
    $datetime2 = new DateTime($date2);
    $interval = $datetime1->diff($datetime2);
    return $interval->days;
}

if ($query->num_rows > 0) {
    $row = $query->fetch_assoc();

    $data = str_replace(['-'], ['/'], ($row['expira']));
    $timestamp = strtotime($data);
    $data = date("d/m/Y", $timestamp);

    $values = [
        'username' => $row['login'],
        'count_connection' => (int) $row['limite'],
        'limit_connection' => (int) $row['limite'],
    ];

    $account_expiration = date("F j, Y", strtotime($row['expira']));
    $current_date = date('F j, Y');
    $values['expiration_date'] = $data;
    $values['expiration_days'] = dateDiffInDays($current_date, $account_expiration);
    $values['time_online'] = null;

} else {
    $values = ['error' => "ist index out of range"];
}

$json = json_encode($values);
echo $json;

$mysqli->close();
exit();

?>
