#!/bin/bash

# ===========================================================================
#  install01.sh - Atlas Web 2026 - Instalador Otimizado
# ===========================================================================
#  Requer: Ubuntu 20.04+ / Debian 11+ | RAM >= 512MB | Disco >= 2GB livre
#  Modo de uso:
#    chmod +x install01.sh
#    sudo ./install01.sh
# ===========================================================================

# ─── Cores ──────────────────────────────────────────────────────────────────
VERDE='\033[0;32m'; AZUL='\033[0;34m'; AMARELO='\033[1;33m'
VERMELHO='\033[0;31m'; CIANO='\033[0;36m'; BRANCO='\033[1;37m'
NEGRITO='\033[1m'; NC='\033[0m'

# ─── Config Globais ─────────────────────────────────────────────────────────
LOG_FILE="/var/log/atlas_install01.log"
META_FILE="/root/.atlas_meta"
CONEXAO_PATH="/var/www/html/atlas/conexao.php"
BACKUP_DIR="/root/backups/atlas_$(date +%Y%m%d_%H%M%S)"
REQUIRED_DISK_MB=2048
REQUIRED_RAM_MB=512
SCRIPT_VER="1.0"

# ─── Funções Core ───────────────────────────────────────────────────────────

log() {
    local msg="[$(date '+%Y-%m-%d %H:%M:%S')] $1"
    echo "$msg" | tee -a "$LOG_FILE"
}

linha() {
    local cor="${1:-$AZUL}"
    echo -e "${cor}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
}

titulo() {
    local num="$1"; local texto="$2"
    echo -e ""
    linha "$AZUL"
    echo -e "${AZUL}┃  ${BRANCO}${NEGRITO}[${num}]${NC} ${BRANCO}${texto}${NC}"
    linha "$AZUL"
}

subtitulo() { echo -e "${CIANO}${NEGRITO}▸ $1${NC}"; }
ok()  { echo -e " ${VERDE}✔${NC} $1"; log "OK: $1"; }
fail_() { echo -e " ${VERMELHO}✘${NC} $1"; log "ERRO: $1"; }
warn() { echo -e " ${AMARELO}⚠${NC} $1"; log "AVISO: $1"; }
info() { echo -e " ${AZUL}ℹ${NC} $1"; }

progress_bar() {
    local current=$1 total=$2 msg="$3"
    [[ $current -gt $total ]] && current=$total
    local percent=$((current * 100 / total))
    local filled=$((current * 30 / total))
    local empty=$((30 - filled))
    local bar
    bar=$(printf "%${filled}s" | tr ' ' '#' ; printf "%${empty}s" | tr ' ' '.')
    echo -ne "\r ${AZUL}[${NC}${bar}${AZUL}]${NC} ${BRANCO}${percent}%${NC} ${msg}   "
    [[ $current -eq $total ]] && echo ""
}

error_exit() {
    fail_ "$1"
    echo -e "\n ${VERMELHO}Instalação interrompida. Log: $LOG_FILE${NC}"
    exit 1
}

pause() { echo ""; read -p " Pressione ENTER para voltar."; }

backup_file() {
    local file="$1"
    if [ -f "$file" ]; then
        mkdir -p "$BACKUP_DIR"
        cp "$file" "$BACKUP_DIR/$(basename "$file").bak"
        ok "Backup criado: $BACKUP_DIR/$(basename "$file").bak"
    fi
}

restore_backup() {
    local file="$1"
    local base="$(basename "$file")"
    local bak="$BACKUP_DIR/$base.bak"
    if [ -f "$bak" ]; then
        cp "$bak" "$file"
        ok "Restaurado: $file"
    else
        warn "Backup não encontrado: $bak"
    fi
}

# ─── Preparação do Sistema ──────────────────────────────────────────────────

