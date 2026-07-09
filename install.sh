#!/bin/bash

# ===========================================================================
#  install.sh - Atlas Web 2026 - Instalador Otimizado
# ===========================================================================
#  Requer: Ubuntu 20.04+ / Debian 11+ | RAM >= 512MB | Disco >= 2GB livre
#  Modo de uso:
#    chmod +x install01.sh
#    sudo ./install01.sh
# ===========================================================================

# ─── Cores ──────────────────────────────────────────────────────────────────
VERDE='\033[0;32m'; AZUL='\033[0;34m'; AMARELO='\033[1;33m'
VERMELHO='\033[0;31m'; CIANO='\033[0;36m'; BRANCO='\033[1;37m'
NEGRITO='\033[1m'; NC='\033[0m'; BG_VERDE='\033[42m'

# ─── Config Globais ─────────────────────────────────────────────────────────
LOG_FILE="/var/log/atlas_install01.log"
META_FILE="/root/.atlas_meta"
CONEXAO_PATH="/var/www/html/atlas/conexao.php"
BACKUP_DIR="/root/backups/atlas_$(date +%Y%m%d_%H%M%S)"
REQUIRED_DISK_MB=2048
REQUIRED_RAM_MB=512
SCRIPT_VER="1.1"
PHP_VER="8.1"

# ─── Detectar SO ─────────────────────────────────────────────────────────────
if [ -f /etc/os-release ]; then
    . /etc/os-release
elif [ -f /usr/lib/os-release ]; then
    . /usr/lib/os-release
fi

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

# ─── Checagem de Pré-requisitos ─────────────────────────────────────────────

check_requirements() {
    local ram_mb disk_mb
    ram_mb=$(free -m 2>/dev/null | awk '/^Mem:/ {print $2}')
    disk_mb=$(df -m / 2>/dev/null | awk 'NR==2 {print $4}')
    if [ -n "$ram_mb" ] && [ "$ram_mb" -lt "$REQUIRED_RAM_MB" ]; then
        warn "RAM detectada: ${ram_mb}MB (mínimo recomendado: ${REQUIRED_RAM_MB}MB)"
    fi
    if [ -n "$disk_mb" ] && [ "$disk_mb" -lt "$REQUIRED_DISK_MB" ]; then
        warn "Disco livre: ${disk_mb}MB (mínimo recomendado: ${REQUIRED_DISK_MB}MB)"
    fi
}

# ─── Preparação do Sistema ──────────────────────────────────────────────────

prepare_system() {
    titulo "01" "PREPARANDO O SISTEMA"

    check_requirements
    log "Iniciando preparação do sistema..."
    local total=8

    # ── 1/8: Repositório PHP ──
    progress_bar 1 $total "Repositórios PHP..."
    if ! apt-cache show php${PHP_VER} &>/dev/null; then
        local php_repo_added=false
        if [ "$ID" = "ubuntu" ]; then
            # Tenta via add-apt-repository
            if ! command -v add-apt-repository &>/dev/null; then
                apt install -y software-properties-common >> "$LOG_FILE" 2>&1
            fi
            if command -v add-apt-repository &>/dev/null; then
                add-apt-repository -y ppa:ondrej/php >> "$LOG_FILE" 2>&1 && php_repo_added=true
            fi
            # Fallback manual se add-apt-repository não funcionou
            if [ "$php_repo_added" = false ]; then
                info "Adicionando PPA ondrej/php manualmente..."
                local php_repo_keyring="/usr/share/keyrings/ondrej-php.gpg"
                apt install -y gpg ca-certificates >> "$LOG_FILE" 2>&1
                gpg --keyserver keyserver.ubuntu.com --recv-keys 14AA40EC0831756756D7F66C4F4EA0AAE5267A6C >> "$LOG_FILE" 2>&1 || \
                    curl -fsSL "https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x4F4EA0AAE5267A6C" 2>/dev/null | gpg --dearmor -o "$php_repo_keyring"
                gpg --export 14AA40EC0831756756D7F66C4F4EA0AAE5267A6C > "$php_repo_keyring" 2>/dev/null || true
                echo "deb [signed-by=$php_repo_keyring] http://ppa.launchpadcontent.net/ondrej/php/ubuntu $(lsb_release -sc) main" > /etc/apt/sources.list.d/ondrej-php.list
                php_repo_added=true
            fi
        elif [ "$ID" = "debian" ]; then
            apt install -y apt-transport-https lsb-release ca-certificates curl >> "$LOG_FILE" 2>&1
            curl -fsSL https://packages.sury.org/php/apt.gpg | gpg --dearmor -o /usr/share/keyrings/sury-php.gpg >> "$LOG_FILE" 2>&1
            echo "deb [signed-by=/usr/share/keyrings/sury-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/sury-php.list
            php_repo_added=true
        fi
        if [ "$php_repo_added" = true ]; then
            ok "Repositório PHP configurado"
        else
            warn "Não foi possível configurar repositório PHP. A instalação pode falhar."
        fi
    fi

    # ── 2/8: apt update ──
    progress_bar 2 $total "Atualizando lista de pacotes..."
    apt update -y >> "$LOG_FILE" 2>&1 || warn "Falha no apt update."

    # ── 3/8: apt upgrade ──
    progress_bar 3 $total "Atualizando pacotes instalados..."
    apt upgrade -y >> "$LOG_FILE" 2>&1 || warn "Falha no apt upgrade."

    # ── 4/8: Instalar dependências ──
    progress_bar 4 $total "Instalando Apache + PHP ${PHP_VER}..."
    local deps=(
        apache2 mariadb-server certbot python3-certbot-apache
        curl wget unzip git openssl cron sudo
        php${PHP_VER} php${PHP_VER}-mysql php${PHP_VER}-curl php${PHP_VER}-zip php${PHP_VER}-xml
        php${PHP_VER}-mbstring php${PHP_VER}-cli php${PHP_VER}-common php${PHP_VER}-fpm php${PHP_VER}-ssh2
    )
    DEBIAN_FRONTEND=noninteractive apt install -y "${deps[@]}" >> "$LOG_FILE" 2>&1
    if [ $? -ne 0 ]; then
        error_exit "Falha ao instalar dependências. Verifique $LOG_FILE"
    fi

    # ── 5/8: Módulos Apache ──
    progress_bar 5 $total "Ativando módulos do Apache..."
    a2dismod -f mpm_prefork > /dev/null 2>&1 || true
    a2enmod rewrite ssl proxy_fcgi setenvif headers mpm_event > /dev/null 2>&1
    a2enconf php${PHP_VER}-fpm > /dev/null 2>&1

    # ── 6/8: Habilitar serviços ──
    progress_bar 6 $total "Habilitando serviços..."
    systemctl enable apache2 mariadb php${PHP_VER}-fpm cron > /dev/null 2>&1

    # ── 7/8: Reiniciar serviços ──
    progress_bar 7 $total "Reiniciando serviços..."
    systemctl restart apache2 mariadb php${PHP_VER}-fpm cron > /dev/null 2>&1
    sleep 2

    # ── 8/8: Firewall ──
    progress_bar 8 $total "Configurando firewall..."
    if command -v ufw &>/dev/null; then
        ufw allow 22/tcp >> "$LOG_FILE" 2>&1
        ufw allow 80/tcp >> "$LOG_FILE" 2>&1
        ufw allow 443/tcp >> "$LOG_FILE" 2>&1
        ufw --force enable >> "$LOG_FILE" 2>&1
    fi
    # Fallback: liberar portas via iptables se estiverem bloqueadas
    if ! iptables -C INPUT -p tcp --dport 80 -m state --state NEW -j ACCEPT 2>/dev/null; then
        apt install -y iptables-persistent >> "$LOG_FILE" 2>&1
        iptables -I INPUT 5 -p tcp --dport 80 -m state --state NEW -j ACCEPT
        iptables -I INPUT 6 -p tcp --dport 443 -m state --state NEW -j ACCEPT
        netfilter-persistent save >> "$LOG_FILE" 2>&1
        ok "Portas 80/443 liberadas no iptables"
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
    ServerAlias www.$DOMINIO
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

    a2dissite 000-default.conf > /dev/null 2>&1 || true
    a2ensite painel.conf > /dev/null 2>&1
    apachectl configtest >> "$LOG_FILE" 2>&1 || warn "Config Apache com avisos. Verifique $LOG_FILE"
    systemctl reload apache2 > /dev/null 2>&1 || systemctl restart apache2 > /dev/null 2>&1
    ok "VirtualHost configurado para $DOMINIO"

    # ── SSL ──
    progress_bar 2 $total "Gerando SSL Let's Encrypt..."
    certbot --apache -d "$DOMINIO" --non-interactive --agree-tos --email "$EMAIL" --redirect >> "$LOG_FILE" 2>&1
    local certbot_status=$?
    if [ $certbot_status -eq 0 ] && [ -d "/etc/letsencrypt/live/$DOMINIO" ]; then
        ok "SSL ativo para $DOMINIO"
    else
        warn "Certbot falhou (status: $certbot_status). Log: $LOG_FILE"
        warn "Tentando novamente com método standalone..."
        systemctl stop apache2 > /dev/null 2>&1
        if certbot certonly --standalone -d "$DOMINIO" --non-interactive --agree-tos --email "$EMAIL" >> "$LOG_FILE" 2>&1; then
            cat > /etc/apache2/sites-available/painel-le-ssl.conf <<SSLVHOST
<IfModule mod_ssl.c>
<VirtualHost *:443>
    ServerName $DOMINIO
    DocumentRoot /var/www/html
    <Directory /var/www/html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog \${APACHE_LOG_DIR}/painel_error.log
    CustomLog \${APACHE_LOG_DIR}/painel_access.log combined
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/$DOMINIO/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/$DOMINIO/privkey.pem
</VirtualHost>
</IfModule>
SSLVHOST
            a2enmod ssl > /dev/null 2>&1
            a2ensite painel-le-ssl.conf > /dev/null 2>&1
            sed -i "s|</VirtualHost>|RewriteEngine On\n    RewriteCond %{HTTPS} off\n    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\n</VirtualHost>|" /etc/apache2/sites-available/painel.conf
            a2enmod rewrite > /dev/null 2>&1
            ok "SSL configurado manualmente via método standalone"
        else
            warn "Falha também no método standalone. Painel funcionará apenas em HTTP."
        fi
        systemctl start apache2 > /dev/null 2>&1
    fi

    # ── Renovação automática ──
    progress_bar 3 $total "Configurando renovação automática..."
    if ! crontab -l 2>/dev/null | grep -q "certbot renew"; then
        (crontab -l 2>/dev/null; cat <<CRON
# ATLAS_PANEL:ssl
0 3 * * * certbot renew --quiet >> $LOG_FILE 2>&1
CRON
) | crontab -
        ok "Renovação SSL automática configurada (03:00)"
    fi

    # ── Salvar ──
    progress_bar 4 $total "Salvando configuração..."
    salvar_meta "DOMINIO" "$DOMINIO"
    salvar_meta "EMAIL" "$EMAIL"

    pause
}

