#!/bin/bash

# ============================================
# Script para Configurar SSL (HTTPS) com Let's Encrypt
# FinaMassa - Sistema de Gestão
# ============================================

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}===========================================${NC}"
echo -e "${BLUE}Configurar SSL (HTTPS) - Let's Encrypt${NC}"
echo -e "${BLUE}===========================================${NC}"
echo ""

# Verificar se está rodando como root
if [ "$(id -u)" -ne 0 ]; then 
    echo -e "${RED}Este script precisa ser executado como root${NC}"
    echo -e "${YELLOW}Execute: sudo ./configurar-ssl.sh${NC}"
    exit 1
fi

# Solicitar domínio
read -p "Digite o domínio (ex: controlaso.com.br): " DOMAIN
DOMAIN=${DOMAIN:-controlaso.com.br}

# Solicitar email para notificações
read -p "Digite seu email para notificações do Let's Encrypt: " EMAIL

if [ -z "$EMAIL" ]; then
    echo -e "${RED}Erro: Email é obrigatório${NC}"
    exit 1
fi

echo ""
echo -e "${BLUE}Configurando SSL para: $DOMAIN${NC}"
echo -e "${BLUE}Email: $EMAIL${NC}"
echo ""

# ============================================
# 1. INSTALAR CERTBOT
# ============================================
echo -e "${YELLOW}[1/6] Instalando Certbot...${NC}"

if ! command -v certbot &> /dev/null; then
    apt update
    apt install -y certbot python3-certbot-apache
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Certbot instalado${NC}"
    else
        echo -e "${RED}✗ Erro ao instalar Certbot${NC}"
        exit 1
    fi
else
    CERTBOT_VERSION=$(certbot --version | cut -d' ' -f2)
    echo -e "${GREEN}✓ Certbot já está instalado (versão $CERTBOT_VERSION)${NC}"
fi

# ============================================
# 2. VERIFICAR APACHE
# ============================================
echo ""
echo -e "${YELLOW}[2/6] Verificando Apache...${NC}"

if ! systemctl is-active --quiet apache2; then
    echo -e "${RED}✗ Apache não está rodando${NC}"
    echo -e "${YELLOW}Iniciando Apache...${NC}"
    systemctl start apache2
fi

if systemctl is-active --quiet apache2; then
    echo -e "${GREEN}✓ Apache está rodando${NC}"
else
    echo -e "${RED}✗ Erro ao iniciar Apache${NC}"
    exit 1
fi

# Verificar se módulo SSL está habilitado
if apache2ctl -M 2>/dev/null | grep -q "ssl_module"; then
    echo -e "${GREEN}✓ Módulo SSL está habilitado${NC}"
else
    echo -e "${YELLOW}Habilitando módulo SSL...${NC}"
    a2enmod ssl
    systemctl restart apache2
    echo -e "${GREEN}✓ Módulo SSL habilitado${NC}"
fi

# Verificar se módulo rewrite está habilitado
if apache2ctl -M 2>/dev/null | grep -q "rewrite_module"; then
    echo -e "${GREEN}✓ Módulo rewrite está habilitado${NC}"
else
    echo -e "${YELLOW}Habilitando módulo rewrite...${NC}"
    a2enmod rewrite
    systemctl restart apache2
    echo -e "${GREEN}✓ Módulo rewrite habilitado${NC}"
fi

# ============================================
# 3. VERIFICAR/CRIAR VIRTUAL HOST
# ============================================
echo ""
echo -e "${YELLOW}[3/6] Verificando Virtual Host...${NC}"

VHOST_FILE="/etc/apache2/sites-available/${DOMAIN}.conf"
VHOST_FILE_SSL="/etc/apache2/sites-available/${DOMAIN}-ssl.conf"

# Verificar se já existe virtual host
if [ -f "$VHOST_FILE" ] || [ -f "$VHOST_FILE_SSL" ]; then
    echo -e "${GREEN}✓ Virtual host encontrado${NC}"
else
    echo -e "${YELLOW}Criando virtual host básico...${NC}"
    
    # Criar virtual host HTTP (será usado pelo Certbot)
    cat > "$VHOST_FILE" <<EOF
<VirtualHost *:80>
    ServerName $DOMAIN
    ServerAlias www.$DOMAIN
    
    DocumentRoot /var/www/html/FinaMassa/public
    
    <Directory /var/www/html/FinaMassa/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/${DOMAIN}_error.log
    CustomLog \${APACHE_LOG_DIR}/${DOMAIN}_access.log combined
</VirtualHost>
EOF
    
    # Habilitar site
    a2ensite ${DOMAIN}.conf
    systemctl reload apache2
    
    echo -e "${GREEN}✓ Virtual host criado e habilitado${NC}"
fi

# ============================================
# 4. VERIFICAR DNS E PORTAS
# ============================================
echo ""
echo -e "${YELLOW}[4/6] Verificando configurações de rede...${NC}"

# Verificar se o domínio aponta para este servidor
SERVER_IP=$(hostname -I | awk '{print $1}')
DOMAIN_IP=$(dig +short $DOMAIN | tail -1)

if [ -n "$DOMAIN_IP" ]; then
    if [ "$DOMAIN_IP" = "$SERVER_IP" ]; then
        echo -e "${GREEN}✓ DNS configurado corretamente ($DOMAIN -> $SERVER_IP)${NC}"
    else
        echo -e "${YELLOW}⚠ DNS aponta para $DOMAIN_IP, mas este servidor é $SERVER_IP${NC}"
        echo -e "${YELLOW}Certifique-se de que o DNS está correto antes de continuar${NC}"
        read -p "Continuar mesmo assim? (s/n): " CONTINUE
        if [ "$CONTINUE" != "s" ] && [ "$CONTINUE" != "S" ]; then
            exit 1
        fi
    fi
