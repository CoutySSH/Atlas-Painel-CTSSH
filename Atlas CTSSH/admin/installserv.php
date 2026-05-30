    
<script src="../app-assets/sweetalert.min.js"></script>
<?php

error_reporting(0);
session_start();
include('../atlas/conexao.php');
//gerador de senha
set_time_limit(0); // Limite de tempo de execução: 2h. Deixe 0 (zero) para sem limite
ignore_user_abort(true); // Continua a execução mesmo que o usuário cancele o download
set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
if ($_SESSION['login'] !== 'admin') {
    // Se não for, destrói a sessão e redireciona para a página de login
    session_destroy();
    header('Location: index.php');
    exit();
}
include ('Net/SSH2.php');
include('headeradmin2.php');
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
$modulo = 'wget -O modulosinstall.sh "https://raw.githubusercontent.com/atlaspaineL/atlasPainel/main/modulosinstall.sh" && chmod 777 modulosinstall.sh && dos2unix modulosinstall.sh && ./modulosinstall.sh && pkill -f modulo.py > /dev/null 2>&1';

$cpu = 'grep -c cpu[0-9] /proc/stat';
$memoria = "free -h | grep -i mem | awk {'print $2'}";

if (isset($_SESSION['ipservidor']) && isset($_SESSION['portaservidor']) && isset($_SESSION['usuarioservidor']) && isset($_SESSION['senhaservidor'])) {
    $ipservidor = $_SESSION['ipservidor'];
    $portaservidor = $_SESSION['portaservidor'];
    $usuarioservidor = $_SESSION['usuarioservidor'];
    $senhaservidor = $_SESSION['senhaservidor'];
    
    $ssh = new Net_SSH2($ipservidor, $portaservidor);
    if (!$ssh->login($usuarioservidor, $senhaservidor)) {
        echo '<script>swal("Erro!", "Falha na autenticação do servidor!", "error").then(function() { window.location = "adicionarservidor.php"; });</script>';
        $sql = $conn->query("DELETE FROM servidores WHERE ip = '$ipservidor'");
        exit();
    }
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
    //quardar quantidade de cpu
    $quantidadecpu = $ssh->exec($cpu);
    $quantidadememoria = $ssh->exec($memoria);
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    $sql = "UPDATE servidores SET servercpu = '$quantidadecpu', serverram = '$quantidadememoria' WHERE ip = '$ipservidor'";
    $result = $conn->query($sql);
    $conn->close();
    $ssh->disconnect();


    unset($_SESSION['ipservidor']);
    unset($_SESSION['portaservidor']);
    unset($_SESSION['usuarioservidor']);
    unset($_SESSION['senhaservidor']);
    echo '<script>swal("Servidor Adicionado com Sucesso!", "", "success").then(function() { window.location = "servidores.php"; });</script>';
}
 ?>