prepare_system() {
    titulo "01" "PREPARANDO O SISTEMA"

    log "Iniciando preparação do sistema..."
    local total=8

    # ── 1/8: Repositório PHP ──
    progress_bar 1 $total "Repositórios PHP..."
    case "$ID" in
        ubuntu)
            if ! apt-cache show php8.3 &>/dev/null; then
                apt install -y software-properties-common > /dev/null 2>&1
                add-apt-repository -y ppa:ondrej/php > /dev/null 2>&1 || warn "Falha ao adicionar PPA ondrej/php"
            fi
            ;;
        debian)
            if ! apt-cache show php8.3 &>/dev/null; then
                apt install -y apt-transport-https lsb-release ca-certificates curl > /dev/null 2>&1
                curl -fsSL https://packages.sury.org/php/apt.gpg | gpg --dearmor -o /usr/share/keyrings/sury-php.gpg 2>/dev/null
                echo "deb [signed-by=/usr/share/keyrings/sury-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/sury-php.list
            fi
            ;;
    esac

    # ── 2/8: apt update ──
    progress_bar 2 $total "Atualizando lista de pacotes..."
    apt update -y >> "$LOG_FILE" 2>&1 || warn "Falha no apt update."

    # ── 3/8: apt upgrade ──
    progress_bar 3 $total "Atualizando pacotes instalados..."
    apt upgrade -y >> "$LOG_FILE" 2>&1 || warn "Falha no apt upgrade."

    # ── 4/8: Instalar dependências ──
    progress_bar 4 $total "Instalando Apache + PHP 8.3..."
    local deps=(
        apache2 mariadb-server certbot python3-certbot-apache
        curl wget unzip git openssl
        php8.3 php8.3-mysql php8.3-curl php8.3-zip php8.3-xml
        php8.3-mbstring php8.3-cli php8.3-common php8.3-fpm php-ssh2
        libapache2-mod-php8.3
    )
    DEBIAN_FRONTEND=noninteractive apt install -y "${deps[@]}" >> "$LOG_FILE" 2>&1
    if [ $? -ne 0 ]; then
        error_exit "Falha ao instalar dependências. Verifique $LOG_FILE"
    fi

    # ── 5/8: Módulos Apache ──
    progress_bar 5 $total "Ativando módulos do Apache..."
    a2enmod rewrite ssl proxy_fcgi setenvif headers > /dev/null 2>&1
    a2enconf php8.3-fpm > /dev/null 2>&1

    # ── 6/8: Habilitar serviços ──
    progress_bar 6 $total "Habilitando serviços..."
    systemctl enable apache2 mariadb php8.3-fpm > /dev/null 2>&1

    # ── 7/8: Reiniciar serviços ──
    progress_bar 7 $total "Reiniciando serviços..."
    systemctl restart apache2 mariadb php8.3-fpm > /dev/null 2>&1

    # ── 8/8: Firewall ──
    progress_bar 8 $total "Configurando firewall..."
    if command -v ufw &>/dev/null; then
        ufw allow 22/tcp > /dev/null 2>&1
        ufw allow 80/tcp > /dev/null 2>&1
        ufw allow 443/tcp > /dev/null 2>&1
        ufw --force enable > /dev/null 2>&1
    fi

    echo -e " ${VERDE}✔${NC} Sistema preparado com sucesso!"
    pause
}

# ─── Configurar Domínio e SSL ──────────────────────────────────────────────

