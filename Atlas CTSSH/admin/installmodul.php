<?php
error_reporting(0);
session_start();
include('../atlas/conexao.php');
set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Verifica se o usuário está autenticado
if (!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
    session_destroy();
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('Location: index.php');
    exit;
}

if ($_SESSION['login'] !== 'admin') {
    //header('Location: index.php');
    echo 'Você não tem permissão para acessar essa página';
    exit;
}

include('Net/SSH2.php');

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

//anti sql injection na $_GET['id']
$_GET['id'] = anti_sql($_GET['id']);
$id = $_GET['id'];

$sql = "SELECT * FROM servidores WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo 'Servidor não encontrado';
    exit;
}

$row = $result->fetch_assoc();

$ipservidor = $row['ip'];
$portaservidor = $row['porta'];
$usuarioservidor = $row['usuario'];
$senhaservidor = $row['senha'];
$cpu = 'grep -c cpu[0-9] /proc/stat';
$memoria = "free -h | grep -i mem | awk {'print $2'}";
$senha = $_SESSION['token'];
$senha = md5($senha);
$modulocreate = "# -*- coding: utf-8 -*-

from http.server import BaseHTTPRequestHandler, HTTPServer
import cgi
import subprocess

# Senha de autenticação
senha_autenticacao = '$senha'

# Classe de manipulador de solicitações
class MyRequestHandler(BaseHTTPRequestHandler):
    def do_POST(self):
        # Verifica se a senha de autenticação está presente no cabeçalho da requisição
        if 'Senha' in self.headers and self.headers['Senha'] == senha_autenticacao:
            # Analisa os dados da solicitação POST
            form = cgi.FieldStorage(
                fp=self.rfile,
                headers=self.headers,
                environ={'REQUEST_METHOD': 'POST'}
            )
            comando = form.getvalue('comando')

            # Executa o comando e captura a saída
            try:
                resultado = subprocess.check_output(comando, shell=True, stderr=subprocess.STDOUT)
            except subprocess.CalledProcessError as e:
                resultado = e.output

            # Envia a resposta de volta para o cliente
            self.send_response(200)
            self.send_header('Content-type', 'text/plain')
            self.end_headers()
            self.wfile.write(resultado)
        else:
            # Senha de autenticação inválida
            self.send_response(401)
            self.send_header('Content-type', 'text/plain')
            self.end_headers()
            self.wfile.write('Não autorizado!'.encode())

# Configurações do servidor
host = '0.0.0.0'
port = 6969

# Cria o servidor HTTP
server = HTTPServer((host, port), MyRequestHandler)

# Inicia o servidor
print('Servidor iniciado em {}:{}'.format(host, port))
server.serve_forever()
";

// Conecta no servidor via SSH e executa o comando
$ssh = new Net_SSH2($ipservidor, $portaservidor);
if (!$ssh->login($usuarioservidor, $senhaservidor)) {
    echo 'Falha na autenticação do servidor';
    exit;
}else{
    echo 'Servidor conectado com sucesso';
}
$dominio = $_SERVER['HTTP_HOST'];

$modulo = 'wget -O modulosinstall.sh "https://raw.githubusercontent.com/atlaspaineL/atlasPainel/main/modulosinstall.sh" && chmod 777 modulosinstall.sh && dos2unix modulosinstall.sh && ./modulosinstall.sh && pkill -f modulo.py > /dev/null 2>&1';

$existingCrontab = $ssh->exec('crontab -l');
if (strpos($existingCrontab, '*/10 * * * * python3 /root/modulo.py') !== false) {
} else {
    // Adiciona a tarefa cron ao crontab
    $ssh->exec(' crontab -l | { cat; echo "@reboot python3 /root/modulo.py"; } | crontab - && crontab -l | { cat; echo "*/10 * * * * python3 /root/modulo.py"; } | crontab -');

}
$ssh->exec($modulo);
$ssh->exec('apt-get install python3 -y > /dev/null 2>&1');
$ssh->exec('echo "' . $modulocreate . '" > modulo.py && sudo pkill -f modulo.py || true');
$ssh->exec('nohup python3 modulo.py > /dev/null 2>&1 &');
$quantidadecpu = $ssh->exec($cpu);
$quantidadememoria = $ssh->exec($memoria);
$ssh->disconnect();

// Atualiza as informações do servidor no banco de dados
$sql = "UPDATE servidores SET servercpu = '$quantidadecpu', serverram = '$quantidadememoria' WHERE ip = '$ipservidor'";
$result = $conn->query($sql);

$conn->close();
unset($_SESSION['ipservidor']);
unset($_SESSION['portaservidor']);
unset($_SESSION['usuarioservidor']);
unset($_SESSION['senhaservidor']);

?>
