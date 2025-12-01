#!/bin/bash

# ============================================
# Script de Verificação de Instalação
# FinaMassa - Sistema de Gestão
# ============================================

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Contadores
TOTAL_CHECKS=0
PASSED_CHECKS=0
FAILED_CHECKS=0
WARNINGS=0

# Funções auxiliares
print_header() {
    echo ""
    echo -e "${BLUE}===========================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}===========================================${NC}"
}

print_success() {
    echo -e "${GREEN}✓${NC} $1"
    ((PASSED_CHECKS++))
    ((TOTAL_CHECKS++))
}

print_error() {
    echo -e "${RED}✗${NC} $1"
    ((FAILED_CHECKS++))
    ((TOTAL_CHECKS++))
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
    ((WARNINGS++))
    ((TOTAL_CHECKS++))
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

# ============================================
# VERIFICAÇÕES DO APACHE
# ============================================
print_header "VERIFICANDO APACHE"

# Verificar se Apache está instalado
if command -v apache2 &> /dev/null || command -v apachectl &> /dev/null; then
    print_success "Apache está instalado"
    
    # Verificar status do serviço
    if systemctl is-active --quiet apache2; then
        print_success "Apache está rodando"
    else
        print_error "Apache NÃO está rodando (execute: systemctl start apache2)"
    fi
    
    # Verificar se está habilitado para iniciar no boot
    if systemctl is-enabled --quiet apache2; then
        print_success "Apache está habilitado para iniciar no boot"
    else
        print_warning "Apache NÃO está habilitado para iniciar no boot (execute: systemctl enable apache2)"
    fi
    
    # Verificar módulo PHP
    if apache2ctl -M 2>/dev/null | grep -q "php"; then
        PHP_MOD=$(apache2ctl -M 2>/dev/null | grep php | head -1 | awk '{print $1}')
        print_success "Módulo PHP está habilitado ($PHP_MOD)"
    else
        print_error "Módulo PHP NÃO está habilitado (execute: a2enmod php)"
    fi
    
    # Verificar módulo rewrite
    if apache2ctl -M 2>/dev/null | grep -q "rewrite"; then
        print_success "Módulo rewrite está habilitado"
    else
        print_warning "Módulo rewrite NÃO está habilitado (execute: a2enmod rewrite)"
    fi
    
    # Verificar virtual host
    if [ -f /etc/apache2/sites-available/000-default.conf ] || [ -f /etc/apache2/sites-available/finamassa.conf ]; then
        print_success "Virtual host configurado"
    else
        print_warning "Virtual host pode não estar configurado corretamente"
    fi
    
    # Verificar permissões do diretório
    if [ -d /var/www/html/FinaMassa ]; then
        OWNER=$(stat -c '%U' /var/www/html/FinaMassa 2>/dev/null)
        PERMS=$(stat -c '%a' /var/www/html/FinaMassa 2>/dev/null)
        print_info "Diretório FinaMassa: Owner=$OWNER, Permissões=$PERMS"
        
        if [ "$OWNER" = "www-data" ] || [ "$OWNER" = "apache" ]; then
            print_success "Permissões do diretório estão corretas"
        else
            print_warning "Diretório pertence a $OWNER (recomendado: www-data ou apache)"
        fi
    else
        print_error "Diretório /var/www/html/FinaMassa não existe"
    fi
else
    print_error "Apache NÃO está instalado"
fi

# ============================================
# VERIFICAÇÕES DO MARIADB/MYSQL
# ============================================
print_header "VERIFICANDO MARIADB/MYSQL"

# Verificar se MariaDB/MySQL está instalado
if command -v mysql &> /dev/null || command -v mariadb &> /dev/null; then
    DB_CMD="mysql"
    if command -v mariadb &> /dev/null; then
        DB_CMD="mariadb"
    fi
    print_success "MariaDB/MySQL está instalado"
    
    # Verificar status do serviço
    if systemctl is-active --quiet mariadb || systemctl is-active --quiet mysql; then
        print_success "MariaDB/MySQL está rodando"
    else
        print_error "MariaDB/MySQL NÃO está rodando (execute: systemctl start mariadb ou systemctl start mysql)"
    fi
    
    # Verificar se está habilitado para iniciar no boot
    if systemctl is-enabled --quiet mariadb 2>/dev/null || systemctl is-enabled --quiet mysql 2>/dev/null; then
        print_success "MariaDB/MySQL está habilitado para iniciar no boot"
    else
        print_warning "MariaDB/MySQL NÃO está habilitado para iniciar no boot"
    fi
    
    # Tentar conectar ao banco (sem senha, apenas verificar se o serviço responde)
    if $DB_CMD -e "SELECT 1;" &>/dev/null; then
        print_success "Conexão com MariaDB/MySQL funcionando (root sem senha)"
    else
        print_warning "Não foi possível conectar ao banco (pode ser necessário senha)"
    fi
else
    print_error "MariaDB/MySQL NÃO está instalado"
fi

# ============================================
# VERIFICAÇÕES DO PHP
# ============================================
print_header "VERIFICANDO PHP"

# Verificar se PHP está instalado
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n1 | cut -d' ' -f2 | cut -d'.' -f1,2)
    PHP_FULL_VERSION=$(php -v | head -n1 | cut -d' ' -f2)
    print_success "PHP está instalado (versão $PHP_FULL_VERSION)"
    
    # Verificar versão mínima (8.1+)
    PHP_MAJOR=$(echo $PHP_VERSION | cut -d'.' -f1)
    PHP_MINOR=$(echo $PHP_VERSION | cut -d'.' -f2)
    
    if [ "$PHP_MAJOR" -ge 8 ] && [ "$PHP_MINOR" -ge 1 ]; then
        print_success "Versão do PHP é compatível (8.1+)"
    else
        print_error "Versão do PHP é muito antiga (requer 8.1+)"
    fi
    
    # Verificar extensões necessárias
    EXTENSIONS=("mysqli" "pdo_mysql" "mbstring" "curl" "xml" "zip" "gd" "bcmath" "intl" "soap" "opcache")
    
    for ext in "${EXTENSIONS[@]}"; do
        if php -m | grep -qi "^$ext$"; then
            print_success "Extensão $ext está instalada"
        else
            print_error "Extensão $ext NÃO está instalada"
        fi
    done
    
    # Verificar configurações importantes do PHP
    PHP_INI=$(php --ini | grep "Loaded Configuration File" | awk '{print $4}')
    if [ -n "$PHP_INI" ] && [ -f "$PHP_INI" ]; then
        print_info "php.ini: $PHP_INI"
        
        # Verificar upload_max_filesize
        UPLOAD_MAX=$(php -i | grep "upload_max_filesize" | awk '{print $3}')
        print_info "upload_max_filesize: $UPLOAD_MAX"
        
        # Verificar memory_limit
        MEMORY_LIMIT=$(php -i | grep "memory_limit" | awk '{print $3}')
        print_info "memory_limit: $MEMORY_LIMIT"
        
        # Verificar timezone
        TIMEZONE=$(php -i | grep "date.timezone" | grep -v "no value" | awk '{print $3}')
        if [ -n "$TIMEZONE" ]; then
            print_success "Timezone configurado: $TIMEZONE"
        else
            print_warning "Timezone não configurado"
        fi
        
        # Verificar opcache
        if php -m | grep -qi "opcache"; then
            OPCACHE_ENABLED=$(php -i | grep "opcache.enable" | grep -v "CLI" | awk '{print $3}')
            if [ "$OPCACHE_ENABLED" = "1" ]; then
                print_success "OPcache está habilitado"
            else
                print_warning "OPcache está desabilitado"
            fi
        fi
    else
        print_warning "Não foi possível encontrar php.ini"
    fi