setup_ssl() {
    titulo "02" "CONFIGURAR DOMÍNIO E SSL"

    load_config

    # ── Domínio ──
    if [ "$DOMINIO" != "Não configurado" ]; then
        info "Domínio atual: ${VERDE}$DOMINIO${NC}"
        read -p " Usar este? (ENTER = sim / digite novo): " input
        [ -n "$input" ] && DOMINIO="$input"
    else
        read -p " Digite o domínio (ex: atlas.seusite.com): " DOMINIO
    fi
    [ -z "$DOMINIO" ] && error_exit "Domínio não pode ser vazio."

    # ── Validar DNS ──
    subtitulo "Validando DNS..."
    local ip_vps=$(curl -s --max-time 5 ifconfig.me 2>/dev/null || hostname -I | awk '{print $1}')
    local ip_dominio=$(dig +short "$DOMINIO" 2>/dev/null | head -n1)
    local ip_dominio4=$(host "$DOMINIO" 2>/dev/null | grep "has address" | awk '{print $NF}' | head -n1)

    if [ -n "$ip_dominio" ] || [ -n "$ip_dominio4" ]; then
        local resolved="${ip_dominio:-$ip_dominio4}"
        if [ "$resolved" = "$ip_vps" ]; then
            ok "DNS OK: $DOMINIO → $ip_vps"
        else
            warn "$DOMINIO resolve para $resolved, não $ip_vps. O SSL pode falhar."
            read -p " Continuar mesmo assim? (s/N): " cont
            [ "$cont" != "s" ] && [ "$cont" != "S" ] && return
        fi
    else
        warn "Não foi possível resolver $DOMINIO. O Certbot exigirá --manual."
        read -p " Continuar? (s/N): " cont
        [ "$cont" != "s" ] && [ "$cont" != "S" ] && return
    fi

    # ── E-mail ──
    read -p " E-mail para o SSL: " EMAIL
    [ -z "$EMAIL" ] && EMAIL="admin@${DOMINIO}"
    ok "E-mail: $EMAIL"

    local total=4

    # ── Backup e configurar Apache ──
    progress_bar 1 $total "Configurando VirtualHost..."
    backup_file "/etc/apache2/sites-available/painel.conf"
    backup_file "/etc/apache2/sites-available/000-default.conf"

    a2enmod rewrite ssl > /dev/null 2>&1

    cat > /etc/apache2/sites-available/painel.conf <<VHOST
<VirtualHost *:80>
    ServerName $DOMINIO
    DocumentRoot /var/www/html
    <Directory /var/www/html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog \${APACHE_LOG_DIR}/painel_error.log
    CustomLog \${APACHE_LOG_DIR}/painel_access.log combined
</VirtualHost>
VHOST

    a2dissite 000-default.conf > /dev/null 2>&1
    a2ensite painel.conf > /dev/null 2>&1
    systemctl reload apache2 > /dev/null 2>&1
    ok "VirtualHost configurado para $DOMINIO"

    # ── SSL ──
    progress_bar 2 $total "Gerando SSL Let's Encrypt..."
    if certbot --apache -d "$DOMINIO" --non-interactive --agree-tos --email "$EMAIL" --redirect >> "$LOG_FILE" 2>&1; then
        ok "SSL ativo para $DOMINIO"
    else
        warn "Certbot falhou. Log: $LOG_FILE"
        warn "Continuando sem SSL. Use certbot manualmente depois."
    fi

    # ── Renovação automática ──
    progress_bar 3 $total "Configurando renovação automática..."
    if ! crontab -l 2>/dev/null | grep -q "certbot renew"; then
        (crontab -l 2>/dev/null; echo "0 3 * * * certbot renew --quiet >> $LOG_FILE 2>&1") | crontab -
        ok "Renovação SSL automática configurada (03:00)"
    fi

    # ── Salvar ──
    progress_bar 4 $total "Salvando configuração..."
    salvar_meta "DOMINIO" "$DOMINIO"
    salvar_meta "EMAIL" "$EMAIL"

    pause
}

# ─── Instalar Painel ───────────────────────────────────────────────────────

