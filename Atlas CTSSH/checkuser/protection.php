<?php


require_once "../atlas/conexao.php";
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
ini_set("error_reporting", 1);
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
foreach ($_REQUEST as $key => $value) {
    $data = $value;
}
$parts = "fix";
$data = str_replace(["/"], ["."], $data);
$data = $parts . $data;
$parts = explode(".", $data);
$data = $parts[2];
$username = $data;
$values = [];
$mysqli->real_escape_string($username);
$query = $mysqli->query("SELECT *\r\nFROM\r\nssh_accounts\r\nWHERE\r\nlogin\r\n='" . $username . "'\r\n");
if (0 < $query->num_rows) {
    $row = $query->fetch_assoc();
    $data = str_replace(["-"], ["/"], $row["expira"]);
    $timestamp = strtotime($data);
    $data = date("d/m/Y", $timestamp);
    $values["username"] = $row["login"];
    $values["count_connection"] = (int) $row["limite"];
    $values["limit_connection"] = (int) $row["limite"];
    $account_expiration = date("F j, Y", strtotime($row["expira"]));
    $current_date = date("F j, Y");
    if ($account_expiration != $current_date) {
        $values["expiration_date"] = $data;
        $values["expiration_days"] = datediffindays($current_date, $account_expiration);
    } else {
        $values["is_active"] = "false";
    }
} else {
    $values["error"] = "ist index out of range";
}
$json = json_encode($values);
$json = str_replace(["\\"], [""], $json);
echo $json;
$mysqli->close();
exit;
function dateDiffInDays($date1, $date2)
{
    $diff = strtotime($date2) - strtotime($date1);
    return abs(round($diff / 86400));
}

?>