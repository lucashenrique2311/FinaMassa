#!/bin/bash

# ============================================
# Script para Configurar MySQL/MariaDB para Conexões Remotas
# FinaMassa - Sistema de Gestão
# ============================================

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}===========================================${NC}"
echo -e "${BLUE}Configurar MySQL/MariaDB para Conexões Remotas${NC}"
echo -e "${BLUE}===========================================${NC}"
echo ""

# Verificar se está rodando como root
if [ "$(id -u)" -ne 0 ]; then 
    echo -e "${RED}Este script precisa ser executado como root${NC}"
    echo -e "${YELLOW}Execute: sudo ./configurar-mysql-remoto.sh${NC}"
    exit 1
fi

# Detectar se é MySQL ou MariaDB
if systemctl is-active --quiet mariadb 2>/dev/null; then
    DB_SERVICE="mariadb"
    DB_CMD="mariadb"
    CONFIG_FILE="/etc/mysql/mariadb.conf.d/50-server.cnf"
    echo -e "${BLUE}MariaDB detectado${NC}"
elif systemctl is-active --quiet mysql 2>/dev/null; then
    DB_SERVICE="mysql"
    DB_CMD="mysql"
    CONFIG_FILE="/etc/mysql/mysql.conf.d/mysqld.cnf"
    echo -e "${BLUE}MySQL detectado${NC}"
else
    echo -e "${RED}MySQL/MariaDB não está rodando${NC}"
    exit 1
fi

# Solicitar senha do root
echo ""
read -sp "Digite a senha do root do MySQL (deixe em branco se não houver): " ROOT_PASS
echo ""

# Verificar conexão
if [ -z "$ROOT_PASS" ]; then
    if ! $DB_CMD -u root -e "SELECT 1;" &>/dev/null; then
        echo -e "${RED}Erro: Não foi possível conectar ao MySQL${NC}"
        exit 1
    fi
    AUTH_CMD=""
else
    if ! $DB_CMD -u root -p"$ROOT_PASS" -e "SELECT 1;" &>/dev/null 2>&1; then
        echo -e "${RED}Erro: Senha incorreta ou não foi possível conectar ao MySQL${NC}"
        exit 1
    fi
    AUTH_CMD="-p$ROOT_PASS"
fi

echo -e "${GREEN}✓ Conexão com MySQL estabelecida${NC}"
echo ""

# ============================================
# 1. CONFIGURAR BIND-ADDRESS
# ============================================
echo -e "${YELLOW}[1/4] Configurando bind-address...${NC}"

# Verificar se o arquivo de configuração existe
if [ ! -f "$CONFIG_FILE" ]; then
    # Tentar encontrar o arquivo
    CONFIG_FILE=$(find /etc/mysql -name "*.cnf" -type f | grep -E "(mysqld|server)" | head -1)
    if [ -z "$CONFIG_FILE" ]; then
        echo -e "${RED}Erro: Não foi possível encontrar o arquivo de configuração${NC}"
        exit 1
    fi
fi

echo -e "${BLUE}Arquivo de configuração: $CONFIG_FILE${NC}"

# Fazer backup
if [ ! -f "${CONFIG_FILE}.backup" ]; then
    cp "$CONFIG_FILE" "${CONFIG_FILE}.backup"
    echo -e "${GREEN}✓ Backup criado: ${CONFIG_FILE}.backup${NC}"
fi

# Verificar se bind-address existe
if grep -q "^bind-address" "$CONFIG_FILE"; then
    # Comentar a linha atual e adicionar nova
    sed -i 's/^bind-address/#bind-address/' "$CONFIG_FILE"
    echo -e "${GREEN}✓ Linha bind-address comentada${NC}"
fi

# Adicionar bind-address = 0.0.0.0 (aceita conexões de qualquer IP)
if ! grep -q "^bind-address = 0.0.0.0" "$CONFIG_FILE"; then
    # Adicionar após a seção [mysqld]
    sed -i '/^\[mysqld\]/a bind-address = 0.0.0.0' "$CONFIG_FILE"
    echo -e "${GREEN}✓ bind-address = 0.0.0.0 adicionado${NC}"
else
    echo -e "${GREEN}✓ bind-address já está configurado${NC}"
fi

# ============================================
# 2. CRIAR/ATUALIZAR USUÁRIO ROOT PARA ACESSO REMOTO
# ============================================
echo ""
echo -e "${YELLOW}[2/4] Configurando usuário root para acesso remoto...${NC}"

# Solicitar senha para o root remoto (pode ser diferente)
echo ""
read -sp "Digite a senha para root@% (acesso remoto) [Enter para usar a mesma senha do root local]: " REMOTE_PASS
echo ""

if [ -z "$REMOTE_PASS" ]; then
    if [ -z "$ROOT_PASS" ]; then
        echo -e "${RED}Erro: É necessário definir uma senha para acesso remoto${NC}"
        exit 1
    fi
    REMOTE_PASS="$ROOT_PASS"
fi

# Verificar se root@% existe
if [ -z "$ROOT_PASS" ]; then
    USER_EXISTS=$($DB_CMD -u root -e "SELECT User, Host FROM mysql.user WHERE User='root' AND Host='%';" 2>/dev/null | grep -c "%")
else
    USER_EXISTS=$($DB_CMD -u root $AUTH_CMD -e "SELECT User, Host FROM mysql.user WHERE User='root' AND Host='%';" 2>/dev/null | grep -c "%")
fi