install_panel() {
    titulo "03" "INSTALAR PAINEL ATLAS"

    load_config
    local total=10

    # ── Clonar repositório ──
    progress_bar 1 $total "Baixando painel do GitHub..."
    local tmp_dir="/tmp/atlas_painel_$$"
    rm -rf "$tmp_dir"
    if ! git clone --depth 1 https://github.com/CoutySSH/Atlas-Painel-CTSSH "$tmp_dir" >> "$LOG_FILE" 2>&1; then
        error_exit "Falha ao clonar repositório. Verifique conexão com GitHub."
    fi
    ok "Repositório clonado"

    # ── Localizar pasta do painel ──
    local origem=""
    for d in "Atlas CTSSH" "Atlas" "Atlas Sem Key"; do
        [ -d "$tmp_dir/$d" ] && { origem="$tmp_dir/$d"; break; }
    done
    if [ -z "$origem" ]; then
        error_exit "Pasta do painel não encontrada no repositório."
    fi
    ok "Pasta encontrada: $d"

    # ── Fazer backup do html existente ──
    progress_bar 2 $total "Backup do diretório atual..."
    if [ -d "/var/www/html" ] && [ "$(ls -A /var/www/html 2>/dev/null)" ]; then
        backup_file "/var/www/html/atlas/conexao.php"
        local bk_html="${BACKUP_DIR}/html_backup.tar.gz"
        tar -czf "$bk_html" -C /var/www html 2>/dev/null && ok "Backup: $bk_html" || warn "Falha ao criar backup"
    fi

    # ── Copiar arquivos ──
    progress_bar 3 $total "Copiando arquivos..."
    rm -rf /var/www/html/*
    cp -a "$origem"/. /var/www/html/
    [ -f "$tmp_dir/banco.sql" ] && cp "$tmp_dir/banco.sql" /var/www/html/banco.sql
    rm -f /var/www/html/index.html 2>/dev/null
    ok "Arquivos copiados"

    # ── Configurar banco ──
    progress_bar 4 $total "Configurando banco de dados..."

    [ -z "$DB_PASS" ] && DB_PASS=$(openssl rand -hex 12)

    if [ -f "$CONEXAO_PATH" ]; then
        local v
        v=$(get_php_var "dbname" "$CONEXAO_PATH"); [ -n "$v" ] && DB_NAME="$v"
        v=$(get_php_var "dbuser" "$CONEXAO_PATH"); [ -n "$v" ] && DB_USER="$v"
        v=$(get_php_var "dbpass" "$CONEXAO_PATH"); [ -n "$v" ] && DB_PASS="$v"
        v=$(get_php_var "dbhost" "$CONEXAO_PATH"); [ -n "$v" ] && DB_HOST="$v"
    fi

    [ -z "$DB_NAME" ] && DB_NAME="gestorssh"
    [ -z "$DB_USER" ] && DB_USER="gestorssh"
    [ -z "$DB_HOST" ] && DB_HOST="localhost"

    if ! systemctl is-active --quiet mariadb; then
        systemctl start mariadb >> "$LOG_FILE" 2>&1 || error_exit "MariaDB não está rodando"
    fi

    mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;" >> "$LOG_FILE" 2>&1
    mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';" >> "$LOG_FILE" 2>&1
    mysql -e "ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';" >> "$LOG_FILE" 2>&1
    mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';" >> "$LOG_FILE" 2>&1
    mysql -e "FLUSH PRIVILEGES;" >> "$LOG_FILE" 2>&1

    if mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -e "SELECT 1;" > /dev/null 2>&1; then
        ok "Banco OK: ${DB_USER}@${DB_HOST}/${DB_NAME}"
    else
        error_exit "Falha ao configurar banco. Verifique $LOG_FILE"
    fi

    salvar_meta "DB_NAME" "$DB_NAME"
    salvar_meta "DB_USER" "$DB_USER"
    salvar_meta "DB_PASS" "$DB_PASS"
    salvar_meta "DB_HOST" "$DB_HOST"
    ok "Credenciais salvas em $META_FILE (acesso root)"

    # ── Escrever conexao.php ──
    progress_bar 5 $total "Escrevendo conexao.php..."
    mkdir -p /var/www/html/atlas
    cat > "$CONEXAO_PATH" <<PHP_CONN
<?php
 \$dbname = '${DB_NAME}';
 \$dbuser = '${DB_USER}';
 \$dbpass = '${DB_PASS}';
 \$dbhost = '${DB_HOST}';
 \$_SESSION['token'] = '${SESSION_TOKEN:-9P9trMXJP9w5Wv7}';
?>
PHP_CONN
    ok "conexao.php criado"

    # ── Importar SQL ──
    progress_bar 6 $total "Importando SQL..."
    if [ -f /var/www/html/banco.sql ]; then
        mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" < /var/www/html/banco.sql >> "$LOG_FILE" 2>&1
        ok "SQL importado"
    else
        warn "banco.sql não encontrado, pulando importação"
    fi

    # ── Senha admin ──
    local admin_senha=$(openssl rand -base64 10 | tr -dc 'A-Za-z0-9' | head -c 12)
    local admin_hash=$(echo -n "$admin_senha" | md5sum | awk '{print $1}')
    mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" \
        -e "UPDATE accounts SET senha = '$admin_hash' WHERE login = 'admin' LIMIT 1;" 2>/dev/null
    salvar_meta "PAINEL_SENHA" "$admin_senha"
    ok "Senha do admin definida"

    # ── Permissões ──
    progress_bar 7 $total "Ajustando permissões..."
    chown -R www-data:www-data /var/www/html/
    find /var/www/html -type d -exec chmod 755 {} \;
    find /var/www/html -type f -exec chmod 644 {} \;
    ok "Permissões ajustadas"

    # ── Proteger atlas ──
    progress_bar 8 $total "Protegendo diretório atlas..."
    [ ! -f /var/www/html/atlas/.htaccess ] && cat > /var/www/html/atlas/.htaccess <<'HTACCESS'
<FilesMatch "\.(php|inc|sql)$">
    Require all denied
</FilesMatch>
HTACCESS
    ok "Atlas protegido via .htaccess"

    # ── Limpeza ──
    progress_bar 9 $total "Limpando arquivos temporários..."
    rm -rf "$tmp_dir"
    rm -f /var/www/html/install.sh /var/www/html/install01.sh /var/www/html/README.md /var/www/html/security-audit-atlas-sem-key.md /var/www/html/telegram-bots-functions.md
    ok "Arquivos temporários removidos"

    # ── Cron ──
    progress_bar 10 $total "Configurando cron..."
    if command -v crontab &>/dev/null; then
        (crontab -l 2>/dev/null | grep -v "cron_exec.php\|onlines.php\|checkpag.php") | crontab -
        (crontab -l 2>/dev/null; echo "* * * * * cd /var/www/html && php cron_exec.php >/dev/null 2>&1") | crontab -
        mysql -e "ALTER TABLE configs ADD COLUMN IF NOT EXISTS cron_ativo INT(1) NOT NULL DEFAULT 1;" 2>/dev/null
        mysql -e "UPDATE configs SET cron_ativo = 1 WHERE id = 1;" 2>/dev/null
        ok "Cron configurado (1 min)"
    fi

    # ── Resumo ──
    echo -e ""
    echo -e "${BG_VERDE:-${VERDE}}${BRANCO}${NEGRITO}╔═══════════════════════════════════════════════════════╗${NC}"
    echo -e "${BG_VERDE:-${VERDE}}${BRANCO}${NEGRITO}║        PAINEL INSTALADO COM SUCESSO!                   ║${NC}"
    echo -e "${BG_VERDE:-${VERDE}}${BRANCO}${NEGRITO}║  ${BRANCO}Admin: admin  /  $admin_senha${NC}             ${BG_VERDE:-${VERDE}}${BRANCO}${NEGRITO}║${NC}"
    echo -e "${BG_VERDE:-${VERDE}}${BRANCO}${NEGRITO}╚═══════════════════════════════════════════════════════╝${NC}"
    echo -e ""
    pause
}

# ─── Utilitários ───────────────────────────────────────────────────────────

reparar_banco() {
    titulo "04" "REPARAR BANCO DE DADOS"

    load_config
    local total=4

    if [ -z "$DB_PASS" ]; then
        DB_PASS=$(openssl rand -hex 12)
        info "Nova senha gerada automaticamente."
    fi

    progress_bar 1 $total "Recriando conexao.php e protegendo atlas..."
    backup_file "$CONEXAO_PATH"

    # Recriar conexao.php
    mkdir -p /var/www/html/atlas
    cat > "$CONEXAO_PATH" <<PHP_CONN
<?php
 \$dbname = '${DB_NAME:-gestorssh}';
 \$dbuser = '${DB_USER:-gestorssh}';
 \$dbpass = '${DB_PASS}';
 \$dbhost = '${DB_HOST:-localhost}';
 \$_SESSION['token'] = '${SESSION_TOKEN:-9P9trMXJP9w5Wv7}';
?>
PHP_CONN
    ok "conexao.php recriado"

    # Proteger diretório atlas via .htaccess
    cat > /var/www/html/atlas/.htaccess <<'HTACCESS'
<FilesMatch "\.(php|inc|sql)$">
    Require all denied
</FilesMatch>
HTACCESS
    ok "Atlas protegido via .htaccess"

    progress_bar 2 $total "Configurando banco de dados..."
    mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME:-gestorssh}\`;" >> "$LOG_FILE" 2>&1
    mysql -e "ALTER USER '${DB_USER:-gestorssh}'@'localhost' IDENTIFIED BY '${DB_PASS}';" >> "$LOG_FILE" 2>&1
    mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME:-gestorssh}\`.* TO '${DB_USER:-gestorssh}'@'localhost';" >> "$LOG_FILE" 2>&1
    mysql -e "FLUSH PRIVILEGES;" >> "$LOG_FILE" 2>&1

    progress_bar 3 $total "Verificando conexão..."
    if mysql -u"${DB_USER:-gestorssh}" -p"$DB_PASS" -h"${DB_HOST:-localhost}" "${DB_NAME:-gestorssh}" -e "SELECT 1;" > /dev/null 2>&1; then
        ok "Conexão com banco verificada com sucesso!"
    else
        fail_ "Falha na conexão. Verifique credenciais."
    fi

    progress_bar 4 $total "Salvando credenciais..."
    salvar_meta "DB_NAME" "${DB_NAME:-gestorssh}"
    salvar_meta "DB_USER" "${DB_USER:-gestorssh}"
    salvar_meta "DB_PASS" "${DB_PASS}"
    salvar_meta "DB_HOST" "${DB_HOST:-localhost}"
    ok "Credenciais salvas em $META_FILE (acesso root)"

    pause
}

reset_admin() {
    titulo "05" "RESETAR SENHA DO ADMIN"

    load_config

    if [ -z "$DB_PASS" ]; then
        fail_ "Banco não configurado. Instale o painel primeiro."
        pause
        return
    fi

    local total=3

    progress_bar 1 $total "Gerando nova senha..."
    local nova_senha=$(openssl rand -base64 10 | tr -dc 'A-Za-z0-9' | head -c 12)
    local hash=$(echo -n "$nova_senha" | md5sum | awk '{print $1}')

    progress_bar 2 $total "Atualizando banco de dados..."
    mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" \
        -e "UPDATE accounts SET senha = '$hash' WHERE login = 'admin' LIMIT 1;" 2>/dev/null

    progress_bar 3 $total "Salvando configuração..."
    if [ $? -eq 0 ]; then
        salvar_meta "PAINEL_SENHA" "$nova_senha"
        echo -e ""
        ok "Senha do admin redefinida!"
        echo -e "   ${BRANCO}Login:${NC} admin"
        echo -e "   ${BRANCO}Senha:${NC} ${VERDE}${nova_senha}${NC}"
        echo -e ""
    else
        fail_ "Falha ao alterar senha. Verifique o banco."
    fi
    pause
}

desinstalar() {
    titulo "06" "DESINSTALAR COMPLETO"

    echo -e ""
    warn "${VERMELHO}Isso REMOVERÁ todo o painel, site, e configurações!${NC}"
    warn "Backup será criado em: $BACKUP_DIR"
    echo -e ""
    read -p " Digite CONFIRMAR para prosseguir: " confirm
    [ "$confirm" != "CONFIRMAR" ] && { info "Cancelado."; pause; return; }

    load_config
    local total=6

    progress_bar 1 $total "Backup do banco de dados..."
    if mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -e "SELECT 1;" > /dev/null 2>&1; then
        local dump="${BACKUP_DIR}/banco_dump.sql"
        mkdir -p "$BACKUP_DIR"
        mysqldump -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" > "$dump" 2>/dev/null
        ok "Backup do banco: $dump"
    fi

    progress_bar 2 $total "Removendo banco de dados..."
    mysql -e "DROP DATABASE IF EXISTS \`${DB_NAME}\`;" 2>/dev/null
    mysql -e "DROP USER IF EXISTS '${DB_USER}'@'localhost';" 2>/dev/null
    mysql -e "FLUSH PRIVILEGES;" 2>/dev/null
    ok "Banco de dados removido"

    progress_bar 3 $total "Parando serviços..."
    systemctl stop apache2 mariadb php8.3-fpm 2>/dev/null
    systemctl disable apache2 mariadb php8.3-fpm 2>/dev/null

    progress_bar 4 $total "Removendo pacotes..."
    apt remove --purge -y apache2 mariadb-server php8.3* certbot python3-certbot-apache 2>/dev/null
    apt autoremove --purge -y 2>/dev/null

    progress_bar 5 $total "Removendo diretórios..."
    rm -rf /var/www/html 2>/dev/null
    rm -rf /etc/apache2 2>/dev/null
    rm -rf /etc/letsencrypt 2>/dev/null
    rm -f /root/.atlas_meta 2>/dev/null
    rm -f /var/log/atlas_install01.log 2>/dev/null

    progress_bar 6 $total "Removendo crons..."
    crontab -r 2>/dev/null

    ok "Desinstalação concluída. Sistema limpo para reinstalação."
    pause
}

status_sistema() {
    titulo "07" "STATUS DO SISTEMA"

    load_config

    linha "${CIANO}"
    echo -e " ${CIANO}▸ Serviços${NC}"
    for svc in apache2 mariadb php8.3-fpm; do
        if systemctl is-active --quiet "$svc" 2>/dev/null; then
            echo -e "   $svc : ${VERDE}ATIVO${NC}"
        else
            echo -e "   $svc : ${VERMELHO}INATIVO${NC}"
        fi
    done

    echo -e ""
    echo -e " ${CIANO}▸ Domínio${NC}"
    echo -e "   ${DOMINIO}"

    echo -e ""
    echo -e " ${CIANO}▸ SSL${NC}"
    if [ "$DOMINIO" != "Não configurado" ] && [ -d "/etc/letsencrypt/live/$DOMINIO" ]; then
        local pem="/etc/letsencrypt/live/$DOMINIO/fullchain.pem"
        if [ -f "$pem" ]; then
            local exp=$(openssl x509 -enddate -noout -in "$pem" 2>/dev/null | cut -d= -f2)
            echo -e "   Válido até: ${VERDE}$exp${NC}"
        fi
    else
        echo -e "   ${AMARELO}Não configurado${NC}"
    fi

    echo -e ""
    echo -e " ${CIANO}▸ Banco${NC}"
    if mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -e "SELECT 1;" > /dev/null 2>&1; then
        echo -e "   ${VERDE}Conectado${NC}"
    else
        echo -e "   ${VERMELHO}Desconectado${NC}"
    fi

    echo -e ""
    echo -e " ${CIANO}▸ Crons${NC}"
    if crontab -l 2>/dev/null | grep -q "cron_exec.php"; then
        echo -e "   ${VERDE}Instaladas${NC}"
    else
        echo -e "   ${AMARELO}Não instaladas${NC}"
    fi

    echo -e ""
    echo -e " ${CIANO}▸ Firewall${NC}"
    if command -v ufw &>/dev/null; then
        ufw status | head -5 2>/dev/null
    else
        echo -e "   ${AMARELO}UFW não encontrado${NC}"
    fi

    linha "${CIANO}"
    pause
}

# ─── Helpers (carregar/salvar config) ──────────────────────────────────────

get_php_var() {
    local var_name="$1"; local file="$2"
    if [ -f "$file" ]; then
        grep -E "\$$var_name\s*=" "$file" | head -n1 | sed "s/.*'\(.*\)'.*/\1/" | tr -d " ;\r\n"
    fi
}

