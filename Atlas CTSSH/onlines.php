<?php


ignore_user_abort(true);
set_time_limit(0);
$start_time = microtime(true);
$lockfile = "lockfile.txt";
$handle = fopen($lockfile, "w+");
if (!flock($handle, LOCK_EX | LOCK_NB)) {
    echo "Outra pessoa já está acessando a página, tente novamente mais tarde.";
    exit;
}
include "atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
set_include_path(get_include_path() . PATH_SEPARATOR . "lib2");
include "Net/SSH2.php";
include "vendor/event/autoload.php";
unlink("onlines.txt");
$dellusers = [];
$limiterativo = "SELECT * FROM configs WHERE id = 1";
$resultlimiterativo = mysqli_query($conn, $limiterativo);
$rowlimiterativo = mysqli_fetch_assoc($resultlimiterativo);
$limiterativo = $rowlimiterativo["corbarranav"];
$limitertempo = $rowlimiterativo["corletranav"];
$limitertempo = $limitertempo * 60;
if ($limitertempo < 300) {
    $limitertempo = 300;
}
if ($limiterativo == 1) {
    $sql = "CREATE TABLE IF NOT EXISTS limiter (\r\n        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,\r\n        usuario VARCHAR(30) NOT NULL,\r\n        tempo TEXT NOT NULL\r\n    )";
    mysqli_query($conn, $sql);
    $sqluserdel = "SELECT * FROM limiter WHERE tempo = 'Deletado'";
    $resultuserdel = mysqli_query($conn, $sqluserdel);
    if (0 < mysqli_num_rows($resultuserdel)) {
        $lista = "";
        while ($rowuserdel = mysqli_fetch_assoc($resultuserdel)) {
            $lista .= $rowuserdel["usuario"] . "\n";
        }
        $arquivo = fopen("limiter.txt", "w");
        fwrite($arquivo, $lista);
        fclose($arquivo);
        $criado = true;
    }
    $killlimiter = "SELECT * FROM limiter";
    $resultkilllimiter = mysqli_query($conn, $killlimiter);
    if (0 < mysqli_num_rows($resultkilllimiter)) {
        $userskill = [];
        $killuser = true;
        while ($rowkilllimiter = mysqli_fetch_assoc($resultkilllimiter)) {
            $userskill[] = $rowkilllimiter["usuario"];
        }
    }
}
$sql = "SELECT id, ip, porta, usuario, senha FROM servidores";
$result = mysqli_query($conn, $sql);
$loop = React\EventLoop\Factory::create();
while ($user_data = mysqli_fetch_assoc($result)) {
    $tentativas = 0;
    $conectado = false;
    while ($tentativas < 2 && !$conectado) {
        $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);
        if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
            $loop->addTimer(0, function () use($ssh) {
                $output = $ssh->exec("ps -ef | grep -oP \"sshd: \\K\\w+(?= \\[priv\\])\" || true && nc -q0 127.0.0.1 7505 echo <<< \"status\" | grep -oP \".*?,\\K.*?(?=,)\" | sort | uniq | grep -v :");
                $write = fopen("onlines.txt", "a");
                fwrite($write, $output);
                fclose($write);
                if ($criado) {
                    $local_file = "limiter.txt";
                    $nome = md5(uniqid(rand(), true));
                    $nome = substr($nome, 0, 10);
                    $nome = $nome . ".txt";
                    $limiter_content = file_get_contents($local_file);
                    $ssh->exec("echo \"" . $limiter_content . "\" > /root/" . $nome);
                    $ssh->exec("python3 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
                    $ssh->exec("python2 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
                }
                if (!empty($userskill)) {
                    $killstring = implode("|", $userskill);
                    $ssh->exec("pgrep -f \"" . $killstring . "\" | xargs kill > /dev/null 2>/dev/null &");
                }
                $onlineserver = $ssh->exec("ps -x | grep sshd | grep -v root | grep priv | wc -l");
                $onlineserver = intval(trim($onlineserver));
                $sql_update = "UPDATE servidores SET onlines = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql_update);
                mysqli_stmt_bind_param($stmt, "ii", $onlineserver, $user_data["id"]);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            });
            $conectado = true;
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
    while ($tentativas < 2 && !$conectado) {
        $ssh = new Net_SSH2($user_data2["ip"], $user_data2["porta"]);
        if ($ssh->login($user_data2["usuario"], $user_data2["senha"])) {
            $loop->addTimer(0, function () use($ssh) {
                $output = $ssh->exec("ps -ef | grep -oP \"sshd: \\K\\w+(?= \\[priv\\])\" && nc -q0 127.0.0.1 7505 echo <<< \"status\" | grep -oP \".*?,\\K.*?(?=,)\" | sort | uniq | grep -v :");
                $write = fopen("onlines.txt", "a");
                fwrite($write, $output);
                fclose($write);
                if ($criado) {
                    $local_file = "limiter.txt";
                    $nome = md5(uniqid(rand(), true));
                    $nome = substr($nome, 0, 10);
                    $nome = $nome . ".txt";
                    $limiter_content = file_get_contents($local_file);
                    $ssh->exec("echo \"" . $limiter_content . "\" > /root/" . $nome);
                    $ssh->exec("python3 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
                    $ssh->exec("python2 /root/delete.py " . $nome . " > /dev/null 2>/dev/null &");
                }
                if (!empty($userskill)) {
                    $killstring = implode("|", $userskill);
                    $ssh->exec("pgrep -f \"" . $killstring . "\" | xargs kill > /dev/null 2>/dev/null &");
                }
                $onlineserver = $ssh->exec("ps -x | grep sshd | grep -v root | grep priv | wc -l");
                $onlineserver = intval(trim($onlineserver));
                $sql_update = "UPDATE servidores SET onlines = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql_update);
                mysqli_stmt_bind_param($stmt, "ii", $onlineserver, $user_data["id"]);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            });
            $conectado = true;
        } else {
            $tentativas++;
        }
    }
    if (!$conectado) {
        echo "Servidor " . $user_data2["ip"] . " não está respondendo." . PHP_EOL;
    }
}
$loop->run();
$sql22 = "DELETE FROM onlines";
mysqli_query($conn, $sql22);
$var = ler();
$var = trim($var);
$var = explode("\n", $var);
foreach ($var as $value) {
    $value = trim($value);
    $value = mysqli_real_escape_string($conn, $value);
    $values[] = "('" . $value . "')";
}
$values = implode(",", $values);
$sql = "UPDATE ssh_accounts SET status = 'Online' WHERE login IN (" . $values . ")";
$result = mysqli_query($conn, $sql);
$sql2 = "UPDATE ssh_accounts SET status = 'Offline' WHERE login NOT IN (" . $values . ")";
$result2 = mysqli_query($conn, $sql2);
$sql213 = "ALTER TABLE onlines MODIFY quantidade INT DEFAULT 0;";
$result213 = mysqli_query($conn, $sql213);
$sql = "INSERT INTO onlines (usuario) VALUES " . $values;
mysqli_query($conn, $sql);
if (mysqli_error($conn)) {
    echo mysqli_error($conn);
}
$sql = "SELECT * FROM onlines";
$result = mysqli_query($conn, $sql);
while ($user_data = mysqli_fetch_assoc($result)) {
    $sql = "UPDATE onlines SET quantidade = quantidade + 1 WHERE usuario = '" . $user_data["usuario"] . "'";
    mysqli_query($conn, $sql);
}
$sql = "DELETE FROM onlines WHERE id NOT IN (SELECT * FROM (SELECT MIN(id) FROM onlines GROUP BY usuario) AS t)";
$result = mysqli_query($conn, $sql);
if ($limiterativo == 1) {
    $delete = "DELETE FROM limiter WHERE tempo = 'Deletado'";
    mysqli_query($conn, $delete);
}
if ($limiterativo == 1) {
    $sql = "SELECT onlines.usuario, ssh_accounts.limite, onlines.quantidade FROM onlines JOIN ssh_accounts ON onlines.usuario = ssh_accounts.login WHERE onlines.quantidade > ssh_accounts.limite";
    $result = mysqli_query($conn, $sql);
    $usuarios = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $usuarios[] = $row["usuario"];
    }
    $sqllimiter = "SELECT * FROM limiter";
    $resultlimiter = mysqli_query($conn, $sqllimiter);
    $exceeded_users = [];
    date_default_timezone_set("America/Sao_Paulo");
    $now = date("Y-m-d H:i:s");
    while ($rowlimiter = mysqli_fetch_assoc($resultlimiter)) {
        $usuario_limite = $rowlimiter["usuario"];
        $tempo = $rowlimiter["tempo"];
        if ($usuario_limite != "root") {
            $sqlcheck = "SELECT onlines.quantidade FROM onlines JOIN ssh_accounts ON onlines.usuario = ssh_accounts.login WHERE onlines.usuario = '" . $usuario_limite . "' AND onlines.quantidade > ssh_accounts.limite";
            $resultcheck = mysqli_query($conn, $sqlcheck);
            if (0 < mysqli_num_rows($resultcheck)) {
                $diff = $now - strtotime($tempo);
                if ($limitertempo < $diff) {
                    unset($exceeded_users[$usuario_limite]);
                } else {
                    if (!array_key_exists($usuario_limite, $exceeded_users)) {
                        $exceeded_users[$usuario_limite] = date("Y-m-d H:i:s");
                    }
                }
            } else {
                $sqldel = "DELETE FROM limiter WHERE usuario = '" . $usuario_limite . "'";
                mysqli_query($conn, $sqldel);
                unset($exceeded_users[$usuario_limite]);
            }
        }
    }
    $sqlcheckdel = "SELECT * FROM limiter";
    $resultcheckdel = mysqli_query($conn, $sqlcheckdel);
    while ($rowcheck = mysqli_fetch_assoc($resultcheckdel)) {
        date_default_timezone_set("America/Sao_Paulo");
        $timestamp = strtotime($rowcheck["tempo"]);
        if ($timestamp !== false) {
            $temporestante = $timestamp - time();
            $temporestante = $temporestante + $limitertempo;
            if ($temporestante < 0) {
                $sqldel = "UPDATE ssh_accounts SET mainid = 'Limite Ultrapassado' WHERE login = '" . $rowcheck["usuario"] . "'";
                mysqli_query($conn, $sqldel);
                $sqlli = "UPDATE limiter SET tempo = 'Deletado' WHERE usuario = '" . $rowcheck["usuario"] . "'";
                mysqli_query($conn, $sqlli);
            }
        }
    }
    foreach ($usuarios as $usuario) {
        if (!array_key_exists($usuario, $exceeded_users)) {
            $sqlinsert = "INSERT INTO limiter (usuario, tempo) VALUES ('" . $usuario . "', '" . $now . "')";
            mysqli_query($conn, $sqlinsert);
        }
    }
}
unlink("limiter.txt");
$end_time = microtime(true);
$time_diff = $end_time - $start_time;
echo "O código levou " . $time_diff . " segundos para ser executado";
flock($handle, LOCK_UN);
fclose($handle);
unlink($lockfile);
function ler()
{
    $read = fopen("onlines.txt", "r");
    $onlines = fread($read, filesize("onlines.txt"));
    fclose($read);
    return $onlines;
}

?>