else
    print_error "PHP NÃO está instalado"
fi

# ============================================
# VERIFICAÇÕES DO COMPOSER
# ============================================
print_header "VERIFICANDO COMPOSER"

if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version | cut -d' ' -f3)
    print_success "Composer está instalado (versão $COMPOSER_VERSION)"
    
    # Verificar se vendor existe
    if [ -d "/var/www/html/FinaMassa/vendor" ]; then
        print_success "Diretório vendor existe (dependências instaladas)"
    else
        print_warning "Diretório vendor não existe (execute: composer install)"
    fi
else
    print_error "Composer NÃO está instalado"
fi

# ============================================
# VERIFICAÇÕES DO CODEIGNITER
# ============================================
print_header "VERIFICANDO CODEIGNITER"

APP_PATH="/var/www/html/FinaMassa"

if [ -d "$APP_PATH" ]; then
    print_success "Diretório da aplicação existe"
    
    # Verificar arquivo .env
    if [ -f "$APP_PATH/.env" ]; then
        print_success "Arquivo .env existe"
        
        # Verificar configurações importantes no .env
        if grep -q "^CI_ENVIRONMENT" "$APP_PATH/.env"; then
            ENV_VALUE=$(grep "^CI_ENVIRONMENT" "$APP_PATH/.env" | cut -d'=' -f2 | tr -d ' ')
            print_info "CI_ENVIRONMENT: $ENV_VALUE"
        else
            print_warning "CI_ENVIRONMENT não configurado no .env"
        fi
        
        # Verificar configuração do banco de dados
        if grep -q "^database.default.hostname" "$APP_PATH/.env"; then
            DB_HOST=$(grep "^database.default.hostname" "$APP_PATH/.env" | cut -d'=' -f2 | tr -d ' ')
            DB_USER=$(grep "^database.default.username" "$APP_PATH/.env" | cut -d'=' -f2 | tr -d ' ')
            DB_NAME=$(grep "^database.default.database" "$APP_PATH/.env" | cut -d'=' -f2 | tr -d ' ')
            
            if [ -n "$DB_HOST" ] && [ -n "$DB_USER" ] && [ -n "$DB_NAME" ]; then
                print_success "Configuração do banco de dados encontrada no .env"
                print_info "Host: $DB_HOST | User: $DB_USER | Database: $DB_NAME"
                
                # Tentar conectar ao banco usando as credenciais do .env
                DB_PASS=$(grep "^database.default.password" "$APP_PATH/.env" | cut -d'=' -f2 | tr -d ' ')
                
                if [ -n "$DB_PASS" ]; then
                    if mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e "USE $DB_NAME; SELECT 1;" &>/dev/null 2>&1; then
                        print_success "Conexão com o banco de dados funcionando"
                        
                        # Verificar se existem tabelas
                        TABLE_COUNT=$(mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null | wc -l)
                        if [ "$TABLE_COUNT" -gt 1 ]; then
                            print_success "Banco de dados contém $((TABLE_COUNT-1)) tabela(s)"
                        else
                            print_warning "Banco de dados está vazio (execute: php spark migrate)"
                        fi
                    else
                        print_error "Não foi possível conectar ao banco de dados com as credenciais do .env"
                    fi
                else
                    print_warning "Senha do banco não configurada no .env"
                fi
            else
                print_warning "Configuração do banco de dados incompleta no .env"
            fi
        else
            print_warning "Configuração do banco de dados não encontrada no .env"
        fi
    else
        print_error "Arquivo .env não existe (copie de .env.example)"
    fi
    
    # Verificar permissões do diretório writable
    if [ -d "$APP_PATH/writable" ]; then
        WRITABLE_PERMS=$(stat -c '%a' "$APP_PATH/writable" 2>/dev/null)
        WRITABLE_OWNER=$(stat -c '%U' "$APP_PATH/writable" 2>/dev/null)
        
        if [ "$WRITABLE_OWNER" = "www-data" ] || [ "$WRITABLE_OWNER" = "apache" ]; then
            print_success "Permissões do diretório writable estão corretas"
        else
            print_warning "Diretório writable pertence a $WRITABLE_OWNER (recomendado: www-data ou apache)"
        fi
        
        print_info "writable: Owner=$WRITABLE_OWNER, Permissões=$WRITABLE_PERMS"
    else
        print_error "Diretório writable não existe"
    fi
    
    # Verificar se spark está executável
    if [ -f "$APP_PATH/spark" ]; then
        if [ -x "$APP_PATH/spark" ]; then
            print_success "Arquivo spark é executável"
        else
            print_warning "Arquivo spark não é executável (execute: chmod +x spark)"
        fi
    else
        print_error "Arquivo spark não existe"
    fi
