# Como Alterar a Senha do MySQL/MariaDB

## Método 1: Usando o Script Automatizado (Recomendado)

Execute o script como root:

```bash
sudo ./alterar-senha-mysql.sh
```

O script irá:
1. Solicitar o usuário (padrão: root)
2. Solicitar a senha atual (pode deixar em branco se não houver)
3. Solicitar a nova senha
4. Confirmar a nova senha
5. Alterar a senha automaticamente

## Método 2: Manualmente via MySQL

### Se você conhece a senha atual:

```bash
mysql -u root -p
```

Depois execute:
```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY 'NovaSenha123!';
FLUSH PRIVILEGES;
```

### Se você NÃO conhece a senha atual (Recuperação):

**Passo 1:** Pare o MySQL/MariaDB
```bash
sudo systemctl stop mariadb
# ou
sudo systemctl stop mysql
```

**Passo 2:** Inicie o MySQL em modo seguro (sem verificação de senha)
```bash
sudo mysqld_safe --skip-grant-tables --skip-networking &
```

**Passo 3:** Conecte sem senha
```bash
mysql -u root
```

**Passo 4:** Altere a senha
```sql
USE mysql;
ALTER USER 'root'@'localhost' IDENTIFIED BY 'NovaSenha123!';
FLUSH PRIVILEGES;
EXIT;
```

**Passo 5:** Pare o MySQL em modo seguro e reinicie normalmente
```bash
sudo pkill mysqld
sudo systemctl start mariadb
# ou
sudo systemctl start mysql
```

**Passo 6:** Teste a nova senha
```bash
mysql -u root -p
```

## Método 3: Usando mysqladmin

```bash
mysqladmin -u root -p password 'NovaSenha123!'
```

## Atualizar o arquivo .env

Após alterar a senha, **NÃO ESQUEÇA** de atualizar o arquivo `.env`:

```bash
nano .env
```

Altere a linha:
```
database.default.password = 'NovaSenha123!'
```

## Verificar se funcionou

Teste a conexão:
```bash
./testar-banco.sh
```

## Dicas de Segurança

1. **Use senhas fortes**: Mínimo de 12 caracteres, com letras maiúsculas, minúsculas, números e símbolos
2. **Não compartilhe senhas**: Cada ambiente deve ter sua própria senha
3. **Mantenha o .env seguro**: O arquivo `.env` não deve ser commitado no Git (já está no .gitignore)
4. **Use usuários específicos**: Em produção, crie usuários específicos para a aplicação, não use root

## Exemplo de senha forte

```
FinaMassa2025@Produção
```

Ou gere uma senha aleatória:
```bash
openssl rand -base64 32
```

