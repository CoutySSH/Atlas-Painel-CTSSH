<?php
$chave = $_GET['key'] ?? '';

if ($chave === '' || !preg_match('/^[a-f0-9]{32}$/', $chave)) {
    http_response_code(403);
    die('Acesso negado.');
}

$backupPath = sys_get_temp_dir() . '/atlasbackup_' . $chave . '.sql';
if (!file_exists($backupPath)) {
    http_response_code(404);
    die('Arquivo não encontrado.');
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="atlasbackup.sql"');
header('Content-Length: ' . filesize($backupPath));
readfile($backupPath);
unlink($backupPath);
