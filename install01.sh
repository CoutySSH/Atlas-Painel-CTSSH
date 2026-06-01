
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

    # ── Repositório PHP (Ondřej Surý) apenas se necessário ──
    subtitulo "[1/4] Configurando repositórios..."
    case "$ID" in
        ubuntu)
            if ! apt-cache show php8.3 &>/dev/null; then
                info "Adicionando repositório ondrej/php..."
                apt install -y software-properties-common > /dev/null 2>&1
                add-apt-repository -y ppa:ondrej/php > /dev/null 2>&1 || warn "Falha ao adicionar PPA ondrej/php"
            fi
            ;;
        debian)
            if ! apt-cache show php8.3 &>/dev/null; then
                info "Adicionando repositório sury/php..."
                apt install -y apt-transport-https lsb-release ca-certificates curl > /dev/null 2>&1
                curl -fsSL https://packages.sury.org/php/apt.gpg | gpg --dearmor -o /usr/share/keyrings/sury-php.gpg 2>/dev/null
                echo "deb [signed-by=/usr/share/keyrings/sury-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/sury-php.list
            fi
            ;;
    esac

    # ── Atualizar pacotes ──
    subtitulo "[2/4] Atualizando pacotes..."
    apt update -y >> "$LOG_FILE" 2>&1 || warn "Falha no apt update. Verifique rede/repositórios."
    apt upgrade -y >> "$LOG_FILE" 2>&1 || warn "Falha no apt upgrade."
    ok "Pacotes atualizados"

    # ── Instalar dependências ──
    subtitulo "[3/4] Instalando dependências..."
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
    ok "Dependências instaladas"

    # ── Configurar Apache com PHP-FPM (mais seguro/performático) ──
    subtitulo "[4/4] Otimizando serviços..."
    a2enmod rewrite ssl proxy_fcgi setenvif headers > /dev/null 2>&1
    a2enconf php8.3-fpm > /dev/null 2>&1

    systemctl enable apache2 mariadb php8.3-fpm > /dev/null 2>&1
    systemctl restart apache2 mariadb php8.3-fpm > /dev/null 2>&1
    ok "Apache + PHP-FPM + MariaDB ativos"

    # ── Firewall ──
    if command -v ufw &>/dev/null; then
        ufw allow 22/tcp > /dev/null 2>&1
        ufw allow 80/tcp > /dev/null 2>&1
        ufw allow 443/tcp > /dev/null 2>&1
        ufw --force enable > /dev/null 2>&1
        ok "Firewall configurado (22, 80, 443)"
    fi

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

    # ── Backup e configurar Apache ──
    subtitulo "Configurando VirtualHost..."
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
    subtitulo "Gerando SSL Let's Encrypt..."
    if certbot --apache -d "$DOMINIO" --non-interactive --agree-tos --email "$EMAIL" --redirect >> "$LOG_FILE" 2>&1; then
        ok "SSL ativo para $DOMINIO"
    else
        warn "Certbot falhou. Log: $LOG_FILE"
        warn "Continuando sem SSL. Use certbot manualmente depois."
    fi

    # ── Renovação automática ──
    if ! crontab -l 2>/dev/null | grep -q "certbot renew"; then
        (crontab -l 2>/dev/null; echo "0 3 * * * certbot renew --quiet >> $LOG_FILE 2>&1") | crontab -
        ok "Renovação SSL automática configurada (03:00)"
    fi

    # ── Salvar ──
    salvar_meta "DOMINIO" "$DOMINIO"
    salvar_meta "EMAIL" "$EMAIL"

    pause
}

# ─── Instalar Painel ───────────────────────────────────────────────────────

