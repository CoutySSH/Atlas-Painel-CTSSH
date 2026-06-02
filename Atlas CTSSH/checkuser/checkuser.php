<?php
if (isset($_POST['username']) || ($_POST['deviceid'] )) 
{

require_once ('../atlas/conexao.php');
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0"); 
header('Content-Type: application/json; charset=utf-8');

$data_hora_atual =date('Y-m-d H:i:s'); 

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

$values = array();

$username = isset($_POST['username']) ? $_POST['username'] : "failed";
$password = isset($_POST['deviceid']) ? $_POST['deviceid'] : "false";


$mysqli->real_escape_string($username);
$mysqli->real_escape_string($password);

$username = anti_sql($username);
$password = anti_sql($password);

$query = $mysqli->query("SELECT *
FROM
atlasdeviceid
WHERE
(nome_user COLLATE latin1_general_cs)
='".$username."'
AND deviceid='".$password."'
");

if($query->num_rows > 0)
{
$row = $query->fetch_assoc();

$values['USER_ID'] = ($row['nome_user']);
$values['DEVICE'] = ($row['deviceid']);

}else{
$query1 = $mysqli->query("SELECT nome_user
FROM
atlasdeviceid
WHERE
(nome_user COLLATE latin1_general_cs)
='".$username."'
");

if($query1->num_rows > 0)
{
$row = $query1->fetch_assoc();
$values['USER_ID'] = ($row['nome_user']);
}
else
{

$query10 = $mysqli->query("SELECT *
FROM
ssh_accounts
WHERE
login
='".$username."'
");


if($query10->num_rows > 0)
{
$row = $query10->fetch_assoc();		
$valor = ($row['limite']);
if($valor <2){
$valor="0";
}
else{
$a="1";
$b = ($row['limite']);
$valor=$b-$a;
}

}else{
$valor="0";
}

$mysqli->query("INSERT INTO userlimiter (nome_user, limiter) VALUES
('$username','$valor')");

$query2 = $mysqli->query("INSERT INTO atlasdeviceid (nome_user, deviceid) values ('{$username}', '{$password}')");

}

 if($query2){
$values['USER_ID'] = $username;
$values['DEVICE'] = $password; 
} else {


$query5 = $mysqli->query("SELECT *
FROM
userlimiter
WHERE
(nome_user COLLATE latin1_general_cs)
='".$username."'
");

if($query5->num_rows > 0)
{
 $row = $query5->fetch_assoc();
$idlimiter=($row['limiter']);
if($idlimiter >0)
{
$val1 ="1";
$soma = $idlimiter - $val1;

$mysqli->query("UPDATE userlimiter
SET
limiter='".$soma."'
WHERE
(nome_user COLLATE latin1_general_cs)
='".$username."'
");

$query7 = $mysqli->query("INSERT INTO atlasdeviceid (nome_user, deviceid) values ('{$username}', '{$password}')");
}

 if($query7)      
         {   
$values['USER_ID'] = ($row['nome_user']);
$values['DEVICE'] = ($row['deviceid']);
 
 
}
}else
{

$values['DEVICE'] = 'false';
$block = 'false';
} 
}
}

$queryok = $mysqli->query("SELECT *
FROM
atlasdeviceid
WHERE
(nome_user COLLATE latin1_general_cs)
='".$username."'
AND deviceid='".$password."'
");

if($queryok->num_rows > 0)
{
$values['DEVICE'] = $username;
$values['DEVICE'] = $password;

}else{

$values['DEVICE'] = 'false';
$block = 'false';
}

$respo = $block;

$query20 = $mysqli->query("SELECT *
FROM
ssh_accounts
WHERE
login
='".$username."'
");



function dateDiffInDays($date1, $date2) 
{
    // Calculating the difference in timestamps
    $diff = strtotime($date2) - strtotime($date1);
      
    // 1 day = 24 hours
    // 24 * 60 * 60 = 86400 seconds
    return abs(round($diff / 86400));
}



if($query20->num_rows > 0)
{

$row = $query20->fetch_assoc();

$acesso = ($row['limite']);
$data2 = ($row['expira']);
$timestamp = strtotime($data2);
$data2 = date("Y-m-d", $timestamp);


if($respo == "false")
{
$values['DEVICE'] = "false";
}

$account_expiration = date("F j, Y", strtotime($row['expira']));
		$current_date = date('F j, Y');
		if($account_expiration != $current_date){
		$values['is_active'] = 'true';
		$values['expiration_date'] = date("Y-m-d-", strtotime($row['expira']));
		$values['expiry'] = dateDiffInDays($current_date, $account_expiration) . " dias.";
        }else{
        $values['is_active'] = 'false';
        }
}else{
        $values['is_active'] = 'false';
        $values['Status'] = 'noencontrado';
}
$values['uuid'] = "null";


echo json_encode($values);
$mysqli->close();
exit();
}
?>