# ─── Instalar helper de crons ──────────────────────────────────────────────

install_cron_helper() {
    subtitulo "Instalando helper de gerenciamento de crons..."

    cat > /root/atlas_cron_helper.sh <<'HELPER'
#!/bin/bash
# Atlas Painel - Helper de crons (executado via sudo pelo www-data)
# Gerencia APENAS crons marcados com # ATLAS_PANEL:<id>
# Crons fora desse padrao (ex: SSL) sao preservados.

ATLAS_MARKER="# ATLAS_PANEL:"
WEB_ROOT="/var/www/html"
LOG_DIR="/var/log/atlas_painel"
mkdir -p "$LOG_DIR" 2>/dev/null

cmd="${1:-}"
shift || true

case "$cmd" in
    list)
        crontab -l 2>/dev/null
        ;;

    status)
        if systemctl is-active --quiet cron 2>/dev/null; then
            echo "ACTIVE"
        else
            echo "INACTIVE"
        fi
        ;;

    enabled)
        # Lista apenas as crons do painel (sem SSL) com estado ativo/inativo
        # Formato: id|schedule|command|state
        crontab -l 2>/dev/null | awk -v marker="$ATLAS_MARKER" '
            BEGIN { inblock=0; name=""; sched=""; cmd=""; state="ACTIVE" }
            /^[[:space:]]*#/ && index($0, marker) {
                # nova entrada
                if (name != "") print name "|" sched "|" cmd "|" state
                inblock=1
                state="ACTIVE"
                name=$0
                sub(".*" marker, "", name)
                sub("[[:space:]]*$", "", name)
                sched=""
                cmd=""
                next
            }
            /^[[:space:]]*#/ {
                if (inblock) { state="DISABLED"; next }
            }
            inblock && NF>0 {
                if (sched == "") sched=$1" "$2" "$3" "$4" "$5
                if (cmd == "") { for (i=6;i<=NF;i++) cmd = cmd (i>6?" ":"") $i }
            }
            END {
                if (name != "") print name "|" sched "|" cmd "|" state
            }
        '
        ;;

    ssl)
        # Imprime a cron do SSL (marcada com ATLAS_PANEL:ssl)
        crontab -l 2>/dev/null | awk -v marker="# ATLAS_PANEL:ssl" '
            /^[[:space:]]*#/ && index($0, marker) { inblock=1; print; next }
            inblock && /^[[:space:]]*[^#]/ { print; inblock=0 }
        '
        ;;

    apply)
        # Reaplica todas as crons padrao do painel (backup, onlines, checkpag, suspenderauto)
        # Preserva qualquer cron que nao seja ATLAS_PANEL (ex: usuario adicionou)
        # Preserva o bloco SSL (ATLAS_PANEL:ssl)
        tmp=$(mktemp)
        crontab -l 2>/dev/null > "$tmp"

        # Remove bloco de crons do painel (exceto SSL) - elas serao readicionadas
        awk -v marker="$ATLAS_MARKER" '
            /^[[:space:]]*#/ && index($0, marker) {
                block=$0
                inblock=1
                next
            }
            inblock && /^[[:space:]]*[^#]/ { inblock=0; next }
            inblock && /^[[:space:]]*$/ { inblock=0; next }
            !inblock { print }
        ' "$tmp" > "${tmp}.new"
        mv "${tmp}.new" "$tmp"

        # Adiciona as crons padrao
        cat >> "$tmp" <<PANEL
# ATLAS_PANEL:backup
0 */12 * * * /usr/bin/php $WEB_ROOT/backup.php >> $LOG_DIR/backup.log 2>&1
# ATLAS_PANEL:onlines
* * * * * /usr/bin/php $WEB_ROOT/onlines.php >> $LOG_DIR/onlines.log 2>&1
# ATLAS_PANEL:checkpag
* * * * * /usr/bin/php $WEB_ROOT/checkpag.php >> $LOG_DIR/checkpag.log 2>&1
# ATLAS_PANEL:suspenderauto
*/30 * * * * /usr/bin/php $WEB_ROOT/admin/suspenderauto.php >> $LOG_DIR/suspenderauto.log 2>&1
PANEL

        # Limpa linhas em branco extras
        awk 'NF' "$tmp" > "${tmp}.new" && mv "${tmp}.new" "$tmp"
        echo "" >> "$tmp"

        crontab "$tmp"
        rm -f "$tmp"
        echo "OK"
        ;;

    enable)
        # Reativa (descomenta) a cron identificada
        id="$1"
        if [ -z "$id" ]; then echo "ERR:missing id"; exit 1; fi
        tmp=$(mktemp)
        crontab -l 2>/dev/null > "$tmp"
        # Encontra a linha com o marker; descomenta apenas linhas deste bloco
        awk -v marker="$ATLAS_MARKER$id" -v base_marker="$ATLAS_MARKER" '
            /^[[:space:]]*#/ && index($0, marker) {
                print
                inblock=1
                next
            }
            inblock {
                if (/^[[:space:]]*#.*ATLAS_PANEL:/) { inblock=0; print; next }
                if (/^[[:space:]]*#/) { sub(/^[[:space:]]*#[[:space:]]?/, ""); print; next }
                if (NF==0) { inblock=0; print ""; next }
                inblock=0
                print
                next
            }
            { print }
        ' "$tmp" > "${tmp}.new"
        mv "${tmp}.new" "$tmp"
        if crontab "$tmp" 2>/dev/null; then
            echo "OK"
        else
            echo "ERR: crontab invalido (verifique o conteudo)"
            exit 1
        fi
        rm -f "$tmp"
        ;;

    disable)
        # Desativa (comenta) a cron identificada sem remover
        id="$1"
        if [ -z "$id" ]; then echo "ERR:missing id"; exit 1; fi
        tmp=$(mktemp)
        crontab -l 2>/dev/null > "$tmp"
        awk -v marker="$ATLAS_MARKER$id" '
            /^[[:space:]]*#/ && index($0, marker) { print; inblock=1; next }
            inblock {
                if (/^[[:space:]]*#.*ATLAS_PANEL:/) { inblock=0; print; next }
                if (NF>0 && !/^[[:space:]]*#/) { print "# " $0; next }
                if (NF==0) { inblock=0; print ""; next }
                inblock=0
                print
                next
            }
            { print }
        ' "$tmp" > "${tmp}.new"
        mv "${tmp}.new" "$tmp"
        if crontab "$tmp" 2>/dev/null; then
            echo "OK"
        else
            echo "ERR: crontab invalido"
            exit 1
        fi
        rm -f "$tmp"
        ;;

    restart)
        systemctl restart cron 2>/dev/null
        if systemctl is-active --quiet cron; then
            echo "OK"
        else
            echo "ERR: cron nao reiniciou"
            exit 1
        fi
        ;;

    *)
        echo "uso: $0 {list|status|enabled|ssl|apply|enable <id>|disable <id>|restart}"
        exit 2
        ;;
esac
HELPER

    chmod 700 /root/atlas_cron_helper.sh
    chown root:root /root/atlas_cron_helper.sh
    ok "Helper criado: /root/atlas_cron_helper.sh"

    # Configurar sudoers para que www-data possa executar o helper sem senha
    local sudoers_file="/etc/sudoers.d/atlas_cron"
    cat > "$sudoers_file" <<SUDO
# Permite que www-data gerencie crons do Atlas Painel via helper
www-data ALL=(root) NOPASSWD: /root/atlas_cron_helper.sh
SUDO
    chmod 440 "$sudoers_file"
    if visudo -c -f "$sudoers_file" >/dev/null 2>&1; then
        ok "Sudoers configurado: $sudoers_file"
    else
        fail_ "Sintaxe invalida em $sudoers_file"
        rm -f "$sudoers_file"
    fi

    # Garante que o servico cron esta rodando
    if ! systemctl is-active --quiet cron 2>/dev/null; then
        systemctl enable cron >/dev/null 2>&1
        systemctl start cron >/dev/null 2>&1
    fi
    ok "Servico cron ativo"
}