else
    print_error "Diretório da aplicação não existe"
fi

# ============================================
# VERIFICAÇÕES DE PERMISSÕES
# ============================================
print_header "VERIFICAÇÕES DE PERMISSÕES"

# Verificar permissões de arquivos importantes
FILES_TO_CHECK=(
    "$APP_PATH/.env"
    "$APP_PATH/writable"
    "$APP_PATH/writable/logs"
    "$APP_PATH/writable/cache"
    "$APP_PATH/writable/session"
    "$APP_PATH/writable/uploads"
)

for file in "${FILES_TO_CHECK[@]}"; do
    if [ -e "$file" ]; then
        PERMS=$(stat -c '%a' "$file" 2>/dev/null)
        OWNER=$(stat -c '%U' "$file" 2>/dev/null)
        
        if [ -d "$file" ]; then
            # Para diretórios, verificar se são graváveis
            if [ -w "$file" ]; then
                print_success "$file: gravável (Owner=$OWNER, Perms=$PERMS)"
            else
                print_warning "$file: pode não ser gravável (Owner=$OWNER, Perms=$PERMS)"
            fi
        else
            print_info "$file: Owner=$OWNER, Perms=$PERMS"
        fi
    fi
done

# ============================================
# VERIFICAÇÕES DE REDE
# ============================================
print_header "VERIFICAÇÕES DE REDE"

