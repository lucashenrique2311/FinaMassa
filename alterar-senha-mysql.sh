#!/bin/bash

# ============================================
# Script para Alterar Senha do MySQL/MariaDB
# FinaMassa - Sistema de Gestão
# ============================================

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}===========================================${NC}"
echo -e "${BLUE}Alterar Senha do MySQL/MariaDB${NC}"
echo -e "${BLUE}===========================================${NC}"
echo ""

# Verificar se está rodando como root
if [ "$(id -u)" -ne 0 ]; then 
    echo -e "${RED}Este script precisa ser executado como root${NC}"
    echo -e "${YELLOW}Execute: sudo ./alterar-senha-mysql.sh${NC}"
    exit 1
fi

# Detectar se é MySQL ou MariaDB
if systemctl is-active --quiet mariadb 2>/dev/null; then
    DB_SERVICE="mariadb"
    DB_CMD="mariadb"
    echo -e "${BLUE}MariaDB detectado${NC}"
elif systemctl is-active --quiet mysql 2>/dev/null; then
    DB_SERVICE="mysql"
    DB_CMD="mysql"
    echo -e "${BLUE}MySQL detectado${NC}"
else
    echo -e "${RED}MySQL/MariaDB não está rodando${NC}"
    exit 1
fi

echo ""
echo -e "${YELLOW}Este script irá alterar a senha do usuário root do MySQL/MariaDB${NC}"
echo ""

# Solicitar usuário
read -p "Digite o usuário (padrão: root): " DB_USER
DB_USER=${DB_USER:-root}

# Solicitar senha atual (pode estar vazia)
echo ""
read -sp "Digite a senha atual (deixe em branco se não houver senha): " CURRENT_PASS
echo ""

# Solicitar nova senha
echo ""
read -sp "Digite a nova senha: " NEW_PASS
echo ""

if [ -z "$NEW_PASS" ]; then
    echo -e "${RED}Erro: A nova senha não pode estar vazia${NC}"
    exit 1
fi

# Confirmar nova senha
echo ""
read -sp "Confirme a nova senha: " NEW_PASS_CONFIRM
echo ""

if [ "$NEW_PASS" != "$NEW_PASS_CONFIRM" ]; then
    echo -e "${RED}Erro: As senhas não coincidem${NC}"
    exit 1
fi

echo ""
echo -e "${YELLOW}Alterando senha...${NC}"

# Tentar alterar a senha
if [ -z "$CURRENT_PASS" ]; then
    # Sem senha atual
    $DB_CMD -u"$DB_USER" -e "ALTER USER '$DB_USER'@'localhost' IDENTIFIED BY '$NEW_PASS';" 2>/dev/null
    RESULT=$?
else
    # Com senha atual
    $DB_CMD -u"$DB_USER" -p"$CURRENT_PASS" -e "ALTER USER '$DB_USER'@'localhost' IDENTIFIED BY '$NEW_PASS';" 2>/dev/null
    RESULT=$?
fi

if [ $RESULT -eq 0 ]; then
    echo -e "${GREEN}✓ Senha alterada com sucesso!${NC}"
    
    # Se for root, também alterar para outros hosts
    if [ "$DB_USER" = "root" ]; then
        echo -e "${YELLOW}Alterando senha para root@% (acesso remoto)...${NC}"
        if [ -z "$CURRENT_PASS" ]; then
            $DB_CMD -u"$DB_USER" -e "ALTER USER 'root'@'%' IDENTIFIED BY '$NEW_PASS';" 2>/dev/null
        else
            $DB_CMD -u"$DB_USER" -p"$CURRENT_PASS" -e "ALTER USER 'root'@'%' IDENTIFIED BY '$NEW_PASS';" 2>/dev/null
        fi
        
        # Se o usuário '%' não existir, criar
        if [ $? -ne 0 ]; then
            echo -e "${YELLOW}Criando usuário root@% para acesso remoto...${NC}"
            if [ -z "$CURRENT_PASS" ]; then
                $DB_CMD -u"$DB_USER" -e "CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '$NEW_PASS';" 2>/dev/null
                $DB_CMD -u"$DB_USER" -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;" 2>/dev/null
            else
                $DB_CMD -u"$DB_USER" -p"$CURRENT_PASS" -e "CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '$NEW_PASS';" 2>/dev/null
                $DB_CMD -u"$DB_USER" -p"$CURRENT_PASS" -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;" 2>/dev/null
            fi
        fi
        
        echo -e "${YELLOW}Aplicando privilégios...${NC}"
        if [ -z "$CURRENT_PASS" ]; then
            $DB_CMD -u"$DB_USER" -e "FLUSH PRIVILEGES;" 2>/dev/null
        else
            $DB_CMD -u"$DB_USER" -p"$NEW_PASS" -e "FLUSH PRIVILEGES;" 2>/dev/null
        fi
    fi
    
    echo ""
    echo -e "${GREEN}===========================================${NC}"
    echo -e "${GREEN}Senha alterada com sucesso!${NC}"
    echo -e "${GREEN}===========================================${NC}"
    echo ""
    echo -e "${YELLOW}IMPORTANTE:${NC}"
    echo -e "  - Atualize o arquivo .env com a nova senha:"
    echo -e "    database.default.password = '$NEW_PASS'"
    echo ""
    echo -e "${YELLOW}Para testar a nova senha:${NC}"
    echo -e "  mysql -u$DB_USER -p'$NEW_PASS'"
    echo ""
    
else
    echo -e "${RED}✗ Erro ao alterar senha${NC}"
    echo ""
    echo -e "${YELLOW}Possíveis causas:${NC}"
    echo -e "  1. Senha atual incorreta"
    echo -e "  2. Usuário não existe"
    echo -e "  3. Permissões insuficientes"
    echo ""
    echo -e "${YELLOW}Se você esqueceu a senha do root, use o método de recuperação:${NC}"
    echo -e "  1. Pare o MySQL: systemctl stop $DB_SERVICE"
    echo -e "  2. Inicie em modo seguro: mysqld_safe --skip-grant-tables &"
    echo -e "  3. Conecte sem senha: mysql -u root"
    echo -e "  4. Altere a senha: ALTER USER 'root'@'localhost' IDENTIFIED BY 'nova_senha';"
    echo -e "  5. Reinicie o MySQL: systemctl restart $DB_SERVICE"
    exit 1
fi

