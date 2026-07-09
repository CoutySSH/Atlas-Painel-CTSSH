<?php


if (isset($_POST["username"]) || $_POST["deviceid"]) {
    require_once "../atlas/conexao.php";
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    header("Content-Type: application/json; charset=utf-8");
    $data_hora_atual = date("Y-m-d H:i:s");
    $values = [];
    $username = isset($_POST["username"]) ? $_POST["username"] : "failed";
    $password = isset($_POST["deviceid"]) ? $_POST["deviceid"] : "false";
    $mysqli->real_escape_string($username);
    $mysqli->real_escape_string($password);
    $query = $mysqli->query("SELECT *\r\nFROM\r\natlasdeviceid\r\nWHERE\r\n(nome_user COLLATE latin1_general_cs)\r\n='" . $username . "'\r\nAND deviceid='" . $password . "'\r\n");
    if (0 < $query->num_rows) {
        $row = $query->fetch_assoc();
        $values["USER_ID"] = $row["nome_user"];
        $values["DEVICE"] = $row["deviceid"];
    } else {
        $query1 = $mysqli->query("SELECT nome_user\r\nFROM\r\natlasdeviceid\r\nWHERE\r\n(nome_user COLLATE latin1_general_cs)\r\n='" . $username . "'\r\n");
        if (0 < $query1->num_rows) {
            $row = $query1->fetch_assoc();
            $values["USER_ID"] = $row["nome_user"];
        } else {
            $query10 = $mysqli->query("SELECT *\r\nFROM\r\nssh_accounts\r\nWHERE\r\nlogin\r\n='" . $username . "'\r\n");
            if (0 < $query10->num_rows) {
                $row = $query10->fetch_assoc();
                $valor = $row["limite"];
                if ($valor < 2) {
                    $valor = "0";
                } else {
                    $a = "1";
                    $b = $row["limite"];
                    $valor = $b - $a;
                }
            } else {
                $valor = "0";
            }
            $mysqli->query("INSERT INTO userlimiter (nome_user, limiter) VALUES\r\n('" . $username . "','" . $valor . "')");
            $query2 = $mysqli->query("INSERT INTO atlasdeviceid (nome_user, deviceid) values ('" . $username . "', '" . $password . "')");
        }
        if ($query2) {
            $values["USER_ID"] = $username;
            $values["DEVICE"] = $password;
        } else {
            $query5 = $mysqli->query("SELECT *\r\nFROM\r\nuserlimiter\r\nWHERE\r\n(nome_user COLLATE latin1_general_cs)\r\n='" . $username . "'\r\n");
            if (0 < $query5->num_rows) {
                $row = $query5->fetch_assoc();
                $idlimiter = $row["limiter"];
                if (0 < $idlimiter) {
                    $val1 = "1";
                    $soma = $idlimiter - $val1;
                    $mysqli->query("UPDATE userlimiter\r\nSET\r\nlimiter='" . $soma . "'\r\nWHERE\r\n(nome_user COLLATE latin1_general_cs)\r\n='" . $username . "'\r\n");
                    $query7 = $mysqli->query("INSERT INTO atlasdeviceid (nome_user, deviceid) values ('" . $username . "', '" . $password . "')");
                }
                if ($query7) {
                    $values["USER_ID"] = $row["nome_user"];
                    $values["DEVICE"] = $row["deviceid"];
                }
            } else {
                $values["DEVICE"] = "false";
                $block = "false";
            }
        }
    }
    $queryok = $mysqli->query("SELECT *\r\nFROM\r\natlasdeviceid\r\nWHERE\r\n(nome_user COLLATE latin1_general_cs)\r\n='" . $username . "'\r\nAND deviceid='" . $password . "'\r\n");
    if (0 < $queryok->num_rows) {
        $values["DEVICE"] = $username;
        $values["DEVICE"] = $password;
    } else {
        $values["DEVICE"] = "false";
        $block = "false";
    }
    $respo = $block;
    $query20 = $mysqli->query("SELECT *\r\nFROM\r\nssh_accounts\r\nWHERE\r\nlogin\r\n='" . $username . "'\r\n");
    function dateDiffInDays($date1, $date2)
    {
        $diff = strtotime($date2) - strtotime($date1);
        return abs(round($diff / 86400));
    }
    if (0 < $query20->num_rows) {
        $row = $query20->fetch_assoc();
        $acesso = $row["limite"];
        $data2 = $row["expira"];
        $timestamp = strtotime($data2);
        $data2 = date("Y-m-d", $timestamp);
        if ($respo == "false") {
            $values["DEVICE"] = "false";
        }
        $account_expiration = date("F j, Y", strtotime($row["expira"]));
        $current_date = date("F j, Y");
        if ($account_expiration != $current_date) {
            $values["is_active"] = "true";
            $values["expiration_date"] = date("Y-m-d-", strtotime($row["expira"]));
            $values["expiry"] = dateDiffInDays($current_date, $account_expiration) . " dias.";
        } else {
            $values["is_active"] = "false";
        }
    } else {
        $values["is_active"] = "false";
        $values["Status"] = "noencontrado";
    }
    $values["uuid"] = "null";
    echo json_encode($values);
    $mysqli->close();
    exit;
}

?>