load_config() {
    DOMINIO="Não configurado"; EMAIL="Não configurado"
    PAINEL_SENHA=""; DB_NAME="gestorssh"; DB_USER="gestorssh"
    DB_PASS=""; DB_HOST="localhost"; SSL_STATUS="DESATIVADO"
    SESSION_TOKEN="9P9trMXJP9w5Wv7"

    if [ -f "$CONEXAO_PATH" ]; then
        local v
        v=$(get_php_var "dbname" "$CONEXAO_PATH"); [ -n "$v" ] && DB_NAME="$v"
        v=$(get_php_var "dbuser" "$CONEXAO_PATH"); [ -n "$v" ] && DB_USER="$v"
        v=$(get_php_var "dbpass" "$CONEXAO_PATH"); [ -n "$v" ] && DB_PASS="$v"
        v=$(get_php_var "dbhost" "$CONEXAO_PATH"); [ -n "$v" ] && DB_HOST="$v"
    fi

    [ -f "$META_FILE" ] && source "$META_FILE"
}

salvar_meta() {
    local chave="$1"; local valor="$2"
    if grep -q "^${chave}=" "$META_FILE" 2>/dev/null; then
        sed -i "s|^${chave}=.*|${chave}=\"${valor}\"|" "$META_FILE"
    else
        echo "${chave}=\"${valor}\"" >> "$META_FILE"
    fi
}

