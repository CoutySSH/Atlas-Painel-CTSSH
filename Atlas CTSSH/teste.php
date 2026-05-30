<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
$episodio_atual = isset($_GET['ep']) ? $_GET['ep'] : 1; // Obtém o número do episódio atual da URL ou define como 1 por padrão
$proximo_episodio = $episodio_atual + 1; // Calcula o próximo episódio

// Exibe o iframe com o link do episódio atual
echo '<iframe name="Player" src="https://redecanais.zip/for-all-mankind-1a-temporada-episodio-01-lua-vermelha_139ece054.html" frameborder="0" height="400" scrolling="no" width="640" allow="encrypted-media" allowFullScreen></iframe>';

// Botão para ir para o próximo episódio
echo '<button onclick="window.location.href=\'?ep='.$proximo_episodio.'\'">Próximo episódio</button>';
?>
</body>
</html>
