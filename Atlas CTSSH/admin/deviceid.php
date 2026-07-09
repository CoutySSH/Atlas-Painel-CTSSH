<?php


session_start();
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!isset($_SESSION["login"])) {
    header("Location: ../index.php");
    exit;
}
if ($_SESSION["login"] != "admin") {
    session_destroy();
    header("Location: index.php");
    exit;
}
$_GET["id"] = anti_sql($_GET["id"]);
$id = $_GET["id"];
$sql22 = "SELECT * FROM ssh_accounts WHERE id = '" . $id . "'";
$result22 = $conn->query($sql22);
if (0 < $result22->num_rows) {
    while ($row22 = $result22->fetch_assoc()) {
        $login = $row22["login"];
    }
}
$sql = "DELETE FROM atlasdeviceid WHERE nome_user = '" . $login . "'";
$result = $conn->query($sql);
$sql2 = "DELETE FROM userlimiter WHERE nome_user = '" . $login . "'";
$result2 = $conn->query($sql2);
if ($result) {
    echo "deletado com sucesso";
} else {
    echo "erro ao deletar";
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