<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
if (!isset($_SESSION)) {
    error_reporting(0);
    session_start();
}
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:index.php");
}
include "header2.php";
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$_GET["id"] = anti_sql($_GET["id"]);
if (!empty($_GET["id"])) {
    $id = $_GET["id"];
}
$sql = "SELECT * FROM atribuidos WHERE userid = '" . $id . "'";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $categoria = $row["categoriaid"];
        $limitetinha = $row["limite"];
        $byid = $row["byid"];
    }
}
if ($byid == $_SESSION["iduser"]) {
    $contas = NULL;
    $ssh_accounts = NULL;
    set_time_limit(0);
    ignore_user_abort(true);
    set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
    include "Net/SSH2.php";
    $sql1 = "SELECT * FROM accounts WHERE byid = '" . $id . "'";
    $result1 = $conn->query($sql1);
    if (0 < $result1->num_rows) {
        while ($row1 = $result1->fetch_assoc()) {
            $contas[] = $row1;
        }
    }
    if ($contas != NULL) {
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
    }
    $sql3 = "SELECT * FROM accounts WHERE id = '" . $id . "'";
    $result3 = $conn->query($sql3);
    if (0 < $result3->num_rows) {
        while ($row3 = $result3->fetch_assoc()) {
            $contas[] = $row3;
            $deletes[] = $row3;
        }
    }
    if ($contas != NULL) {
        foreach ($contas as $conta) {
            $sql2 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
            $result2 = $conn->query($sql2);
            if (0 < $result2->num_rows) {
                while ($row2 = $result2->fetch_assoc()) {
                    $contas[] = $row2;
                }
            }
        }
    }
    $sql55 = "SELECT * FROM accounts WHERE byid = '" . $conta["id"] . "'";
    $result55 = $conn->query($sql55);
    if (0 < $result55->num_rows) {
        while ($row55 = $result55->fetch_assoc()) {
            $dells[] = $row55;
        }
    }
    $contas = array_unique($contas, SORT_REGULAR);
    foreach ($contas as $conta) {
        $sql3 = "SELECT * FROM ssh_accounts WHERE byid = '" . $conta["id"] . "'";
        $result3 = $conn->query($sql3);
        if (0 < $result3->num_rows) {
            while ($row3 = $result3->fetch_assoc()) {
                $ssh_accounts[] = $row3;
            }
        }
    }
    $nome = md5(uniqid(rand(), true));
    $nome = substr($nome, 0, 10);
    $nome = $nome . ".txt";
    $file = fopen((int) $nome, "w");
    if ($ssh_accounts != NULL) {
        foreach ($ssh_accounts as $ssh_account) {
            $login = $ssh_account["login"];
            fwrite($file, $login . PHP_EOL);
        }
    }
    $sql2 = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
    $result = $conn->query($sql2);
    if (0 < $result->num_rows) {
        
        $servidores_com_erro = [];
        $sucess = false;
        while ($user_data = mysqli_fetch_assoc($result)) {
            $tentativas = 0;
            $conectado = false;
            while ($tentativas < 2 && !$conectado) {
                $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
                if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
                    
                        $local_file = $nome;
                        $limiter_content = file_get_contents($local_file);
                        $ssh->exec("echo \"" . $limiter_content . "\" > /root/" . $nome);
                        $ssh->exec("python3 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
                        $ssh->exec("python2 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
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
                    
                        $local_file = $nome;
                        $limiter_content = file_get_contents($local_file);
                        $ssh->exec("echo \"" . $limiter_content . "\" > /root/" . $nome);
                        $ssh->exec("python3 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
                        $ssh->exec("python2 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
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
        if ($sucess) {
            echo "1";
            foreach ($contas as $conta) {
                $sql4 = "DELETE FROM accounts WHERE id = '" . $conta["id"] . "'";
                $result4 = $conn->query($sql4);
                $sql5 = "DELETE FROM ssh_accounts WHERE byid = '" . $conta["id"] . "'";
                $result5 = $conn->query($sql5);
                $sql6 = "DELETE FROM atribuidos WHERE userid = '" . $conta["id"] . "'";
                $result6 = $conn->query($sql6);
            }
            foreach ($deletes as $delete) {
                $sql4 = "DELETE FROM accounts WHERE id = '" . $delete["id"] . "'";
                $result4 = $conn->query($sql4);
                $sql5 = "DELETE FROM ssh_accounts WHERE byid = '" . $delete["id"] . "'";
                $result5 = $conn->query($sql5);
                $sql6 = "DELETE FROM atribuidos WHERE userid = '" . $delete["id"] . "'";
                $result6 = $conn->query($sql6);
            }
            echo "<script>sweetAlert('Sucesso!', 'Contas deletadas com sucesso!', 'success').then(function(){window.location.href = 'listarrevendedores.php';});</script>";
        } else {
            echo "<script>sweetAlert('Erro!', 'Erro ao deletar contas!', 'error').then(function(){window.location.href = 'listarrevendedores.php';});</script>";
        }
        
    }
    unlink($nome);
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