else
    echo -e "${YELLOW}⚠ Não foi possível verificar o DNS. Certifique-se de que o domínio aponta para este servidor${NC}"
    read -p "Continuar mesmo assim? (s/n): " CONTINUE
    if [ "$CONTINUE" != "s" ] && [ "$CONTINUE" != "S" ]; then
        exit 1
    fi
fi

# Verificar se as portas 80 e 443 estão abertas
if netstat -tuln 2>/dev/null | grep -q ":80 " || ss -tuln 2>/dev/null | grep -q ":80 "; then
    echo -e "${GREEN}✓ Porta 80 está aberta${NC}"
else
    echo -e "${YELLOW}⚠ Porta 80 não está aberta${NC}"
fi

if netstat -tuln 2>/dev/null | grep -q ":443 " || ss -tuln 2>/dev/null | grep -q ":443 "; then
    echo -e "${GREEN}✓ Porta 443 está aberta${NC}"
else
    echo -e "${YELLOW}⚠ Porta 443 não está aberta (será aberta automaticamente pelo Certbot)${NC}"
fi

# Verificar firewall
if command -v ufw &> /dev/null && ufw status | grep -q "Status: active"; then
    echo -e "${BLUE}Verificando firewall UFW...${NC}"
    
    if ufw status | grep -q "80/tcp"; then
        echo -e "${GREEN}✓ Porta 80 aberta no firewall${NC}"
    else
        echo -e "${YELLOW}Abrindo porta 80 no firewall...${NC}"
        ufw allow 80/tcp
    fi
    
    if ufw status | grep -q "443/tcp"; then
        echo -e "${GREEN}✓ Porta 443 aberta no firewall${NC}"
    else
        echo -e "${YELLOW}Abrindo porta 443 no firewall...${NC}"
        ufw allow 443/tcp
    fi
fi

# ============================================
# 5. OBTER CERTIFICADO SSL
# ============================================
echo ""
echo -e "${YELLOW}[5/6] Obtendo certificado SSL do Let's Encrypt...${NC}"
echo -e "${BLUE}Isso pode levar alguns minutos...${NC}"
echo ""

# Verificar se www está configurado no DNS
WWW_IP=$(dig +short www.$DOMAIN | tail -1)
if [ -n "$WWW_IP" ] && [ "$WWW_IP" = "$SERVER_IP" ]; then
    echo -e "${GREEN}✓ www.$DOMAIN está configurado no DNS${NC}"
    CERTBOT_DOMAINS="-d $DOMAIN -d www.$DOMAIN"
else
    echo -e "${YELLOW}⚠ www.$DOMAIN não está configurado no DNS${NC}"
    echo -e "${YELLOW}Obtendo certificado apenas para $DOMAIN${NC}"
    CERTBOT_DOMAINS="-d $DOMAIN"
fi

# Executar Certbot
certbot --apache $CERTBOT_DOMAINS --non-interactive --agree-tos --email $EMAIL --redirect

if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}✓ Certificado SSL obtido e configurado com sucesso!${NC}"
else
    echo ""
    echo -e "${RED}✗ Erro ao obter certificado SSL${NC}"
    echo -e "${YELLOW}Possíveis causas:${NC}"
    echo -e "  - DNS não está apontando para este servidor"
    echo -e "  - Porta 80 está bloqueada"
    echo -e "  - Domínio já tem certificado ativo"
    echo ""
    echo -e "${YELLOW}Tente executar manualmente:${NC}"
    echo -e "  certbot --apache -d $DOMAIN -d www.$DOMAIN"
    exit 1
fi

# ============================================
# 6. CONFIGURAR RENOVAÇÃO AUTOMÁTICA
# ============================================
echo ""
echo -e "${YELLOW}[6/6] Configurando renovação automática...${NC}"

# Verificar se já existe cron job
if crontab -l 2>/dev/null | grep -q "certbot renew"; then
    echo -e "${GREEN}✓ Renovação automática já está configurada${NC}"
else
    # Adicionar cron job para renovação (executa duas vezes por dia)
    (crontab -l 2>/dev/null; echo "0 0,12 * * * certbot renew --quiet --deploy-hook 'systemctl reload apache2'") | crontab -
    echo -e "${GREEN}✓ Renovação automática configurada${NC}"
fi

# Testar renovação (dry-run)
echo -e "${BLUE}Testando renovação (dry-run)...${NC}"
certbot renew --dry-run

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Teste de renovação bem-sucedido${NC}"
else
    echo -e "${YELLOW}⚠ Teste de renovação falhou, mas isso pode ser normal${NC}"
fi

# ============================================
# RESUMO
# ============================================
echo ""
echo -e "${GREEN}===========================================${NC}"
echo -e "${GREEN}SSL configurado com sucesso!${NC}"
echo -e "${GREEN}===========================================${NC}"
echo ""
echo -e "${BLUE}Informações:${NC}"
echo -e "  Domínio: $DOMAIN"
echo -e "  HTTPS: https://$DOMAIN"
echo -e "  Certificado: Let's Encrypt (renovação automática)"
echo ""
echo -e "${BLUE}Próximos passos:${NC}"
echo -e "  1. Acesse https://$DOMAIN para verificar"
echo -e "  2. O redirecionamento HTTP -> HTTPS está ativo"
echo -e "  3. O certificado será renovado automaticamente"
echo ""
echo -e "${YELLOW}Comandos úteis:${NC}"
echo -e "  - Ver certificados: certbot certificates"
echo -e "  - Renovar manualmente: certbot renew"
echo -e "  - Revogar certificado: certbot revoke --cert-path /etc/letsencrypt/live/$DOMAIN/cert.pem"
echo ""

