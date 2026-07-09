<?php


if (!isset($_SESSION)) {
    error_reporting(0);
    session_start();
}
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "Net/SSH2.php";
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:index.php");
}
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$_GET["id"] = anti_sql($_GET["id"]);
if (!empty($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM ssh_accounts WHERE id = '" . $id . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $login = $row["login"];
    $categoria = $row["categoriaid"];
    $byid = $row["byid"];
}
if ($byid == $_SESSION["iduser"]) {
    $sql2 = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
    $result = mysqli_query($conn, $sql2);
    
    $servidores_com_erro = [];
    $sucess = false;
    while ($user_data = mysqli_fetch_assoc($result)) {
        $tentativas = 0;
        $conectado = false;
        while ($tentativas < 2 && !$conectado) {
            $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
            if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
                
                    $ssh->exec("./atlasremove.sh " . $login . " ");
                    $ssh->disconnect();
                $conectado = true;
                $sucess = true;
            } else {
                $tentativas++;
            }
        }
        if (!$conectado) {
            $servidores_com_erro[] = $user_data["ip"];
        }
        foreach ($servidores_com_erro as $ip) {
            $sql2 = "SELECT id, ip, porta, usuario, senha FROM servidores WHERE ip = '" . $ip . "'";
            $result2 = mysqli_query($conn, $sql2);
            $user_data2 = mysqli_fetch_assoc($result2);
            $tentativas = 0;
            $conectado = false;
            $sucess = false;
            while ($tentativas < 2 && !$conectado) {
                $ssh = new Net_SSH2($user_data2["ip"], $user_data2["porta"]);
                if ($ssh->login($user_data2["usuario"], $user_data2["senha"])) {
                    
                        $ssh->exec("./atlasremove.sh " . $login . " ");
                        $ssh->disconnect();
                    $conectado = true;
                    $sucess = true;
                } else {
                    $tentativas++;
                }
            }
            if (!$conectado) {
                $failed_servers[] = $user_data2["nome"];
            }
        }
    }
    if ($sucess) {
        echo "excluido";
        $sql3 = "DELETE FROM ssh_accounts WHERE id = '" . $id . "'";
        $result = mysqli_query($conn, $sql3);
    } else {
        echo "erro";
    }
    
} else {
    echo "<script>sweetAlert('Oops...', 'Você não tem permissão para editar este usuário!', 'error').then(function(){window.location.href='../home.php'});</script>";
    unset($_POST["criaruser"]);
    unset($_POST["usuariofin"]);
    unset($_POST["senhafin"]);
    unset($_POST["validadefin"]);
    exit;
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