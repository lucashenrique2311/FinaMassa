#!/bin/bash

# ============================================
# Script para Executar Limpeza do Banco de Dados
# FinaMassa - Sistema de Gestão
# ============================================

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}===========================================${NC}"
echo -e "${BLUE}Limpeza do Banco de Dados${NC}"
echo -e "${BLUE}===========================================${NC}"
echo ""

# Verificar se está rodando como root
if [ "$(id -u)" -ne 0 ]; then 
    echo -e "${YELLOW}Executando como usuário normal...${NC}"
fi

# Ler configurações do .env
APP_PATH="/var/www/html/FinaMassa"

if [ ! -f "$APP_PATH/.env" ]; then
    echo -e "${RED}✗ Arquivo .env não encontrado${NC}"
    exit 1
fi

# Extrair configurações do banco
DB_HOST=$(grep -E "^[ ]*database.default.hostname" "$APP_PATH/.env" | sed 's/^[ ]*//' | cut -d'=' -f2 | sed "s/^[ ]*'//; s/'[ ]*$//; s/^[ ]*//; s/[ ]*$//")
DB_USER=$(grep -E "^[ ]*database.default.username" "$APP_PATH/.env" | sed 's/^[ ]*//' | cut -d'=' -f2 | sed "s/^[ ]*'//; s/'[ ]*$//; s/^[ ]*//; s/[ ]*$//")
DB_NAME=$(grep -E "^[ ]*database.default.database" "$APP_PATH/.env" | sed 's/^[ ]*//' | cut -d'=' -f2 | sed "s/^[ ]*'//; s/'[ ]*$//; s/^[ ]*//; s/[ ]*$//")
DB_PASS=$(grep -E "^[ ]*database.default.password" "$APP_PATH/.env" | sed 's/^[ ]*//' | cut -d'=' -f2 | sed "s/^[ ]*'//; s/'[ ]*$//; s/^[ ]*//; s/[ ]*$//")

if [ -z "$DB_HOST" ] || [ -z "$DB_USER" ] || [ -z "$DB_NAME" ]; then
    echo -e "${RED}✗ Configurações do banco não encontradas no .env${NC}"
    exit 1
fi

echo -e "${BLUE}Configurações:${NC}"
echo -e "  Host: $DB_HOST"
echo -e "  Database: $DB_NAME"
echo -e "  User: $DB_USER"
echo ""

# Aviso importante
echo -e "${RED}⚠️  ATENÇÃO: Esta operação é DESTRUTIVA!${NC}"
echo -e "${YELLOW}Este script irá:${NC}"
echo -e "  ✗ Remover TODOS os pedidos"
echo -e "  ✗ Remover TODOS os itens de pedidos"
echo -e "  ✗ Remover TODAS as movimentações de estoque"
echo -e "  ✗ Remover TODO o estoque"
echo -e "  ✗ Remover TODOS os produtos"
echo -e "  ✗ Remover TODOS os fornecedores"
echo -e "  ✗ Remover TODOS os depósitos"
echo ""
echo -e "${GREEN}✓ Manterá:${NC}"
echo -e "  ✓ Usuários"
echo -e "  ✓ Permissões"
echo ""

# Confirmar
read -p "Deseja continuar? Digite 'SIM' para confirmar: " CONFIRM

if [ "$CONFIRM" != "SIM" ]; then
    echo -e "${YELLOW}Operação cancelada${NC}"
    exit 0
fi

# Perguntar sobre backup
echo ""
read -p "Deseja fazer backup antes de limpar? (s/n): " BACKUP

if [ "$BACKUP" = "s" ] || [ "$BACKUP" = "S" ]; then
    BACKUP_FILE="backup_${DB_NAME}_$(date +%Y%m%d_%H%M%S).sql"
    echo -e "${YELLOW}Criando backup...${NC}"
    
    if [ -z "$DB_PASS" ]; then
        mysqldump -h"$DB_HOST" -u"$DB_USER" "$DB_NAME" > "$BACKUP_FILE"
    else
        mysqldump -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_FILE"
    fi
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Backup criado: $BACKUP_FILE${NC}"
    else
        echo -e "${RED}✗ Erro ao criar backup${NC}"
        read -p "Continuar mesmo assim? (s/n): " CONTINUE
        if [ "$CONTINUE" != "s" ] && [ "$CONTINUE" != "S" ]; then
            exit 1
        fi
    fi
fi

# Executar limpeza
echo ""
echo -e "${YELLOW}Executando limpeza...${NC}"

if [ -z "$DB_PASS" ]; then
    mysql -h"$DB_HOST" -u"$DB_USER" "$DB_NAME" < "$APP_PATH/limpar-banco-seguro.sql"
else
    mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$APP_PATH/limpar-banco-seguro.sql"
fi

if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}===========================================${NC}"
    echo -e "${GREEN}Limpeza concluída com sucesso!${NC}"
    echo -e "${GREEN}===========================================${NC}"
    echo ""
    echo -e "${BLUE}Dados removidos:${NC}"
    echo -e "  - Pedidos de venda"
    echo -e "  - Itens de pedidos"
    echo -e "  - Movimentações de estoque"
    echo -e "  - Estoque"
    echo -e "  - Produtos"
    echo -e "  - Fornecedores"
    echo -e "  - Depósitos"
    echo ""
    echo -e "${BLUE}Dados mantidos:${NC}"
    echo -e "  - Usuários"
    echo -e "  - Permissões"
    echo ""
else
    echo ""
    echo -e "${RED}✗ Erro ao executar limpeza${NC}"
    exit 1
fi