# Verificar se Apache está escutando na porta 80
if netstat -tuln 2>/dev/null | grep -q ":80 " || ss -tuln 2>/dev/null | grep -q ":80 "; then
    print_success "Porta 80 está em uso (Apache provavelmente)"
else
    print_warning "Porta 80 não está em uso"
fi

# Verificar se MySQL está escutando na porta 3306
if netstat -tuln 2>/dev/null | grep -q ":3306 " || ss -tuln 2>/dev/null | grep -q ":3306 "; then
    print_success "Porta 3306 está em uso (MySQL/MariaDB)"
else
    print_warning "Porta 3306 não está em uso"
fi

# ============================================
# RESUMO FINAL
# ============================================
print_header "RESUMO DA VERIFICAÇÃO"

echo ""
echo -e "${BLUE}Total de verificações:${NC} $TOTAL_CHECKS"
echo -e "${GREEN}Sucessos:${NC} $PASSED_CHECKS"
echo -e "${RED}Falhas:${NC} $FAILED_CHECKS"
echo -e "${YELLOW}Avisos:${NC} $WARNINGS"
echo ""

if [ $FAILED_CHECKS -eq 0 ]; then
    if [ $WARNINGS -eq 0 ]; then
        echo -e "${GREEN}✓ Todas as verificações passaram! Sistema está configurado corretamente.${NC}"
        exit 0
    else
        echo -e "${YELLOW}⚠ Sistema está funcional, mas há alguns avisos que podem ser corrigidos.${NC}"
        exit 0
    fi
else
    echo -e "${RED}✗ Algumas verificações falharam. Revise os erros acima.${NC}"
    exit 1
fi

