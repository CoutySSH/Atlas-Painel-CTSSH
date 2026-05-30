<?php

ignore_user_abort(true);
set_time_limit(0);
$start_time = microtime(true);
$lockfile = 'lockfile.txt';

// Abre o arquivo de bloqueio com exclusão e bloqueio
$handle = fopen($lockfile, 'w+');
if (!flock($handle, LOCK_EX | LOCK_NB)) {
    echo "Outra pessoa já está acessando a página, tente novamente mais tarde.";
    exit;
}

// Conexão com o banco de dados
include('atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Erro na conexão: " . mysqli_connect_error());
}

set_include_path(get_include_path() . PATH_SEPARATOR . 'lib2');

// Verifica se o arquivo existe
if (!file_exists("admin/suspenderrev.php")) {
    echo "O código levou 1.0 segundos para ser executado";
    exit;
}

include('Net/SSH2.php');
include('vendor/event/autoload.php');
use React\EventLoop\Factory;

// Remove o arquivo de onlines.txt, se existir
if (file_exists("onlines.txt")) {
    unlink("onlines.txt");
}
// Inicia as variáveis
$dellusers = array();
$criado = false; // Inicializa a variável
$userskill = array();

// Obtém as configurações
$limiterativo = "SELECT * FROM configs WHERE id = 1";
$resultlimiterativo = mysqli_query($conn, $limiterativo);
$rowlimiterativo = mysqli_fetch_assoc($resultlimiterativo);
$limiterativo = $rowlimiterativo['corbarranav'];
$limitertempo = $rowlimiterativo['corletranav'];

// Converte minutos para segundos e define um valor mínimo
$limitertempo = max(intval($limitertempo) * 60, 300);

// Cria a tabela limiter se necessário
if ($limiterativo == 1) {
    $sql = "CREATE TABLE IF NOT EXISTS limiter (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(30) NOT NULL,
        tempo TEXT NOT NULL
    )";
    mysqli_query($conn, $sql);

    $sqluserdel = "SELECT * FROM limiter WHERE tempo = 'Deletado'";
    $resultuserdel = mysqli_query($conn, $sqluserdel);
    
    if (mysqli_num_rows($resultuserdel) > 0) {
        $lista = '';
        while ($rowuserdel = mysqli_fetch_assoc($resultuserdel)) {
            $lista .= $rowuserdel['usuario'] . "\n";
        }
        file_put_contents('limiter.txt', $lista);
        $criado = true;
    }

    $killlimiter = "SELECT * FROM limiter";
    $resultkilllimiter = mysqli_query($conn, $killlimiter);
    
    if (mysqli_num_rows($resultkilllimiter) > 0) {
        while ($rowkilllimiter = mysqli_fetch_assoc($resultkilllimiter)) {
            $userskill[] = $rowkilllimiter['usuario'];
        }
    }
}

// Tempo máximo que o servidor tem para responder
$sql = "SELECT id, ip, porta, usuario, senha FROM servidores";
$result = mysqli_query($conn, $sql);

$loop = Factory::create();
$senha = md5($_SESSION['token']);

while ($user_data = mysqli_fetch_assoc($result)) {
    $conectado = false;
    $ipeporta = $user_data['ip'] . ':6969';
    $timeout = 3;
    $socket = @fsockopen($user_data['ip'], 6969, $errno, $errstr, $timeout);

    if ($socket) {
        fclose($socket);
        $loop->addTimer(0.001, function () use ($user_data, $conn, $criado, $userskill, $ipeporta, $senha) {
            $comando = 'sudo ps -ef | grep -oP "sshd: \K\w+(?= \[priv\])" || true && sed "/^10.8.0./d" /etc/openvpn/openvpn-status.log | grep 127.0.0.1 | awk -F"," \'{print $1}\' && nc -q0 127.0.0.1 7505 echo "status" | grep -oP ".*?,\K.*?(?=,)" | sort | uniq | grep -v : || true && awk -v date="$(date -d \'60 seconds ago\' +\'%Y/%m/%d %H:%M:%S\')" \'$0 > date && /email:/ { sub(/.*email: /, "", $0); sub(/@gmail\.com$/, "", $0); if (!seen[$0]++) print }\' /var/log/v2ray/access.log';

            $write = fopen("onlines.txt", "a");
            $headers = array('Senha: ' . $senha);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://' . $ipeporta);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('comando' => $comando)));
            $output = curl_exec($ch);
            curl_close($ch);
            
            // Remove erros conhecidos da saída
            $output = str_replace("sed: can't read /etc/openvpn/openvpn-status.log: No such file or directory", "", $output);
            fwrite($write, $output);
            fclose($write);
            
            if ($criado) {
                $local_file = 'limiter.txt';
                $nome = substr(md5(uniqid(rand(), true)), 0, 10) . ".txt";
                $limiter_content = file_get_contents($local_file);

                $ch1 = curl_init();
                curl_setopt($ch1, CURLOPT_URL, 'http://' . $ipeporta);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch1, CURLOPT_POST, 1);
                curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query(array('comando' => 'echo "' . $limiter_content . '" > /root/' . $nome)));
                curl_exec($ch1);
                curl_close($ch1);

                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, 'http://' . $ipeporta);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch2, CURLOPT_POST, 1);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query(array('comando' => 'sudo python3 /root/delete.py ' . $nome . ' > /dev/null 2>/dev/null &')));
                curl_exec($ch2);
                curl_close($ch2);
            }

            if (!empty($userskill)) {
                $killstring = implode("|", $userskill);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'http://' . $ipeporta);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('comando' => 'pgrep -f "' . $killstring . '" | xargs kill > /dev/null 2>/dev/null &')));
                curl_exec($ch);
                curl_close($ch);
            }

            $comando1 = 'ps -x | grep sshd | grep -v root | grep priv | wc -l';
            $comando2 = 'sed \'/^10.8.0./d\' /etc/openvpn/openvpn-status.log | grep 127.0.0.1 | awk -F\',\' \'{print $1}\' | wc -l';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://' . $ipeporta);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('comando' => $comando1)));
            $output = curl_exec($ch);
            curl_close($ch);
            $onlineserver = intval(trim($output));

            $ch2 = curl_init();
            curl_setopt($ch2, CURLOPT_URL, 'http://' . $ipeporta);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch2, CURLOPT_POST, 1);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query(array('comando' => $comando2)));
            $output2 = curl_exec($ch2);
            curl_close($ch2);
            $total_onlineserver = intval(trim($output2));

            if ($onlineserver > $total_onlineserver) {
                $dellusers[] = $user_data['usuario'];
                $sql_del = "DELETE FROM servidores WHERE id = " . intval($user_data['id']);
                mysqli_query($conn, $sql_del);
            }
        });
    } else {
        $sql_del = "DELETE FROM servidores WHERE id = " . intval($user_data['id']);
        mysqli_query($conn, $sql_del);
    }
}

$loop->run();

$end_time = microtime(true);
$execution_time = $end_time - $start_time;
echo "O código levou " . number_format($execution_time, 1) . " segundos para ser executado";

fclose($handle);

?>
