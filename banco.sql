-- ============================================================
-- Atlas Painel CTSSH - Banco de Dados
-- ============================================================

CREATE TABLE IF NOT EXISTS `accounts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nome` varchar(255) DEFAULT NULL,
    `contato` varchar(255) DEFAULT NULL,
    `login` varchar(50) NOT NULL DEFAULT '0',
    `token` varchar(330) NOT NULL DEFAULT '0',
    `mb` varchar(50) NOT NULL DEFAULT '0',
    `senha` varchar(50) NOT NULL DEFAULT '0',
    `byid` varchar(50) NOT NULL DEFAULT '0',
    `mainid` varchar(50) NOT NULL DEFAULT '0',
    `accesstoken` text DEFAULT NULL,
    `valorusuario` varchar(50) DEFAULT NULL,
    `valorrevenda` varchar(50) DEFAULT NULL,
    `idtelegram` text DEFAULT NULL,
    `tempo` text DEFAULT NULL,
    `tokenvenda` text DEFAULT NULL,
    `acesstokenpaghiper` text DEFAULT NULL,
    `formadepag` text DEFAULT NULL,
    `tokenpaghiper` text DEFAULT NULL,
    `whatsapp` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `atlasdeviceid` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nome_user` varchar(255) DEFAULT NULL,
    `deviceid` varchar(255) DEFAULT NULL,
    `byid` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `atribuidos` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `valor` varchar(255) DEFAULT NULL,
    `categoriaid` int(11) NOT NULL DEFAULT 0,
    `userid` int(11) NOT NULL DEFAULT 0,
    `byid` int(11) NOT NULL DEFAULT 0,
    `limite` int(11) NOT NULL DEFAULT 0,
    `limitetest` int(11) DEFAULT NULL,
    `tipo` text NOT NULL,
    `expira` text DEFAULT NULL,
    `subrev` int(11) NOT NULL DEFAULT 0,
    `suspenso` int(11) NOT NULL DEFAULT 0,
    `notificado` text DEFAULT 'nao',
    `valormensal` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `bot` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `app` text DEFAULT NULL,
    `sender` text DEFAULT NULL,
    `message` text DEFAULT NULL,
    `data` text DEFAULT NULL,
    `idpagamento` text DEFAULT NULL,
    `access_token` text DEFAULT NULL,
    `quantidadeuser` text DEFAULT NULL,
    `status` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `categorias` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `subid` int(11) DEFAULT NULL,
    `nome` varchar(150) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `configs` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `nomepainel` text DEFAULT NULL,
    `logo` text DEFAULT NULL,
    `icon` text DEFAULT NULL,
    `corborder` text DEFAULT NULL,
    `corletranav` text DEFAULT NULL,
    `deviceativo` text DEFAULT NULL,
    `imglogin` text DEFAULT NULL,
    `corbarranav` text DEFAULT NULL,
    `corfundologo` text DEFAULT NULL,
    `corcard` text DEFAULT NULL,
    `cortextcard` text DEFAULT NULL,
    `cornavsuperior` text DEFAULT NULL,
    `minimocompra` text DEFAULT NULL,
    `textoedit` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `cupons` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `nome` varchar(30) NOT NULL,
    `cupom` varchar(50) NOT NULL,
    `desconto` varchar(50) NOT NULL,
    `usado` varchar(50) NOT NULL,
    `byid` varchar(50) NOT NULL,
    `vezesuso` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `limiter` (
    `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `usuario` VARCHAR(30) NOT NULL,
    `tempo` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `logs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `userid` int(11) DEFAULT 0,
    `texto` text DEFAULT NULL,
    `validade` text DEFAULT NULL,
    `revenda` text DEFAULT NULL,
    `byid` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `mensagens` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `funcao` text DEFAULT NULL,
    `mensagem` text DEFAULT NULL,
    `ativo` text DEFAULT NULL,
    `hora` text DEFAULT NULL,
    `byid` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `onlines` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `usuario` text NOT NULL,
    `quantidade` text NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `pagamentos` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `idpagamento` varchar(50) DEFAULT NULL,
    `valor` varchar(50) DEFAULT NULL,
    `texto` text DEFAULT NULL,
    `iduser` varchar(50) DEFAULT NULL,
    `data` text DEFAULT NULL,
    `status` text DEFAULT NULL,
    `login` text DEFAULT NULL,
    `byid` varchar(50) DEFAULT NULL,
    `access_token` text DEFAULT NULL,
    `tipo` text DEFAULT NULL,
    `addlimite` int(11) DEFAULT NULL,
    `formadepag` text DEFAULT NULL,
    `tokenpaghiper` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `servidores` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `subid` int(11) NOT NULL DEFAULT 0,
    `nome` varchar(150) NOT NULL DEFAULT '0',
    `porta` int(11) NOT NULL DEFAULT 0,
    `usuario` varchar(150) NOT NULL DEFAULT '0',
    `senha` varchar(150) NOT NULL DEFAULT '0',
    `ip` varchar(150) NOT NULL DEFAULT '0',
    `servercpu` varchar(150) NOT NULL DEFAULT '0',
    `serverram` varchar(150) NOT NULL DEFAULT '0',
    `onlines` varchar(150) NOT NULL DEFAULT '0',
    `lastview` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `ssh_accounts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `byid` int(11) NOT NULL DEFAULT 0,
    `categoriaid` int(11) NOT NULL DEFAULT 0,
    `limite` int(11) NOT NULL DEFAULT 0,
    `bycredit` int(11) NOT NULL DEFAULT 0,
    `login` varchar(50) NOT NULL DEFAULT '0',
    `senha` varchar(50) NOT NULL DEFAULT '0',
    `mainid` text NOT NULL,
    `expira` text DEFAULT NULL,
    `lastview` text DEFAULT NULL,
    `status` text DEFAULT NULL,
    `notificado` text DEFAULT 'nao',
    `whatsapp` text DEFAULT NULL,
    `valormensal` text DEFAULT NULL,
    `uuid` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `userlimiter` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nome_user` varchar(255) DEFAULT NULL,
    `limiter` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `whatsapp` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `token` text DEFAULT NULL,
    `sessao` text DEFAULT NULL,
    `ativo` text DEFAULT '1',
    `byid` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- Dados iniciais
-- ============================================================

INSERT INTO `configs` (`id`, `nomepainel`, `logo`, `icon`) VALUES
(1, 'Atlas CTSSH', 'https://i.ibb.co/pjTD1H3V/Chat-GPT-Image-25-de-mai-de-2026-22-25-05-removebg-preview.png', 'https://i.ibb.co/pjTD1H3V/Chat-GPT-Image-25-de-mai-de-2026-22-25-05-removebg-preview.png');

INSERT INTO `accounts` (`id`, `nome`, `contato`, `login`, `token`, `mb`, `senha`, `byid`, `mainid`, `accesstoken`, `valorusuario`, `valorrevenda`) VALUES
(1, 'admin', 'admin', 'admin', 'admin', '60', '12345', '0', '0', NULL, '0', '0');
