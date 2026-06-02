<?php error_reporting(0);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function security(){
    date_default_timezone_set('America/Sao_Paulo');
        $_SESSION['sgdfsr43erfggfd4rgs3rsdfsdfsadfe'] = true;
        $_SESSION['token_invalido_'] = false;
        $_SESSION['tokenatual'] = $_SESSION['token'] ?? '9P9trMXJP9w5Wv7';
}


?>