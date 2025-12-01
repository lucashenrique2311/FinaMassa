#!/bin/bash

# ============================================
# Script para Corrigir Permissões
# FinaMassa - Sistema de Gestão
# ============================================

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

APP_PATH="/var/www/html/FinaMassa"

echo -e "${BLUE}===========================================${NC}"
echo -e "${BLUE}Corrigindo Permissões - FinaMassa${NC}"
echo -e "${BLUE}===========================================${NC}"
echo ""

# Verificar se está rodando como root
if [ "$(id -u)" -ne 0 ]; then 
    echo -e "${RED}Este script precisa ser executado como root${NC}"
    exit 1
fi

# Detectar usuário do Apache
if id "www-data" &>/dev/null; then
    APACHE_USER="www-data"
elif id "apache" &>/dev/null; then
    APACHE_USER="apache"
else
    echo -e "${RED}Usuário do Apache não encontrado (www-data ou apache)${NC}"
    exit 1
fi

echo -e "${BLUE}Usuário do Apache detectado: $APACHE_USER${NC}"
echo ""

# Corrigir dono do diretório principal
echo -e "${YELLOW}Corrigindo dono do diretório principal...${NC}"
chown -R $APACHE_USER:$APACHE_USER "$APP_PATH"
echo -e "${GREEN}✓ Dono do diretório corrigido${NC}"

# Corrigir permissões do diretório writable
echo -e "${YELLOW}Corrigindo permissões do diretório writable...${NC}"
if [ -d "$APP_PATH/writable" ]; then
    chown -R $APACHE_USER:$APACHE_USER "$APP_PATH/writable"
    chmod -R 775 "$APP_PATH/writable"
    echo -e "${GREEN}✓ Permissões do writable corrigidas${NC}"
else
    echo -e "${RED}✗ Diretório writable não encontrado${NC}"
fi

# Corrigir permissões de subdiretórios do writable
echo -e "${YELLOW}Corrigindo permissões dos subdiretórios...${NC}"
for dir in logs cache session uploads debugbar; do
    if [ -d "$APP_PATH/writable/$dir" ]; then
        chown -R $APACHE_USER:$APACHE_USER "$APP_PATH/writable/$dir"
        chmod -R 775 "$APP_PATH/writable/$dir"
        echo -e "${GREEN}✓ Permissões de writable/$dir corrigidas${NC}"
    else
        mkdir -p "$APP_PATH/writable/$dir"
        chown -R $APACHE_USER:$APACHE_USER "$APP_PATH/writable/$dir"
        chmod -R 775 "$APP_PATH/writable/$dir"
        echo -e "${GREEN}✓ Diretório writable/$dir criado e configurado${NC}"
    fi
done

# Tornar spark executável
echo -e "${YELLOW}Corrigindo permissões do arquivo spark...${NC}"
if [ -f "$APP_PATH/spark" ]; then
    chmod +x "$APP_PATH/spark"
    chown $APACHE_USER:$APACHE_USER "$APP_PATH/spark"
    echo -e "${GREEN}✓ Arquivo spark agora é executável${NC}"
else
    echo -e "${RED}✗ Arquivo spark não encontrado${NC}"
fi

# Corrigir permissões do .env (deve ser legível pelo Apache, mas não gravável)
echo -e "${YELLOW}Corrigindo permissões do arquivo .env...${NC}"
if [ -f "$APP_PATH/.env" ]; then
    chown root:$APACHE_USER "$APP_PATH/.env"
    chmod 640 "$APP_PATH/.env"
    echo -e "${GREEN}✓ Permissões do .env corrigidas (root:apache, 640)${NC}"
else
    echo -e "${YELLOW}⚠ Arquivo .env não encontrado${NC}"
fi

# Corrigir permissões de outros arquivos importantes
echo -e "${YELLOW}Corrigindo permissões de arquivos importantes...${NC}"
chown -R $APACHE_USER:$APACHE_USER "$APP_PATH/app"
chown -R $APACHE_USER:$APACHE_USER "$APP_PATH/public"
chmod -R 755 "$APP_PATH/app"
chmod -R 755 "$APP_PATH/public"
echo -e "${GREEN}✓ Permissões de app/ e public/ corrigidas${NC}"

echo ""
echo -e "${GREEN}===========================================${NC}"
echo -e "${GREEN}Permissões corrigidas com sucesso!${NC}"
echo -e "${GREEN}===========================================${NC}"
echo ""
echo -e "${BLUE}Resumo:${NC}"
echo -e "  - Diretório principal: $APACHE_USER:$APACHE_USER"
echo -e "  - Diretório writable: $APACHE_USER:$APACHE_USER (775)"
echo -e "  - Arquivo spark: executável"
echo -e "  - Arquivo .env: root:$APACHE_USER (640)"
echo ""