# ─── Instalar Painel ───────────────────────────────────────────────────────

install_panel() {
    titulo "03" "INSTALAR PAINEL ATLAS"

    load_config
    local total=11

    # ── Pré-requisitos ──
    if ! systemctl is-active --quiet apache2; then
        error_exit "Apache2 não está rodando. Execute a opção 01 primeiro."
    fi
    if ! systemctl is-active --quiet mariadb; then
        systemctl start mariadb >> "$LOG_FILE" 2>&1 || error_exit "MariaDB não inicia. Verifique: journalctl -xeu mariadb"
        sleep 2
    fi
    if ! command -v php${PHP_VER} &>/dev/null && ! command -v php &>/dev/null; then
        error_exit "PHP não encontrado. Execute a opção 01 primeiro."
    fi

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

    # Testa se root consegue conectar (via unix_socket em Debian/Ubuntu)
    if ! mysql -e "SELECT 1;" >> "$LOG_FILE" 2>&1; then
        warn "Conexão root falhou. Tentando definir senha root..."
        mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '${DB_PASS}'; FLUSH PRIVILEGES;" >> "$LOG_FILE" 2>&1 || \
            error_exit "Não foi possível autenticar no MariaDB. Execute: mysql_secure_installation"
        # Persiste a senha para uso futuro
        local mysql_cnf="/root/.my.cnf"
        cat > "$mysql_cnf" <<MYCNF
[client]
user=root
password=${DB_PASS}
MYCNF
        chmod 600 "$mysql_cnf"
    fi

    mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" >> "$LOG_FILE" 2>&1
    mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';" >> "$LOG_FILE" 2>&1
    mysql -e "ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';" >> "$LOG_FILE" 2>&1
    mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost' WITH GRANT OPTION;" >> "$LOG_FILE" 2>&1
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
    {
        printf '<?php\n'
        printf " \$dbname = '%s';\n" "${DB_NAME}"
        printf " \$dbuser = '%s';\n" "${DB_USER}"
        printf " \$dbpass = '%s';\n" "${DB_PASS}"
        printf " \$dbhost = '%s';\n" "${DB_HOST}"
        printf " \$_SESSION['token'] = '%s';\n" "${SESSION_TOKEN:-9P9trMXJP9w5Wv7}"
        printf '?>\n'
    } > "$CONEXAO_PATH"
    chmod 640 "$CONEXAO_PATH"
    chown www-data:www-data "$CONEXAO_PATH" 2>/dev/null || true
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
    # O painel (index.php) autentica comparando a senha em plaintext
    # (SELECT ... WHERE login = ? AND senha = ?), portanto a senha
    # É gravada como texto puro, nunca com password_hash().
    progress_bar 7 $total "Configurando senha do admin..."
    local admin_senha=$(openssl rand -base64 12 | tr -dc 'A-Za-z0-9' | head -c 14)
    [ ${#admin_senha} -lt 8 ] && admin_senha=$(openssl rand -hex 8)
    local admin_pass="$admin_senha"

    # Detectar automaticamente a tabela e colunas de usuários
    local user_table="" login_col="" pass_col=""
    for t in accounts usuarios users admin admins administradores; do
        local exists=$(mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -Nse \
            "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}' AND table_name='${t}';" 2>/dev/null)
        if [ "$exists" = "1" ]; then
            user_table="$t"
            for lc in login usuario username user nome email; do
                local c=$(mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -Nse \
                    "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema='${DB_NAME}' AND table_name='${t}' AND column_name='${lc}';" 2>/dev/null)
                [ "$c" = "1" ] && login_col="$lc" && break
            done
            for pc in senha password passwd pass pwd hash; do
                local c=$(mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -Nse \
                    "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema='${DB_NAME}' AND table_name='${t}' AND column_name='${pc}';" 2>/dev/null)
                [ "$c" = "1" ] && pass_col="$pc" && break
            done
            [ -n "$login_col" ] && [ -n "$pass_col" ] && break
        fi
    done

    if [ -z "$user_table" ] || [ -z "$login_col" ] || [ -z "$pass_col" ]; then
        warn "Estrutura do banco não detectada. Tentando método alternativo via PHP..."
        # Fallback: usa PHP do próprio painel para setar a senha
        if [ -f /var/www/html/index.php ] || [ -f /var/www/html/pages/login.php ]; then
            cat > /tmp/atlas_setpass.php <<'PHPSET'
<?php
$found = false;
$dir = new RecursiveDirectoryIterator('/var/www/html', RecursiveDirectoryIterator::SKIP_DOTS);
foreach (new RecursiveIteratorIterator($dir) as $f) {
    if (preg_match('/\.(php)$/', $f)) {
        $c = file_get_contents($f);
        if (preg_match('/SELECT.*FROM\s+`?(\w+)`?.*WHERE.*login.*=.*\?|FROM\s+`?(\w+)`?.*WHERE.*usuario/is', $c, $m)) {
            $tbl = !empty($m[1]) ? $m[1] : $m[2];
            echo "TABELA: $tbl\n";
            $found = true;
        }
    }
}
?>
PHPSET
            warn "Estrutura não padronizada. Verifique manualmente em /var/www/html"
        fi
        salvar_meta "PAINEL_SENHA" "$admin_senha"
        ok "Senha gerada (verifique estrutura manualmente): $admin_senha"
    else
        # Garante que existe um usuário admin
        local admin_exists=$(mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -Nse \
            "SELECT COUNT(*) FROM \`${user_table}\` WHERE \`${login_col}\`='admin';" 2>/dev/null)
        if [ "$admin_exists" = "0" ]; then
            # Tenta inserir um admin padrão
            mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -e \
                "INSERT INTO \`${user_table}\` (\`${login_col}\`, \`${pass_col}\`) VALUES ('admin', '${admin_pass}');" >> "$LOG_FILE" 2>&1
            ok "Usuário admin criado"
        else
            mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -e \
                "UPDATE \`${user_table}\` SET \`${pass_col}\` = '${admin_pass}' WHERE \`${login_col}\` = 'admin' LIMIT 1;" >> "$LOG_FILE" 2>&1
            ok "Senha do admin atualizada"
        fi
        salvar_meta "PAINEL_SENHA" "$admin_senha"
        ok "Estrutura detectada: ${user_table}.${login_col} / ${user_table}.${pass_col}"
    fi

    # ── Permissões ──
    progress_bar 8 $total "Ajustando permissões..."
    chown -R www-data:www-data /var/www/html/
    find /var/www/html -type d -exec chmod 755 {} \;
    find /var/www/html -type f -exec chmod 644 {} \;
    ok "Permissões ajustadas"

    # ── Proteger atlas ──
    progress_bar 9 $total "Protegendo diretório atlas..."
    cat > /var/www/html/atlas/.htaccess <<'HTACCESS'
<FilesMatch "\.(inc|sql|log|md)$">
    Require all denied
</FilesMatch>
HTACCESS
    ok "Atlas protegido via .htaccess"

    # ── Limpeza ──
    progress_bar 10 $total "Limpando arquivos temporários..."
    rm -rf "$tmp_dir"
    rm -f /var/www/html/install.sh /var/www/html/install01.sh /var/www/html/README.md /var/www/html/security-audit-atlas-sem-key.md /var/www/html/telegram-bots-functions.md
    ok "Arquivos temporários removidos"

    # ── Cron ──
    progress_bar 11 $total "Configurando gerenciamento de crons..."
    mysql -e "ALTER TABLE configs ADD COLUMN IF NOT EXISTS cron_ativo INT(1) NOT NULL DEFAULT 1;" 2>/dev/null
    mysql -e "UPDATE configs SET cron_ativo = 1 WHERE id = 1;" 2>/dev/null

    install_cron_helper

    if [ -x /root/atlas_cron_helper.sh ]; then
        /root/atlas_cron_helper.sh apply >/dev/null 2>&1 && ok "Crons do painel aplicadas" || warn "Falha ao aplicar crons (helper apply)"
    fi

    # ── Recarregar Apache ──
    systemctl reload apache2 > /dev/null 2>&1 || systemctl restart apache2 > /dev/null 2>&1
    ok "Apache recarregado"

    # ── Teste final ──
    if [ -n "$DOMINIO" ] && [ "$DOMINIO" != "Não configurado" ]; then
        local test_url="https://$DOMINIO"
        local http_code=$(curl -sk -o /dev/null -w "%{http_code}" --max-time 10 "$test_url" 2>/dev/null)
        if [ "$http_code" = "200" ] || [ "$http_code" = "302" ] || [ "$http_code" = "301" ]; then
            ok "Painel acessível em $test_url (HTTP $http_code)"
        else
            warn "Painel pode não estar acessível (HTTP $http_code). Verifique manualmente."
        fi
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
    {
        printf '<?php\n'
        printf " \$dbname = '%s';\n" "${DB_NAME:-gestorssh}"
        printf " \$dbuser = '%s';\n" "${DB_USER:-gestorssh}"
        printf " \$dbpass = '%s';\n" "${DB_PASS}"
        printf " \$dbhost = '%s';\n" "${DB_HOST:-localhost}"
        printf " \$_SESSION['token'] = '%s';\n" "${SESSION_TOKEN:-9P9trMXJP9w5Wv7}"
        printf '?>\n'
    } > "$CONEXAO_PATH"
    chmod 640 "$CONEXAO_PATH"
    ok "conexao.php recriado"

    # Proteger diretório atlas via .htaccess
    cat > /var/www/html/atlas/.htaccess <<'HTACCESS'
<FilesMatch "\.(inc|sql|log|md)$">
    Require all denied
</FilesMatch>
HTACCESS
    ok "Atlas protegido via .htaccess"

    progress_bar 2 $total "Configurando banco de dados..."
    mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME:-gestorssh}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" >> "$LOG_FILE" 2>&1
    mysql -e "ALTER USER '${DB_USER:-gestorssh}'@'localhost' IDENTIFIED BY '${DB_PASS}';" >> "$LOG_FILE" 2>&1
    mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME:-gestorssh}\`.* TO '${DB_USER:-gestorssh}'@'localhost' WITH GRANT OPTION;" >> "$LOG_FILE" 2>&1
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
    local nova_senha=$(openssl rand -base64 12 | tr -dc 'A-Za-z0-9' | head -c 14)
    [ ${#nova_senha} -lt 8 ] && nova_senha=$(openssl rand -hex 8)
    # Painel autentica em plaintext -> senha gravada como texto puro
    local admin_pass="$nova_senha"

    progress_bar 2 $total "Atualizando banco de dados..."
    # Detectar estrutura automaticamente
    local user_table="" login_col="" pass_col=""
    for t in accounts usuarios users admin admins administradores; do
        local exists=$(mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -Nse \
            "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}' AND table_name='${t}';" 2>/dev/null)
        if [ "$exists" = "1" ]; then
            user_table="$t"
            for lc in login usuario username user nome email; do
                local c=$(mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -Nse \
                    "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema='${DB_NAME}' AND table_name='${t}' AND column_name='${lc}';" 2>/dev/null)
                [ "$c" = "1" ] && login_col="$lc" && break
            done
            for pc in senha password passwd pass pwd hash; do
                local c=$(mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -Nse \
                    "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema='${DB_NAME}' AND table_name='${t}' AND column_name='${pc}';" 2>/dev/null)
                [ "$c" = "1" ] && pass_col="$pc" && break
            done
            [ -n "$login_col" ] && [ -n "$pass_col" ] && break
        fi
    done

    if [ -z "$user_table" ] || [ -z "$login_col" ] || [ -z "$pass_col" ]; then
        fail_ "Estrutura do banco não reconhecida. Não foi possível detectar tabela de usuários."
        pause
        return
    fi

    local update_status
    mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" \
        -e "UPDATE \`${user_table}\` SET \`${pass_col}\` = '${admin_pass}' WHERE \`${login_col}\` = 'admin' LIMIT 1;" >> "$LOG_FILE" 2>&1
    update_status=$?

    # Verificação real: testa se a senha bate com o que está no banco
    local stored_hash=$(mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -Nse \
        "SELECT \`${pass_col}\` FROM \`${user_table}\` WHERE \`${login_col}\` = 'admin' LIMIT 1;" 2>/dev/null)

    progress_bar 3 $total "Salvando configuração..."
    if [ $update_status -eq 0 ] && [ "$stored_hash" = "$admin_pass" ]; then
        salvar_meta "PAINEL_SENHA" "$nova_senha"
        echo -e ""
        ok "Senha do admin redefinida com sucesso!"
        echo -e "   ${BRANCO}Tabela:${NC}  ${CIANO}${user_table}${NC}"
        echo -e "   ${BRANCO}Login:${NC}   admin"
        echo -e "   ${BRANCO}Senha:${NC}   ${VERDE}${nova_senha}${NC}"
        echo -e ""
    else
        fail_ "Falha ao alterar senha. Verifique $LOG_FILE"
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
    systemctl stop apache2 mariadb php${PHP_VER}-fpm 2>/dev/null
    systemctl disable apache2 mariadb php${PHP_VER}-fpm 2>/dev/null

    progress_bar 4 $total "Removendo pacotes..."
    apt remove --purge -y apache2 mariadb-server php${PHP_VER}* certbot python3-certbot-apache 2>/dev/null
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
    for svc in apache2 mariadb php${PHP_VER}-fpm; do
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
    if [ -x /root/atlas_cron_helper.sh ]; then
        if systemctl is-active --quiet cron 2>/dev/null; then
            echo -e "   Servico cron: ${VERDE}ATIVO${NC}"
        else
            echo -e "   Servico cron: ${VERMELHO}INATIVO${NC}"
        fi
        local painel_total=0 painel_ativas=0
        while IFS='|' read -r name sched cmd state; do
            [ -z "$name" ] && continue
            painel_total=$((painel_total+1))
            [ "$state" = "ACTIVE" ] && painel_ativas=$((painel_ativas+1))
        done < <(/root/atlas_cron_helper.sh enabled 2>/dev/null)
        echo -e "   Crons do painel: ${VERDE}${painel_ativas} ativas${NC} / ${painel_total} total"
        if /root/atlas_cron_helper.sh ssl 2>/dev/null | grep -q "certbot renew"; then
            echo -e "   Renovacao SSL: ${VERDE}configurada${NC}"
        else
            echo -e "   Renovacao SSL: ${AMARELO}nao configurada${NC}"
        fi
    else
        echo -e "   ${AMARELO}Helper nao instalado. Use opcao 03 para instalar.${NC}"
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

# ─── Reinstalar Painel (mantém banco/conexao) ──────────────────────────────

reinstalar_painel() {
    titulo "08" "REINSTALAR PAINEL (MANTÉM BANCO)"

    echo -e ""
    warn "Isso vai SUBSTITUIR todos os arquivos do painel."
    warn "O conexao.php e o banco de dados serão PRESERVADOS."
    echo -e ""
    read -p " Continuar? (s/N): " confirma
    [ "$confirma" != "s" ] && [ "$confirma" != "S" ] && { info "Cancelado."; pause; return; }

    load_config
    local total=8

    if ! systemctl is-active --quiet apache2; then
        error_exit "Apache2 não está rodando. Execute a opção 01 primeiro."
    fi

    # ── 1/8: Backup do conexao.php ──
    progress_bar 1 $total "Backup do conexao.php..."
    local bk_conexao="/root/conexao_backup_$(date +%Y%m%d_%H%M%S).php"
    if [ -f "$CONEXAO_PATH" ]; then
        cp "$CONEXAO_PATH" "$bk_conexao"
        ok "Backup salvo em $bk_conexao"
    else
        warn "conexao.php não existe — banco precisará ser reconfigurado"
    fi

    # ── 2/8: Clone do repo ──
    progress_bar 2 $total "Baixando painel do GitHub..."
    local tmp_dir="/tmp/atlas_painel_$$"
    rm -rf "$tmp_dir"
    if ! git clone --depth 1 https://github.com/CoutySSH/Atlas-Painel-CTSSH "$tmp_dir" >> "$LOG_FILE" 2>&1; then
        error_exit "Falha ao clonar repositório."
    fi
    ok "Repositório clonado"

    local origem=""
    for d in "Atlas CTSSH" "Atlas" "Atlas Sem Key"; do
        [ -d "$tmp_dir/$d" ] && { origem="$tmp_dir/$d"; break; }
    done
    [ -z "$origem" ] && error_exit "Pasta do painel não encontrada no repositório."

    # ── 3/8: Backup do HTML ──
    progress_bar 3 $total "Backup do diretório atual..."
    if [ -d "/var/www/html" ] && [ "$(ls -A /var/www/html 2>/dev/null)" ]; then
        local bk_html="${BACKUP_DIR}/html_pre_reinstall.tar.gz"
        tar -czf "$bk_html" -C /var/www html 2>/dev/null && ok "Backup: $bk_html" || warn "Falha no backup"
    fi

    # ── 4/8: Remover arquivos antigos (preservar atlas/) ──
    progress_bar 4 $total "Removendo arquivos antigos..."
    cd /var/www/html || { fail_ "Não foi possível acessar /var/www/html"; pause; return; }
    shopt -s dotglob nullglob 2>/dev/null
    for item in *; do
        [ "$item" = "atlas" ] && continue
        rm -rf "$item"
    done
    shopt -u dotglob nullglob 2>/dev/null
    ok "Arquivos antigos removidos (atlas/ preservado)"

    # ── 5/8: Copiar novos arquivos ──
    progress_bar 5 $total "Copiando novos arquivos..."
    cp -a "$origem"/. /var/www/html/
    [ -f "$tmp_dir/banco.sql" ] && cp "$tmp_dir/banco.sql" /var/www/html/banco.sql
    rm -f /var/www/html/index.html 2>/dev/null
    ok "Arquivos copiados"

    # ── 6/8: Restaurar conexao.php e proteger atlas ──
    progress_bar 6 $total "Restaurando conexao.php..."
    mkdir -p /var/www/html/atlas
    if [ -f "$bk_conexao" ]; then
        cp "$bk_conexao" "$CONEXAO_PATH"
        chmod 640 "$CONEXAO_PATH"
        chown www-data:www-data "$CONEXAO_PATH" 2>/dev/null
        ok "conexao.php restaurado (banco preservado)"
    fi

    cat > /var/www/html/atlas/.htaccess <<'HTACCESS'
<FilesMatch "\.(inc|sql|log|md)$">
    Require all denied
</FilesMatch>
HTACCESS
    ok "Atlas protegido via .htaccess"

    # ── 7/8: Auto-fix arquivos PHP sem tag ──
    progress_bar 7 $total "Corrigindo arquivos PHP..."
    local fix_count=0
    while IFS= read -r f; do
        [ -z "$f" ] && continue
        local first
        first=$(head -c 50 "$f" | tr -d '[:space:]' | head -c 30)
        if echo "$first" | grep -qE '^\$|^(session_|error_|if|for|while|function|echo|require|include|header|mysqli_|sql|mysql_|use |namespace|class |[a-z_]+\(|[a-z_]+->)'; then
            sed -i '1i <?php' "$f"
            fix_count=$((fix_count + 1))
        fi
    done < <(find /var/www/html -name "*.php" -exec grep -L '<?php' {} + 2>/dev/null)
    ok "$fix_count arquivos PHP corrigidos"

    # Limpeza
    rm -rf "$tmp_dir"
    rm -f /var/www/html/install.sh /var/www/html/install01.sh /var/www/html/README.md
    rm -f /var/www/html/security-audit-atlas-sem-key.md /var/www/html/telegram-bots-functions.md

    # Permissões
    chown -R www-data:www-data /var/www/html/
    find /var/www/html -type d -exec chmod 755 {} \;
    find /var/www/html -type f -exec chmod 644 {} \;
    ok "Permissões ajustadas"

    # Helper de crons (reaplica e mantem servico ativo)
    install_cron_helper
    if [ -x /root/atlas_cron_helper.sh ]; then
        /root/atlas_cron_helper.sh apply >/dev/null 2>&1 && ok "Crons do painel reaplicadas" || warn "Falha ao reaplicar crons"
    fi

    # ── 8/8: Recarregar Apache ──
    progress_bar 8 $total "Recarregando Apache..."
    systemctl reload apache2 > /dev/null 2>&1 || systemctl restart apache2 > /dev/null 2>&1
    ok "Apache recarregado"

    # Teste final
    if [ -n "$DOMINIO" ] && [ "$DOMINIO" != "Não configurado" ]; then
        local test_url="https://$DOMINIO"
        local http_code=$(curl -sk -o /dev/null -w "%{http_code}" --max-time 10 "$test_url" 2>/dev/null)
        if [ "$http_code" = "200" ] || [ "$http_code" = "302" ] || [ "$http_code" = "301" ]; then
            ok "Painel acessível em $test_url (HTTP $http_code)"
        else
            warn "Painel pode não estar acessível (HTTP $http_code)"
        fi
    fi

    echo -e ""
    echo -e "${VERDE}${NEGRITO}╔═══════════════════════════════════════════════════════╗${NC}"
    echo -e "${VERDE}${NEGRITO}║   PAINEL REINSTALADO COM SUCESSO!                      ║${NC}"
    echo -e "${VERDE}${NEGRITO}║   Banco de dados e credenciais preservados.            ║${NC}"
    echo -e "${VERDE}${NEGRITO}╚═══════════════════════════════════════════════════════╝${NC}"
    echo -e ""
    pause
}

# ─── Reiniciar Serviços ────────────────────────────────────────────────────

reiniciar_servicos() {
    titulo "09" "REINICIAR SERVIÇOS"

    load_config
    local servicos=("apache2" "mariadb" "php${PHP_VER}-fpm" "cron")
    local total=5

    # ── 1/5: Status atual ──
    progress_bar 1 $total "Verificando status atual..."
    echo -e ""
    for svc in "${servicos[@]}"; do
        if systemctl is-active --quiet "$svc" 2>/dev/null; then
            echo -e "   ${CIANO}$svc${NC}: ${VERDE}ATIVO${NC}"
        else
            echo -e "   ${CIANO}$svc${NC}: ${VERMELHO}INATIVO${NC}"
        fi
    done

    # ── 2/5: Parar serviços ──
    progress_bar 2 $total "Parando serviços..."
    for svc in "${servicos[@]}"; do
        systemctl stop "$svc" >> "$LOG_FILE" 2>&1
    done
    sleep 1
    ok "Serviços parados"

    # ── 3/5: Iniciar serviços ──
    progress_bar 3 $total "Iniciando serviços..."
    for svc in "${servicos[@]}"; do
        systemctl start "$svc" >> "$LOG_FILE" 2>&1
    done
    sleep 2
    ok "Serviços iniciados"

    # ── 4/5: Habilitar inicialização automática ──
    progress_bar 4 $total "Verificando inicialização automática..."
    for svc in "${servicos[@]}"; do
        systemctl enable "$svc" > /dev/null 2>&1
    done
    ok "Inicialização automática configurada"

    # ── 5/5: Status final + testes ──
    progress_bar 5 $total "Verificando status final..."
    echo -e ""
    local algum_falhou=0
    for svc in "${servicos[@]}"; do
        if systemctl is-active --quiet "$svc" 2>/dev/null; then
            echo -e "   ${CIANO}$svc${NC}: ${VERDE}✓ ATIVO${NC}"
        else
            echo -e "   ${CIANO}$svc${NC}: ${VERMELHO}✗ FALHOU${NC}"
            echo "      $(systemctl status $svc --no-pager 2>&1 | grep -i 'active\|failed' | head -1)"
            algum_falhou=1
        fi
    done

    # Teste de banco
    if [ -n "$DB_PASS" ]; then
        echo -e ""
        if mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" "$DB_NAME" -e "SELECT 1;" > /dev/null 2>&1; then
            ok "Conexão com banco OK"
        else
            warn "Falha na conexão com banco"
            algum_falhou=1
        fi
    fi

    # Teste HTTP
    if [ -n "$DOMINIO" ] && [ "$DOMINIO" != "Não configurado" ]; then
        local test_url="https://$DOMINIO"
        local http_code=$(curl -sk -o /dev/null -w "%{http_code}" --max-time 10 "$test_url" 2>/dev/null)
        if [ "$http_code" = "200" ] || [ "$http_code" = "302" ] || [ "$http_code" = "301" ]; then
            ok "Painel respondendo em $test_url (HTTP $http_code)"
        else
            warn "Painel retornou HTTP $http_code"
            algum_falhou=1
        fi
    fi

    echo -e ""
    if [ $algum_falhou -eq 0 ]; then
        echo -e "${VERDE}${NEGRITO}╔═══════════════════════════════════════════════════════╗${NC}"
        echo -e "${VERDE}${NEGRITO}║        TODOS OS SERVIÇOS FORAM REINICIADOS!            ║${NC}"
        echo -e "${VERDE}${NEGRITO}╚═══════════════════════════════════════════════════════╝${NC}"
    else
        echo -e "${AMARELO}${NEGRITO}╔═══════════════════════════════════════════════════════╗${NC}"
        echo -e "${AMARELO}${NEGRITO}║     SERVIÇOS REINICIADOS COM ALGUNS AVISOS             ║${NC}"
        echo -e "${AMARELO}${NEGRITO}║     Verifique o log: $LOG_FILE${NC}"
        echo -e "${AMARELO}${NEGRITO}╚═══════════════════════════════════════════════════════╝${NC}"
    fi
    echo -e ""
    pause
}

# ─── Gerenciar Crons ────────────────────────────────────────────────────────

cron_decode_schedule() {
    # Recebe "m h dom mon dow" e retorna descricao legivel
    local s="$1"
    local m h dom mon dow
    read -r m h dom mon dow <<< "$s"
    local desc=""

    # Caso padrao das 4 crons do painel
    if   [ "$m" = "0" ] && [ "$h" = "*/12" ]; then desc="a cada 12h (00:00, 12:00)"
    elif [ "$m" = "*" ] && [ "$h" = "*" ];    then desc="a cada minuto"
    elif [ "$m" = "*/30" ] && [ "$h" = "*" ]; then desc="a cada 30 min"
    elif [ "$m" = "*/15" ] && [ "$h" = "*" ]; then desc="a cada 15 min"
    elif [ "$m" = "*/5" ]  && [ "$h" = "*" ]; then desc="a cada 5 min"
    elif [ "$m" = "*/10" ] && [ "$h" = "*" ]; then desc="a cada 10 min"
    elif [ "$m" = "0" ] && [ "$h" = "*/2" ];  then desc="a cada 2h"
    elif [ "$m" = "0" ] && [ "$h" = "*/3" ];  then desc="a cada 3h"
    elif [ "$m" = "0" ] && [ "$h" = "*/6" ];  then desc="a cada 6h"
    elif [ "$m" = "0" ] && [ "$h" = "3" ];    then desc="todo dia as 03:00"
    elif [ "$m" = "0" ] && [ "$h" = "0" ];    then desc="todo dia a meia-noite"
    else
        # Fallback generico
        case "$m" in
            */[0-9]*) desc="cada ${m#*/} min" ;;
            *)        desc="min ${m}" ;;
        esac
        case "$h" in
            */[0-9]*) desc+=" / ciclo ${h#*/}h" ;;
            *)        [ "$h" != "*" ] && desc+=" as ${h}h" ;;
        esac
    fi
    [ "$dom" != "*" ] && desc+=" / dia ${dom}"
    [ "$mon" != "*" ] && desc+=" / mes ${mon}"
    [ "$dow" != "*" ] && desc+=" / sem ${dow}"
    echo "$desc"
}

cron_resumo() {
    # Imprime resumo rapido das crons para o menu
    local helper="/root/atlas_cron_helper.sh"
    if [ ! -x "$helper" ]; then
        echo "helper ausente"
        return
    fi
    local total=0 ativas=0
    while IFS='|' read -r name sched cmd state; do
        [ -z "$name" ] && continue
        total=$((total+1))
        [ "$state" = "ACTIVE" ] && ativas=$((ativas+1))
    done < <($helper enabled 2>/dev/null)
    echo "${ativas}|${total}"
}

gerenciar_crons() {
    local helper="/root/atlas_cron_helper.sh"

    # Garante helper instalado
    if [ ! -x "$helper" ]; then
        warn "Helper nao encontrado. Instalando..."
        install_cron_helper
    fi

    while true; do
        clear
        titulo "10" "GERENCIAR CRONS DO PAINEL"

        # ── Status do servico cron ──
        local cron_state
        if systemctl is-active --quiet cron 2>/dev/null; then
            cron_state="${VERDE}ATIVO${NC}"
        else
            cron_state="${VERMELHO}INATIVO${NC}"
        fi
        echo -e " ${CIANO}▸ Servico cron:${NC} $cron_state"
        if systemctl is-enabled --quiet cron 2>/dev/null; then
            echo -e " ${CIANO}▸ Inicializacao automatica:${NC} ${VERDE}habilitada${NC}"
        else
            echo -e " ${CIANO}▸ Inicializacao automatica:${NC} ${AMARELO}desabilitada${NC}"
        fi

        # ── SSL (separado, gerenciado pela opcao 02) ──
        echo -e ""
        echo -e " ${CIANO}▸ Renovacao SSL:${NC}"
        if $helper ssl 2>/dev/null | grep -q "certbot renew"; then
            echo -e "   ${VERDE}✔${NC} Configurada (03:00 diario)"
        else
            echo -e "   ${AMARELO}⚠${NC} Nao configurada (use opcao 02)"
        fi

        # ── Lista de crons do painel ──
        echo -e ""
        echo -e " ${BRANCO}${NEGRITO}▸ Crons do painel:${NC}"
        echo -e "   ${CIANO}ID${NC}              ${CIANO}AGENDA${NC}                          ${CIANO}COMANDO${NC}                       ${CIANO}ESTADO${NC}"
        linha "$CIANO"

        local ids=()
        local idx=0
        while IFS='|' read -r name sched cmd state; do
            [ -z "$name" ] && continue
            idx=$((idx+1))
            ids+=("$name")
            local st_icon st_col
            if [ "$state" = "ACTIVE" ]; then
                st_icon="●"; st_col="$VERDE"
            else
                st_icon="○"; st_col="$AMARELO"
            fi
            local desc
            desc=$(cron_decode_schedule "$sched")
            local cmd_short="$cmd"
            [ ${#cmd_short} -gt 32 ] && cmd_short="${cmd_short:0:29}..."
            printf "   %-15s %-32s %-32s ${st_col}%s${NC}\n" "$name" "$desc" "$cmd_short" "$st_icon $state"
        done < <($helper enabled 2>/dev/null)

        if [ $idx -eq 0 ]; then
            echo -e "   ${AMARELO}Nenhuma cron do painel instalada.${NC}"
            echo -e "   ${CIANO}Dica:${NC} use a opcao [A] para reaplicar as crons padrao."
        fi

        # ── Logs recentes ──
        echo -e ""
        echo -e " ${CIANO}▸ Ultima execucao detectada:${NC}"
        local log_dir="/var/log/atlas_painel"
        for f in backup onlines checkpag suspenderauto; do
            local lf="$log_dir/$f.log"
            if [ -f "$lf" ] && [ -s "$lf" ]; then
                local last_line
                last_line=$(tail -1 "$lf" 2>/dev/null)
                local last_time
                last_time=$(stat -c '%y' "$lf" 2>/dev/null | cut -d. -f1)
                echo -e "   ${BRANCO}$f${NC}: $last_time"
            fi
        done

        # ── Submenu ──
        echo -e ""
        linha "$AZUL"
        echo -e " ${BRANCO}${NEGRITO}ACOES:${NC}"
        echo -e "   ${VERMELHO}[A]${NC} Reaplicar crons padrao do painel"
        echo -e "   ${VERMELHO}[R]${NC} Reiniciar servico cron"
        echo -e "   ${VERMELHO}[L]${NC} Listar crontab completa"
        echo -e "   ${VERMELHO}[E]${NC} Editar crontab manualmente"
        echo -e "   ${VERMELHO}[D]${NC} Desativar uma cron especifica"
        echo -e "   ${VERMELHO}[H]${NC} Reativar uma cron especifica"
        echo -e "   ${VERMELHO}[V]${NC} Ver log de uma cron"
        echo -e "   ${VERMELHO}[0]${NC} Voltar ao menu principal"
        linha "$AZUL"

        echo -e -n " ${BRANCO}Opcao:${NC} "; read SUB

        case "$SUB" in
            A|a)
                info "Reaplicando crons padrao do painel..."
                local out
                out=$($helper apply 2>&1)
                if [ "$out" = "OK" ]; then
                    ok "Crons aplicadas com sucesso!"
                else
                    fail_ "Falha ao aplicar: $out"
                fi
                sleep 2
                ;;
            R|r)
                info "Reiniciando servico cron..."
                local out
                out=$($helper restart 2>&1)
                if [ "$out" = "OK" ]; then
                    ok "Cron reiniciado!"
                else
                    fail_ "Falha: $out"
                fi
                sleep 2
                ;;
            L|l)
                echo -e ""
                linha "$CIANO"
                echo -e " ${CIANO}▸ Crontab completa (root):${NC}"
                linha "$CIANO"
                $helper list 2>/dev/null | sed 's/^/   /'
                echo -e ""
                pause
                ;;
            E|e)
                warn "Abrindo editor de crontab (cuidado!)"
                sleep 1
                crontab -e
                pause
                ;;
            D|d)
                if [ ${#ids[@]} -eq 0 ]; then
                    warn "Nenhuma cron para desativar."
                    sleep 1
                    continue
                fi
                echo -e ""
                echo -e -n " ${BRANCO}ID da cron para desativar:${NC} "; read cron_id
                if [ -z "$cron_id" ]; then continue; fi
                local out
                out=$($helper disable "$cron_id" 2>&1)
                if [ "$out" = "OK" ]; then
                    ok "Cron '$cron_id' desativada."
                else
                    fail_ "Falha: $out"
                fi
                sleep 2
                ;;
            H|h)
                if [ ${#ids[@]} -eq 0 ]; then
                    warn "Nenhuma cron cadastrada. Reaplicar padrao primeiro."
                    sleep 1
                    continue
                fi
                echo -e ""
                echo -e -n " ${BRANCO}ID da cron para reativar:${NC} "; read cron_id
                if [ -z "$cron_id" ]; then continue; fi
                local out
                out=$($helper enable "$cron_id" 2>&1)
                if [ "$out" = "OK" ]; then
                    ok "Cron '$cron_id' reativada."
                else
                    fail_ "Falha: $out"
                fi
                sleep 2
                ;;
            V|v)
                echo -e ""
                echo -e -n " ${BRANCO}ID do log (backup/onlines/checkpag/suspenderauto):${NC} "; read log_id
                if [ -z "$log_id" ]; then continue; fi
                local lf="/var/log/atlas_painel/${log_id}.log"
                if [ -f "$lf" ]; then
                    echo -e ""
                    linha "$CIANO"
                    echo -e " ${CIANO}▸ Ultimas 20 linhas de $lf:${NC}"
                    linha "$CIANO"
                    tail -20 "$lf" 2>/dev/null | sed 's/^/   /'
                else
                    warn "Log nao encontrado: $lf"
                fi
                echo -e ""
                pause
                ;;
            0|"")
                return
                ;;
            *)
                warn "Opcao invalida."
                sleep 1
                ;;
        esac
    done
}

# ─── Helpers (carregar/salvar config) ──────────────────────────────────────

get_php_var() {
    local var_name="$1"; local file="$2"
    if [ -f "$file" ]; then
        php -r "\$$var_name=''; include '$file'; echo \$$var_name;" 2>/dev/null
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
    systemctl is-active --quiet php${PHP_VER}-fpm 2>/dev/null && php_ok=1
    apachectl -M 2>/dev/null | grep -qE "proxy_fcgi|php_module" && php_ok=1
    [ "$php_ok" -eq 1 ] && php_icon="${VERDE}ATIVO${NC}" || php_icon="${VERMELHO}INATIVO${NC}"
    if [ "$DOMINIO" != "Nao configurado" ] && [ -d "/etc/letsencrypt/live/$DOMINIO" ]; then
        ssl_icon="${VERDE}ATIVO${NC}"
    else
        ssl_icon="${AMARELO}INATIVO${NC}"
    fi
    echo -e "${AZUL}|${NC}  Apache ${apache_icon}  |  MariaDB ${mariadb_icon}  |  PHP ${php_icon}"
    echo -e "${AZUL}|${NC}  SSL ${ssl_icon}${NC}"
    local cron_icon cron_info
    if systemctl is-active --quiet cron 2>/dev/null; then
        if [ -x /root/atlas_cron_helper.sh ]; then
            local resumo
            resumo=$(cron_resumo 2>/dev/null)
            if [ "$resumo" != "helper ausente" ] && [ -n "$resumo" ]; then
                local ativas="${resumo%|*}" total="${resumo#*|}"
                cron_icon="${VERDE}${ativas}/${total} ativas${NC}"
            else
                cron_icon="${AMARELO}sem crons${NC}"
            fi
        else
            cron_icon="${AMARELO}helper ausente${NC}"
        fi
    else
        cron_icon="${VERMELHO}INATIVO${NC}"
    fi
    echo -e "${AZUL}|${NC}  Cron ${cron_icon}${NC}"
    echo -e "${AZUL}+----------------------------------------------------------------+${NC}"

    local opcoes=(
        "01:Preparar Sistema (repos, deps, firewall)"
        "02:Configurar Dominio e SSL (com validacao DNS)"
        "03:Instalar Painel Atlas (GitHub + BD + permissoes)"
        "04:Reparar Banco de Dados"
        "05:Resetar Senha do Admin"
        "06:Desinstalar Completo"
        "07:Status do Sistema"
        "08:Reinstalar Painel (atualiza arquivos, mantem banco)"
        "09:Reiniciar Servicos (Apache/MariaDB/PHP-FPM)"
        "10:Gerenciar Crons (listar/ativar/desativar/reaplicar)"
        "99:Sair"
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
         8|08)             reinstalar_painel  ;;
          9|09)             reiniciar_servicos ;;
          10|10)             gerenciar_crons   ;;
         99)
            echo -e "\n${VERDE} Saindo...${NC}\n"
            exit 0
            ;;
        *)
            echo -e "\n${VERMELHO} Opção inválida!${NC}"
            sleep 2
            ;;
    esac
done