if [ "$USER_EXISTS" -eq 0 ]; then
    # Criar usuário root@%
    if [ -z "$ROOT_PASS" ]; then
        $DB_CMD -u root -e "CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '$REMOTE_PASS';" 2>/dev/null
    else
        $DB_CMD -u root $AUTH_CMD -e "CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '$REMOTE_PASS';" 2>/dev/null
    fi
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Usuário root@% criado${NC}"
    else
        echo -e "${RED}✗ Erro ao criar usuário root@%${NC}"
    fi
else
    # Atualizar senha do usuário existente
    if [ -z "$ROOT_PASS" ]; then
        $DB_CMD -u root -e "ALTER USER 'root'@'%' IDENTIFIED BY '$REMOTE_PASS';" 2>/dev/null
    else
        $DB_CMD -u root $AUTH_CMD -e "ALTER USER 'root'@'%' IDENTIFIED BY '$REMOTE_PASS';" 2>/dev/null
    fi
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Senha do usuário root@% atualizada${NC}"
    else
        echo -e "${RED}✗ Erro ao atualizar senha${NC}"
    fi
fi

# ============================================
# 3. CONCEDER PRIVILÉGIOS
# ============================================
echo ""
echo -e "${YELLOW}[3/4] Concedendo privilégios...${NC}"

if [ -z "$ROOT_PASS" ]; then
    $DB_CMD -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;" 2>/dev/null
    $DB_CMD -u root -e "FLUSH PRIVILEGES;" 2>/dev/null
else
    $DB_CMD -u root $AUTH_CMD -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;" 2>/dev/null
    $DB_CMD -u root $AUTH_CMD -e "FLUSH PRIVILEGES;" 2>/dev/null
fi

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Privilégios concedidos${NC}"
else
    echo -e "${RED}✗ Erro ao conceder privilégios${NC}"
fi

# ============================================
# 4. VERIFICAR FIREWALL
# ============================================
echo ""
echo -e "${YELLOW}[4/4] Verificando firewall...${NC}"

# Verificar se ufw está ativo
if command -v ufw &> /dev/null && ufw status | grep -q "Status: active"; then
    echo -e "${BLUE}UFW está ativo${NC}"
    
    # Verificar se a porta 3306 está aberta
    if ufw status | grep -q "3306"; then
        echo -e "${GREEN}✓ Porta 3306 já está aberta no firewall${NC}"
    else
        echo -e "${YELLOW}Porta 3306 não está aberta. Deseja abrir? (s/n)${NC}"
        read -p "> " OPEN_PORT
        
        if [ "$OPEN_PORT" = "s" ] || [ "$OPEN_PORT" = "S" ]; then
            ufw allow 3306/tcp
            echo -e "${GREEN}✓ Porta 3306 aberta no firewall${NC}"
        else
            echo -e "${YELLOW}⚠ Porta 3306 não foi aberta. Você precisará abrir manualmente${NC}"
        fi
    fi
elif command -v firewall-cmd &> /dev/null && systemctl is-active --quiet firewalld; then
    echo -e "${BLUE}Firewalld está ativo${NC}"
    
    if firewall-cmd --list-ports | grep -q "3306"; then
        echo -e "${GREEN}✓ Porta 3306 já está aberta no firewall${NC}"
    else
        echo -e "${YELLOW}Porta 3306 não está aberta. Deseja abrir? (s/n)${NC}"
        read -p "> " OPEN_PORT
        
        if [ "$OPEN_PORT" = "s" ] || [ "$OPEN_PORT" = "S" ]; then
            firewall-cmd --permanent --add-port=3306/tcp
            firewall-cmd --reload
            echo -e "${GREEN}✓ Porta 3306 aberta no firewall${NC}"
        else
            echo -e "${YELLOW}⚠ Porta 3306 não foi aberta. Você precisará abrir manualmente${NC}"
        fi
    fi
else
    echo -e "${GREEN}✓ Nenhum firewall ativo detectado${NC}"
fi

# ============================================
# REINICIAR SERVIÇO
# ============================================
echo ""
echo -e "${YELLOW}Reiniciando MySQL/MariaDB...${NC}"
systemctl restart $DB_SERVICE

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ MySQL/MariaDB reiniciado${NC}"
else
    echo -e "${RED}✗ Erro ao reiniciar MySQL/MariaDB${NC}"
    echo -e "${YELLOW}Verifique os logs: journalctl -u $DB_SERVICE -n 50${NC}"
fi

# ============================================
# RESUMO
# ============================================
echo ""
echo -e "${GREEN}===========================================${NC}"
echo -e "${GREEN}Configuração concluída!${NC}"
echo -e "${GREEN}===========================================${NC}"
echo ""
echo -e "${BLUE}Resumo das alterações:${NC}"
echo -e "  ✓ bind-address configurado para 0.0.0.0"
echo -e "  ✓ Usuário root@% criado/atualizado"
echo -e "  ✓ Privilégios concedidos"
echo -e "  ✓ MySQL/MariaDB reiniciado"
echo ""
echo -e "${YELLOW}Informações para conexão remota:${NC}"
echo -e "  Host: $(hostname -I | awk '{print $1}')"
echo -e "  Port: 3306"
echo -e "  User: root"
echo -e "  Password: $REMOTE_PASS"
echo ""
echo -e "${YELLOW}IMPORTANTE:${NC}"
echo -e "  - Se estiver usando um servidor na nuvem, verifique também"
echo -e "    as regras de firewall do provedor (AWS Security Groups, etc.)"
echo -e "  - Para maior segurança, considere criar um usuário específico"
echo -e "    para a aplicação ao invés de usar root"
echo ""

