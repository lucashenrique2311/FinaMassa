# Como Configurar MySQL/MariaDB para Conexões Remotas

## Problema

Ao tentar conectar via MySQL Workbench ou outro cliente remoto, você recebe o erro:
```
Failed to connect to MySQL at 159.195.56.32 with user root
Unable to connect localhost
```

## Solução Automatizada (Recomendado)

Execute o script como root:

```bash
sudo ./configurar-mysql-remoto.sh
```

O script irá:
1. Configurar o `bind-address` para aceitar conexões remotas
2. Criar/atualizar o usuário `root@%` para acesso remoto
3. Conceder privilégios necessários
4. Verificar e configurar o firewall
5. Reiniciar o MySQL/MariaDB

## Solução Manual

### Passo 1: Configurar bind-address

Edite o arquivo de configuração do MySQL:

**Para MariaDB:**
```bash
sudo nano /etc/mysql/mariadb.conf.d/50-server.cnf
```

**Para MySQL:**
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Encontre a linha:
```
bind-address = 127.0.0.1
```

Altere para:
```
bind-address = 0.0.0.0
```

Ou comente a linha:
```
# bind-address = 127.0.0.1
bind-address = 0.0.0.0
```

### Passo 2: Criar usuário para acesso remoto

Conecte ao MySQL:
```bash
mysql -u root -p
```

Execute os seguintes comandos SQL:

```sql
-- Criar usuário root para acesso remoto (ou atualizar se já existir)
CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY 'SuaSenhaSegura123!';

-- Conceder todos os privilégios
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;

-- Aplicar as mudanças
FLUSH PRIVILEGES;

-- Verificar usuários criados
SELECT User, Host FROM mysql.user WHERE User='root';
```

### Passo 3: Reiniciar o MySQL

```bash
sudo systemctl restart mariadb
# ou
sudo systemctl restart mysql
```

### Passo 4: Configurar Firewall

**Se estiver usando UFW:**
```bash
sudo ufw allow 3306/tcp
sudo ufw status
```

**Se estiver usando Firewalld:**
```bash
sudo firewall-cmd --permanent --add-port=3306/tcp
sudo firewall-cmd --reload
```

**Se estiver usando iptables:**
```bash
sudo iptables -A INPUT -p tcp --dport 3306 -j ACCEPT
sudo iptables-save
```

### Passo 5: Verificar se está funcionando

Teste localmente primeiro:
```bash
mysql -u root -p -h 127.0.0.1
```

Depois teste de outro servidor:
```bash
mysql -u root -p -h 159.195.56.32
```

## Verificar Configuração

### Verificar bind-address
```bash
sudo grep bind-address /etc/mysql/mariadb.conf.d/50-server.cnf
# ou
sudo grep bind-address /etc/mysql/mysql.conf.d/mysqld.cnf
```

### Verificar usuários
```bash
mysql -u root -p -e "SELECT User, Host FROM mysql.user WHERE User='root';"
```

### Verificar se está escutando na porta 3306
```bash
sudo netstat -tuln | grep 3306
# ou
sudo ss -tuln | grep 3306
```

Deve mostrar algo como:
```
tcp  0  0 0.0.0.0:3306  0.0.0.0:*  LISTEN
```

## Firewall do Provedor (Cloud)

Se você estiver usando um servidor na nuvem (AWS, DigitalOcean, etc.), também precisa configurar o firewall do provedor:

### AWS (EC2)
- Vá em Security Groups
- Adicione regra de entrada: Porta 3306, Protocolo TCP, Source: Seu IP ou 0.0.0.0/0

### DigitalOcean
- Vá em Networking > Firewalls
- Adicione regra de entrada: Porta 3306, Protocolo TCP

### Outros provedores
- Procure por "Firewall", "Security Groups" ou "Network Rules"
- Adicione regra para permitir porta 3306 TCP

## Segurança

⚠️ **ATENÇÃO**: Permitir acesso remoto ao MySQL como root pode ser um risco de segurança!

### Recomendações:

1. **Use senhas fortes**
   ```sql
   ALTER USER 'root'@'%' IDENTIFIED BY 'SenhaMuitoForte123!@#';
   ```

2. **Crie um usuário específico para a aplicação** (ao invés de usar root)
   ```sql
   CREATE USER 'finamassa'@'%' IDENTIFIED BY 'SenhaSegura123!';
   GRANT ALL PRIVILEGES ON finamassa.* TO 'finamassa'@'%';
   FLUSH PRIVILEGES;
   ```

3. **Restrinja o acesso por IP** (se possível)
   ```sql
   CREATE USER 'root'@'SEU_IP_AQUI' IDENTIFIED BY 'SenhaSegura123!';
   GRANT ALL PRIVILEGES ON *.* TO 'root'@'SEU_IP_AQUI';
   FLUSH PRIVILEGES;
   ```

4. **Use SSL para conexões remotas** (recomendado para produção)

5. **Considere usar um túnel SSH** ao invés de expor a porta 3306 diretamente

## Testar Conexão

### Via MySQL Workbench

1. Abra o MySQL Workbench
2. Clique em "+" para adicionar nova conexão
3. Configure:
   - **Connection Name**: FinaMassa Remoto
   - **Hostname**: 159.195.56.32
   - **Port**: 3306
   - **Username**: root
   - **Password**: [sua senha]
4. Clique em "Test Connection"
5. Se funcionar, clique em "OK"

### Via linha de comando

```bash
mysql -u root -p -h 159.195.56.32
```

## Troubleshooting

### Erro: "Access denied for user 'root'@'IP'"

- Verifique se o usuário `root@%` existe
- Verifique se a senha está correta
- Verifique se os privilégios foram concedidos

### Erro: "Can't connect to MySQL server"

- Verifique se o MySQL está rodando: `sudo systemctl status mariadb`
- Verifique o bind-address: `sudo grep bind-address /etc/mysql/*/mysqld.cnf`
- Verifique o firewall local: `sudo ufw status`
- Verifique o firewall do provedor (AWS, etc.)
- Verifique se a porta está aberta: `sudo netstat -tuln | grep 3306`

### Erro: "Connection timeout"

- Verifique o firewall do servidor
- Verifique o firewall do provedor (AWS Security Groups, etc.)
- Verifique se o MySQL está escutando na porta correta

## Reverter Mudanças

Se precisar reverter para aceitar apenas conexões locais:

```bash
# Editar arquivo de configuração
sudo nano /etc/mysql/mariadb.conf.d/50-server.cnf
# ou
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# Alterar para:
bind-address = 127.0.0.1

# Reiniciar
sudo systemctl restart mariadb
```