install_panel() {
    titulo "03" "INSTALAR PAINEL ATLAS"

    load_config

    # ── Clonar repositório ──
    subtitulo "[1/7] Baixando painel do GitHub..."
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
    subtitulo "[2/7] Backup do diretório atual..."
    if [ -d "/var/www/html" ] && [ "$(ls -A /var/www/html 2>/dev/null)" ]; then
        backup_file "/var/www/html/atlas/conexao.php"
        local bk_html="${BACKUP_DIR}/html_backup.tar.gz"
        tar -czf "$bk_html" -C /var/www html 2>/dev/null && ok "Backup: $bk_html" || warn "Falha ao criar backup"
    fi

    # ── Copiar arquivos ──
    subtitulo "[3/7] Copiando arquivos..."
    rm -rf /var/www/html/*
    cp -a "$origem"/. /var/www/html/
    [ -f "$tmp_dir/banco.sql" ] && cp "$tmp_dir/banco.sql" /var/www/html/banco.sql
    rm -f /var/www/html/index.html 2>/dev/null
    ok "Arquivos copiados"

    # ── Configurar banco ──
    subtitulo "[4/7] Configurando banco de dados..."

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

    # ── Importar SQL ──
    subtitulo "[5/7] Importando SQL..."

    # ── Escrever conexao.php ──

    # ── Senha admin ──

    # ── Permissões ──
    subtitulo "[6/7] Ajustando permissões..."

    # ── Limpeza ──
    subtitulo "[7/7] Limpando arquivos temporários..."
    rm -rf "$tmp_dir"
    rm -f /var/www/html/install.sh /var/www/html/install01.sh /var/www/html/README.md /var/www/html/security-audit-atlas-sem-key.md /var/www/html/telegram-bots-functions.md
    ok "Arquivos temporários removidos"

    # ── Cron ──
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
    echo -e "${BG_VERDE:-${VERDE}}${BRANCO}${NEGRITO}╚═══════════════════════════════════════════════════════╝${NC}"
    echo -e ""
    pause
}

# ─── Utilitários ───────────────────────────────────────────────────────────

reparar_banco() {
    titulo "04" "REPARAR BANCO DE DADOS"

    load_config

    if [ -z "$DB_PASS" ]; then
        warn "Senha do banco não encontrada automaticamente."
        read -p " Digite a senha do banco: " DB_PASS
        [ -z "$DB_PASS" ] && error_exit "Senha obrigatória"
    fi

    # Backup do conexao.php
    backup_file "$CONEXAO_PATH"

    # Recriar
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

    mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME:-gestorssh}\`;" >> "$LOG_FILE" 2>&1
    mysql -e "ALTER USER '${DB_USER:-gestorssh}'@'localhost' IDENTIFIED BY '${DB_PASS}';" >> "$LOG_FILE" 2>&1
    mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME:-gestorssh}\`.* TO '${DB_USER:-gestorssh}'@'localhost';" >> "$LOG_FILE" 2>&1
    mysql -e "FLUSH PRIVILEGES;" >> "$LOG_FILE" 2>&1

    if mysql -u"${DB_USER:-gestorssh}" -p"$DB_PASS" -h"${DB_HOST:-localhost}" "${DB_NAME:-gestorssh}" -e "SELECT 1;" > /dev/null 2>&1; then
        ok "Conexão com banco verificada com sucesso!"
    else
        fail_ "Falha na conexão. Verifique credenciais."
    fi
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

    local nova_senha=$(openssl rand -base64 10 | tr -dc 'A-Za-z0-9' | head -c 12)
    local hash=$(echo -n "$nova_senha" | md5sum | awk '{print $1}')

    mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" \
        -e "UPDATE accounts SET senha = '$hash' WHERE login = 'admin' LIMIT 1;" 2>/dev/null

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

    # Parar serviços
    systemctl stop apache2 mariadb php8.3-fpm 2>/dev/null
    systemctl disable apache2 mariadb php8.3-fpm 2>/dev/null

    # Remover pacotes
    apt remove --purge -y apache2 mariadb-server php8.3* certbot python3-certbot-apache 2>/dev/null
    apt autoremove --purge -y 2>/dev/null

    # Remover diretórios
    rm -rf /var/www/html 2>/dev/null
    rm -rf /etc/apache2 2>/dev/null
    rm -f /etc/letsencrypt 2>/dev/null

    # Remover crons
    crontab -r 2>/dev/null

    ok "Desinstalação concluída."
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

    [ -f "$META_FILE" ] && source "$META_FILE"

    if [ -f "$CONEXAO_PATH" ]; then
        local v
        v=$(get_php_var "dbname" "$CONEXAO_PATH"); [ -n "$v" ] && DB_NAME="$v"
        v=$(get_php_var "dbuser" "$CONEXAO_PATH"); [ -n "$v" ] && DB_USER="$v"
        v=$(get_php_var "dbpass" "$CONEXAO_PATH"); [ -n "$v" ] && DB_PASS="$v"
        v=$(get_php_var "dbhost" "$CONEXAO_PATH"); [ -n "$v" ] && DB_HOST="$v"
    fi
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