# ─── Menu Principal ─────────────────────────────────────────────────────────

show_menu() {
    clear
    load_config

    echo -e ""
    echo -e "${AZUL}+----------------------------------------------------------------+${NC}"
    echo -e "${AZUL}|${NC}           ${BRANCO}Feito por ${VERMELHO}@Couty_SSH${NC} ${BRANCO}-${NC} ${CIANO}coutyssh.com${NC}            ${AZUL}|${NC}"
    echo -e "${AZUL}+----------------------------------------------------------------+${NC}"
    echo -e "${AZUL}|${NC}  ${BRANCO}${NEGRITO}ATLAS WEB 2026 - INSTALADOR OTIMIZADO v${SCRIPT_VER}${NC}        ${AZUL}|${NC}"
    echo -e "${AZUL}|${NC}  ${CIANO}Apache  MariaDB  PHP  SSL  Dominio  Logs${NC}   ${AZUL}|${NC}"
    echo -e "${AZUL}+----------------------------------------------------------------+${NC}"
    echo -e "${AZUL}|${NC}  Dominio: ${VERDE}$DOMINIO${NC}"
    local apache_icon mariadb_icon php_icon ssl_icon
    systemctl is-active --quiet apache2  2>/dev/null && apache_icon="${VERDE}ATIVO${NC}"  || apache_icon="${VERMELHO}INATIVO${NC}"
    systemctl is-active --quiet mariadb  2>/dev/null && mariadb_icon="${VERDE}ATIVO${NC}" || mariadb_icon="${VERMELHO}INATIVO${NC}"
    local php_ok=0
    systemctl is-active --quiet php8.3-fpm 2>/dev/null && php_ok=1
    apachectl -M 2>/dev/null | grep -q php_module && php_ok=1
    [ "$php_ok" -eq 1 ] && php_icon="${VERDE}ATIVO${NC}" || php_icon="${VERMELHO}INATIVO${NC}"
    if [ "$DOMINIO" != "Nao configurado" ] && [ -d "/etc/letsencrypt/live/$DOMINIO" ]; then
        ssl_icon="${VERDE}ATIVO${NC}"
    else
        ssl_icon="${AMARELO}INATIVO${NC}"
    fi
    echo -e "${AZUL}|${NC}  Apache ${apache_icon}  |  MariaDB ${mariadb_icon}  |  PHP ${php_icon}"
    echo -e "${AZUL}|${NC}  SSL ${ssl_icon}${NC}"
    echo -e "${AZUL}+----------------------------------------------------------------+${NC}"

    local opcoes=(
        "01:Preparar Sistema (repos, deps, firewall)"
        "02:Configurar Dominio e SSL (com validacao DNS)"
        "03:Instalar Painel Atlas (GitHub + BD + permissoes)"
        "04:Reparar Banco de Dados"
        "05:Resetar Senha do Admin"
        "06:Desinstalar Completo"
        "07:Status do Sistema"
        "00:Sair"
    )
    for item in "${opcoes[@]}"; do
        local num="${item%%:*}"
        local desc="${item#*:}"
        echo -e "${AZUL}|${NC}     ${VERMELHO}[$num]${NC}  ${AMARELO}$desc${NC}"
    done
    echo -e "${AZUL}+----------------------------------------------------------------+${NC}"
    echo -e ""
}

# ─── Loop Principal ─────────────────────────────────────────────────────────

while true; do
    show_menu
    echo -e -n " ${BRANCO}Selecione uma opção:${NC} "; read OPCAO
    case $OPCAO in
        1|01)             prepare_system  ;;
        2|02)             setup_ssl       ;;
        3|03)             install_panel   ;;
        4|04)             reparar_banco   ;;
        5|05)             reset_admin     ;;
        6|06)             desinstalar     ;;
         7|07)             status_sistema  ;;
        0|00)
            echo -e "\n${VERDE} Saindo...${NC}\n"
            exit 0
            ;;
        *)
            echo -e "\n${VERMELHO} Opção inválida!${NC}"
            sleep 2
            ;;
    esac
done
