<?php


if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:index.php");
    exit;
}
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$sql5 = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["iduser"] . "'";
$sql5 = $conn->query($sql5);
$row = $sql5->fetch_assoc();
$validade = $row["expira"];
$categoria = $row["categoriaid"];
$tipo = $row["tipo"];
$_SESSION["limite"] = $row["limite"];
$_SESSION["tipodeconta"] = $row["tipo"];
if ($_SESSION["tipodeconta"] == "") {
    $_SESSION["tipodeconta"] = "Validade";
}
date_default_timezone_set("America/Sao_Paulo");
$hoje = date("Y-m-d H:i:s");
if ($_SESSION["tipodeconta"] == "Credito") {
    if ($_SESSION["limite"] < 1) {
        echo "<script>alert('Limite Atingido')</script><script>window.location.href = '../home.php'</script>";
        unset($_POST["criaruser"]);
        unset($_POST["usuariofin"]);
        unset($_POST["senhafin"]);
        unset($_POST["validadefin"]);
        exit;
    }
} else {
    if ($_SESSION["tipodeconta"] == "Validade" && $validade < $hoje) {
        echo "<script>alert('Sua conta está vencida')</script><script>window.location.href = '../home.php'</script>";
        unset($_POST["criaruser"]);
        unset($_POST["usuariofin"]);
        unset($_POST["senhafin"]);
        unset($_POST["validadefin"]);
        exit;
    }
}
if (!empty($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM ssh_accounts WHERE id = '" . $id . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $login = $row["login"];
    $senha = $row["senha"];
    $validade = $row["expira"];
    $limite = $row["limite"];
    $byid = $row["byid"];
    if ($_SESSION["tipodeconta"] == "Credito" && $_SESSION["limite"] < $limite) {
        echo "<script>alert('Limite Atingido')</script><script>window.location.href = '../home.php'</script>";
        unset($_POST["criaruser"]);
        unset($_POST["usuariofin"]);
        unset($_POST["senhafin"]);
        unset($_POST["validadefin"]);
        exit;
    }
    $_SESSION["categoriarenov"] = $row["categoriaid"];
    if ($validade < date("Y-m-d H:i:s")) {
        $validade = date("Y-m-d H:i:s");
    }
}
if ($byid == $_SESSION["iduser"]) {
    date_default_timezone_set("America/Sao_Paulo");
    $novadata = date("Y-m-d H:i:s", strtotime("+31 days", strtotime($validade)));
    $novadata = date("Y-m-d H:i:s", strtotime($novadata));
    $data = date("Y-m-d H:i:s");
    $diferenca = strtotime($novadata) - strtotime($data);
    $dias = floor($diferenca / 86400);
    date_default_timezone_set("America/Sao_Paulo");
    $datahoje = date("d-m-Y H:i:s");
    $sql10 = "INSERT INTO logs (revenda, validade, texto, userid) VALUES ('" . $_SESSION["login"] . "', '" . $datahoje . "', 'Renovou 30 dias para o usuario " . $login . "', '" . $_SESSION["iduser"] . "')";
    $result10 = mysqli_query($conn, $sql10);
    set_time_limit(0);
    ignore_user_abort(true);
    set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
    $tentativas = 0;
    $max_tentativas = 3;
    $sucesso = 0;
    include "Net/SSH2.php";
    define("SSH_PORT", 22);
    
    $sql2 = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
    $result = $conn->query($sql2);
    $servidores_com_erro = [];
    $sucess = false;
    $sucess_servers = [];
    while ($user_data = mysqli_fetch_assoc($result)) {
        $tentativas = 0;
        $conectado = false;
        while ($tentativas < 2 && !$conectado) {
            $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
            if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
                
                    $ssh->exec("rm -rf /etc/SSHPlus/userteste/" . $login . ".sh > /dev/null 2>&1 &");
                    $ssh->exec("./atlasdata.sh " . $login . " " . $dias . " > /dev/null 2>&1 &");
                    $ssh->exec("./atlascreate.sh " . $login . " " . $senha . " " . $dias . " " . $limite . " ");
                    $ssh->disconnect();
                $sucess_servers[] = $user_data["nome"];
                $conectado = true;
                $sucess = true;
            } else {
                $tentativas++;
            }
        }
        if (!$conectado) {
            $servidores_com_erro[] = $user_data["ip"];
            $failed_servers[] = $user_data["nome"];
        }
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
                
                    $ssh->exec("rm -rf /etc/SSHPlus/userteste/" . $login . ".sh > /dev/null 2>&1 &");
                    $ssh->exec("./atlasdata.sh " . $login . " " . $dias . " > /dev/null 2>&1 &");
                    $ssh->exec("./atlascreate.sh " . $login . " " . $senha . " " . $dias . " " . $limite . " ");
                    $ssh->disconnect();
                $conectado = true;
                $sucess_servers[] = $user_data2["nome"];
                $sucess = true;
            } else {
                $tentativas++;
            }
        }
        if (!$conectado) {
            $failed_servers[] = $user_data2["nome"];
        }
    }
    if ($sucess) {
        $sql3 = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["iduser"] . "'";
        $result3 = mysqli_query($conn, $sql3);
        $row3 = mysqli_fetch_assoc($result3);
        $limiteatual = $row3["limite"];
        $limiteatual = $limiteatual - $limite;
        if ($tipo == "Credito") {
            $sql11 = "UPDATE atribuidos SET limite = '" . $limiteatual . "' WHERE userid = '" . $_SESSION["iduser"] . "'";
            $result11 = mysqli_query($conn, $sql11);
        }
        echo "Renovado com Sucesso!";
        $sql = "UPDATE ssh_accounts SET expira = '" . $novadata . "', mainid = '0' WHERE id = '" . $id . "'";
        $result = mysqli_query($conn, $sql);
    }
    if (!$sucess) {
        echo "Erro ao renovar!";
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