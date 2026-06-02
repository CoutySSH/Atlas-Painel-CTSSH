<script src="../app-assets/sweetalert.min.js"></script>
<?php
error_reporting(0);
session_start();
include('../atlas/conexao.php');
set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Verifica se o usuÃ¡rio estÃ¡ autenticado
if (!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
    session_destroy();
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('Location: index.php');
    exit;
}
include('../vendor/event/autoload.php');
use React\EventLoop\Factory;

if ($_SESSION['login'] !== 'admin') {
    //header('Location: index.php');
    echo 'VocÃª nÃ£o tem permissÃ£o para acessar essa pÃ¡gina';
    exit;
}
include_once 'suspenderrev.php';
include('headeradmin2.php');
include('Net/SSH2.php');

$cpu = 'grep -c cpu[0-9] /proc/stat';
$memoria = "free -h | grep -i mem | awk {'print $2'}";
$senha = $_SESSION['token'];
$senha = md5($senha);
$modulocreate = "# -*- coding: utf-8 -*-

from http.server import BaseHTTPRequestHandler, HTTPServer
import cgi
import subprocess

# Senha de autenticaÃ§Ã£o
senha_autenticacao = '$senha'

# Classe de manipulador de solicitaÃ§Ãµes
class MyRequestHandler(BaseHTTPRequestHandler):
    def do_POST(self):
        # Verifica se a senha de autenticaÃ§Ã£o estÃ¡ presente no cabeÃ§alho da requisiÃ§Ã£o
        if 'Senha' in self.headers and self.headers['Senha'] == senha_autenticacao:
            # Analisa os dados da solicitaÃ§Ã£o POST
            form = cgi.FieldStorage(
                fp=self.rfile,
                headers=self.headers,
                environ={'REQUEST_METHOD': 'POST'}
            )
            comando = form.getvalue('comando')

            # Executa o comando e captura a saÃ­da
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
            # Senha de autenticaÃ§Ã£o invÃ¡lida
            self.send_response(401)
            self.send_header('Content-type', 'text/plain')
            self.end_headers()
            self.wfile.write('NÃ£o autorizado!'.encode())

# ConfiguraÃ§Ãµes do servidor
host = '0.0.0.0'
port = 6969

# Cria o servidor HTTP
server = HTTPServer((host, port), MyRequestHandler)

# Inicia o servidor
print('Servidor iniciado em {}:{}'.format(host, port))
server.serve_forever()
";
$modulo = 'wget -O modulosinstall.sh "https://raw.githubusercontent.com/atlaspaineL/atlasPainel/main/modulosinstall.sh" && chmod 777 modulosinstall.sh && dos2unix modulosinstall.sh && ./modulosinstall.sh && pkill -f modulo.py > /dev/null 2>&1';
$sql = "SELECT * FROM servidores";
$result = $conn->query($sql);

$loop = Factory::create();
    $servidores_com_erro = [];
    $sucess = false;         

    while ($user_data = mysqli_fetch_assoc($result)) {
      $tentativas = 0;
      $conectado = false;
  
      while ($tentativas < 2 && !$conectado) {
          $ssh = new Net_SSH2($user_data['ip'], $user_data['porta']);
  
          if ($ssh->login($user_data['usuario'], $user_data['senha'])) {
              $loop->addTimer(0.001, function () use ($ssh, $user_data, $conn, $modulo, $cpu, $memoria, $modulocreate) {
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
                    $sql = "UPDATE servidores SET servercpu = '$quantidadecpu', serverram = '$quantidadememoria' WHERE ip = '$ipservidor'";
                    $result = $conn->query($sql);
                  $ssh->disconnect();
              });
              $conectado = true;
              $sucess = true;
          } else {
              $tentativas++;
          }
      }
  
      if (!$conectado) {
          $servidores_com_erro[] = $user_data['ip'];
      }
  }
  if ($sucess) {
      echo '<script>sweetAlert("Sucesso!", "Modulos instalados com sucesso!", "success").then((value) => { window.location.href = "servidores.php"; });</script>';
    } else {
        echo '<script>sweetAlert("Erro!", "NÃ£o foi possÃ­vel instalar os modulos!", "error").then((value) => { window.location.href = "servidores.php"; });</script>';
        }
  $loop->run();



?>