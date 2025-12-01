#!/bin/bash

# ============================================
# Script para Testar Conexão com Banco de Dados
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
echo -e "${BLUE}Testando Conexão com Banco de Dados${NC}"
echo -e "${BLUE}===========================================${NC}"
echo ""

# Verificar se .env existe
if [ ! -f "$APP_PATH/.env" ]; then
    echo -e "${RED}✗ Arquivo .env não encontrado${NC}"
    exit 1
fi

# Ler configurações do .env
DB_HOST=$(grep -E "^[ ]*database.default.hostname" "$APP_PATH/.env" | sed 's/^[ ]*//' | cut -d'=' -f2 | sed "s/^[ ]*'//; s/'[ ]*$//; s/^[ ]*//; s/[ ]*$//")
DB_USER=$(grep -E "^[ ]*database.default.username" "$APP_PATH/.env" | sed 's/^[ ]*//' | cut -d'=' -f2 | sed "s/^[ ]*'//; s/'[ ]*$//; s/^[ ]*//; s/[ ]*$//")
DB_NAME=$(grep -E "^[ ]*database.default.database" "$APP_PATH/.env" | sed 's/^[ ]*//' | cut -d'=' -f2 | sed "s/^[ ]*'//; s/'[ ]*$//; s/^[ ]*//; s/[ ]*$//")
DB_PASS=$(grep -E "^[ ]*database.default.password" "$APP_PATH/.env" | sed 's/^[ ]*//' | cut -d'=' -f2 | sed "s/^[ ]*'//; s/'[ ]*$//; s/^[ ]*//; s/[ ]*$//")

# Verificar se todas as variáveis foram lidas
if [ -z "$DB_HOST" ] || [ -z "$DB_USER" ] || [ -z "$DB_NAME" ]; then
    echo -e "${RED}✗ Configurações do banco não encontradas no .env${NC}"
    echo -e "${YELLOW}Verifique se as seguintes linhas existem:${NC}"
    echo -e "  database.default.hostname = ..."
    echo -e "  database.default.username = ..."
    echo -e "  database.default.database = ..."
    exit 1
fi

echo -e "${BLUE}Configurações encontradas:${NC}"
echo -e "  Host: $DB_HOST"
echo -e "  Usuário: $DB_USER"
echo -e "  Database: $DB_NAME"
echo ""

# Testar conexão
echo -e "${YELLOW}Testando conexão...${NC}"

if [ -z "$DB_PASS" ]; then
    # Tentar sem senha
    if mysql -h"$DB_HOST" -u"$DB_USER" -e "SELECT 1;" &>/dev/null; then
        echo -e "${GREEN}✓ Conexão bem-sucedida (sem senha)${NC}"
        CONNECTED=1
    else
        echo -e "${RED}✗ Falha na conexão (sem senha)${NC}"
        CONNECTED=0
    fi
else
    # Tentar com senha
    if mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e "SELECT 1;" &>/dev/null 2>&1; then
        echo -e "${GREEN}✓ Conexão bem-sucedida (com senha)${NC}"
        CONNECTED=1
    else
        echo -e "${RED}✗ Falha na conexão (verifique usuário/senha)${NC}"
        CONNECTED=0
    fi
fi

if [ $CONNECTED -eq 1 ]; then
    echo ""
    echo -e "${YELLOW}Testando acesso ao banco de dados '$DB_NAME'...${NC}"
    
    if [ -z "$DB_PASS" ]; then
        if mysql -h"$DB_HOST" -u"$DB_USER" -e "USE $DB_NAME; SELECT 1;" &>/dev/null; then
            echo -e "${GREEN}✓ Acesso ao banco '$DB_NAME' bem-sucedido${NC}"
            
            # Contar tabelas
            if [ -z "$DB_PASS" ]; then
                TABLE_COUNT=$(mysql -h"$DB_HOST" -u"$DB_USER" "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null | wc -l)
            else
                TABLE_COUNT=$(mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null | wc -l)
            fi
            
            if [ "$TABLE_COUNT" -gt 1 ]; then
                echo -e "${GREEN}✓ Banco contém $((TABLE_COUNT-1)) tabela(s)${NC}"
                
                # Listar algumas tabelas
                echo ""
                echo -e "${BLUE}Tabelas encontradas:${NC}"
                if [ -z "$DB_PASS" ]; then
                    mysql -h"$DB_HOST" -u"$DB_USER" "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null | tail -n +2 | head -10
                else
                    mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null | tail -n +2 | head -10
                fi
                
                if [ "$TABLE_COUNT" -gt 11 ]; then
                    echo -e "${YELLOW}... e mais $((TABLE_COUNT-11)) tabela(s)${NC}"
                fi
            else
                echo -e "${YELLOW}⚠ Banco está vazio (nenhuma tabela encontrada)${NC}"
                echo -e "${YELLOW}Execute: php spark migrate${NC}"
            fi
        else
            echo -e "${RED}✗ Não foi possível acessar o banco '$DB_NAME'${NC}"
            echo -e "${YELLOW}Verifique se o banco existe e se o usuário tem permissões${NC}"
        fi
    else
        if mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e "USE $DB_NAME; SELECT 1;" &>/dev/null 2>&1; then
            echo -e "${GREEN}✓ Acesso ao banco '$DB_NAME' bem-sucedido${NC}"
            
            # Contar tabelas
            TABLE_COUNT=$(mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null | wc -l)
            
            if [ "$TABLE_COUNT" -gt 1 ]; then
                echo -e "${GREEN}✓ Banco contém $((TABLE_COUNT-1)) tabela(s)${NC}"
                
                # Listar algumas tabelas
                echo ""
                echo -e "${BLUE}Tabelas encontradas:${NC}"
                mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null | tail -n +2 | head -10
                
                if [ "$TABLE_COUNT" -gt 11 ]; then
                    echo -e "${YELLOW}... e mais $((TABLE_COUNT-11)) tabela(s)${NC}"
                fi
            else
                echo -e "${YELLOW}⚠ Banco está vazio (nenhuma tabela encontrada)${NC}"
                echo -e "${YELLOW}Execute: php spark migrate${NC}"
            fi
        else
            echo -e "${RED}✗ Não foi possível acessar o banco '$DB_NAME'${NC}"
            echo -e "${YELLOW}Verifique se o banco existe e se o usuário tem permissões${NC}"
        fi
    fi
fi

echo ""
echo -e "${BLUE}===========================================${NC}"

