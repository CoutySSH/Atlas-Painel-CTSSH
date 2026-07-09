<?php


session_start();
include "../atlas/conexao.php";
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!isset($_SESSION["login"]) || !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("Location: index.php");
    exit;
}
if ($_SESSION["login"] != "admin") {
    echo "Você não tem permissão para acessar essa página";
    exit;
}
include "Net/SSH2.php";
$_GET["id"] = anti_sql($_GET["id"]);
$id = $_GET["id"];
$sql = "SELECT * FROM servidores WHERE id = '" . $id . "'";
$result = $conn->query($sql);
if ($result->num_rows === 0) {
    echo "Servidor não encontrado";
    exit;
}
$row = $result->fetch_assoc();
$ipservidor = $row["ip"];
$portaservidor = $row["porta"];
$usuarioservidor = $row["usuario"];
$senhaservidor = $row["senha"];
$cpu = "grep -c cpu[0-9] /proc/stat";
$memoria = "free -h | grep -i mem | awk {'print \$2'}";
$senha = $_SESSION["token"];
$senha = md5($senha);
$modulocreate = "# -*- coding: utf-8 -*-\r\n\r\nfrom http.server import BaseHTTPRequestHandler, HTTPServer\r\nimport cgi\r\nimport subprocess\r\n\r\n# Senha de autenticação\r\nsenha_autenticacao = '" . $senha . "'\r\n\r\n# Classe de manipulador de solicitações\r\nclass MyRequestHandler(BaseHTTPRequestHandler):\r\n    def do_POST(self):\r\n        # Verifica se a senha de autenticação está presente no cabeçalho da requisição\r\n        if 'Senha' in self.headers and self.headers['Senha'] == senha_autenticacao:\r\n            # Analisa os dados da solicitação POST\r\n            form = cgi.FieldStorage(\r\n                fp=self.rfile,\r\n                headers=self.headers,\r\n                environ={'REQUEST_METHOD': 'POST'}\r\n            )\r\n            comando = form.getvalue('comando')\r\n\r\n            # Executa o comando e captura a saída\r\n            try:\r\n                resultado = subprocess.check_output(comando, shell=True, stderr=subprocess.STDOUT)\r\n            except subprocess.CalledProcessError as e:\r\n                resultado = e.output\r\n\r\n            # Envia a resposta de volta para o cliente\r\n            self.send_response(200)\r\n            self.send_header('Content-type', 'text/plain')\r\n            self.end_headers()\r\n            self.wfile.write(resultado)\r\n        else:\r\n            # Senha de autenticação inválida\r\n            self.send_response(401)\r\n            self.send_header('Content-type', 'text/plain')\r\n            self.end_headers()\r\n            self.wfile.write('Não autorizado!'.encode())\r\n\r\n# Configurações do servidor\r\nhost = '0.0.0.0'\r\nport = 6969\r\n\r\n# Cria o servidor HTTP\r\nserver = HTTPServer((host, port), MyRequestHandler)\r\n\r\n# Inicia o servidor\r\nprint('Servidor iniciado em {}:{}'.format(host, port))\r\nserver.serve_forever()\r\n";
$ssh = new Net_SSH2($ipservidor, $portaservidor);
if (!$ssh->login($usuarioservidor, $senhaservidor)) {
    echo "Falha na autenticação do servidor";
    exit;
}
echo "Servidor conectado com sucesso";
$dominio = $_SERVER["HTTP_HOST"];
$modulo = "rm atlasdata.sh || true && rm atlascreate.sh || true && rm atlasteste.sh || true && rm atlasremove.sh || true && rm delete.py || true && rm sincronizar.py || true &&\r\nwget https://painelpro.shop/modulos/atlascreate.sh && chmod 777 atlascreate.sh && wget https://painelpro.shop/modulos/atlasteste.sh && chmod 777 atlasteste.sh && wget https://painelpro.shop/modulos/atlasremove.sh && chmod 777 atlasremove.sh && wget https://painelpro.shop/modulos/delete.py && wget https://painelpro.shop/modulos/atlasdata.sh && chmod 777 atlasdata.sh && chmod 777 delete.py && wget https://painelpro.shop/modulos/sincronizar.py && chmod 777 sincronizar.py && pkill -f modulo.py > /dev/null 2>&1";
$existingCrontab = $ssh->exec("crontab -l");
if (strpos($existingCrontab, "*/10 * * * * python3 /root/modulo.py") == false) {
    $ssh->exec(" crontab -l | { cat; echo \"@reboot python3 /root/modulo.py\"; } | crontab - && crontab -l | { cat; echo \"*/10 * * * * python3 /root/modulo.py\"; } | crontab -");
}
$ssh->exec($modulo);
$ssh->exec("apt-get install python3 -y > /dev/null 2>&1");
$ssh->exec("echo \"" . $modulocreate . "\" > modulo.py && sudo pkill -f modulo.py || true");
$ssh->exec("nohup python3 modulo.py > /dev/null 2>&1 &");
$quantidadecpu = $ssh->exec($cpu);
$quantidadememoria = $ssh->exec($memoria);
$ssh->disconnect();
$sql = "UPDATE servidores SET servercpu = '" . $quantidadecpu . "', serverram = '" . $quantidadememoria . "' WHERE ip = '" . $ipservidor . "'";
$result = $conn->query($sql);
$conn->close();
unset($_SESSION["ipservidor"]);
unset($_SESSION["portaservidor"]);
unset($_SESSION["usuarioservidor"]);
unset($_SESSION["senhaservidor"]);
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
