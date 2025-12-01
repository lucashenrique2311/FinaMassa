# Como Configurar SSL (HTTPS) para controlaso.com.br

## Pré-requisitos

Antes de configurar SSL, certifique-se de que:

1. ✅ O domínio `controlaso.com.br` está apontando para o IP do servidor
2. ✅ O Apache está instalado e rodando
3. ✅ As portas 80 (HTTP) e 443 (HTTPS) estão abertas no firewall
4. ✅ Você tem acesso root ao servidor

## Solução Automatizada (Recomendado)

Execute o script como root:

```bash
sudo ./configurar-ssl.sh
```

O script irá:
1. Instalar Certbot (cliente Let's Encrypt)
2. Verificar e configurar Apache
3. Verificar/criar virtual host
4. Verificar DNS e portas
5. Obter certificado SSL automaticamente
6. Configurar renovação automática

## Verificar DNS

Antes de executar, verifique se o DNS está correto:

```bash
# Verificar IP do servidor
hostname -I

# Verificar DNS do domínio
dig +short controlaso.com.br
```

O IP retornado pelo `dig` deve ser o mesmo do servidor.

## Solução Manual

### Passo 1: Instalar Certbot

```bash
sudo apt update
sudo apt install -y certbot python3-certbot-apache
```

### Passo 2: Habilitar módulos do Apache

```bash
sudo a2enmod ssl
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Passo 3: Verificar Virtual Host

Certifique-se de que existe um virtual host para o domínio:

```bash
sudo nano /etc/apache2/sites-available/controlaso.com.br.conf
```

Exemplo de configuração:

```apache
<VirtualHost *:80>
    ServerName controlaso.com.br
    ServerAlias www.controlaso.com.br
    
    DocumentRoot /var/www/html/FinaMassa/public
    
    <Directory /var/www/html/FinaMassa/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/controlaso_error.log
    CustomLog ${APACHE_LOG_DIR}/controlaso_access.log combined
</VirtualHost>
```

Habilitar o site:

```bash
sudo a2ensite controlaso.com.br.conf
sudo systemctl reload apache2
```

### Passo 4: Abrir Portas no Firewall

**UFW:**
```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw status
```

**Firewalld:**
```bash
sudo firewall-cmd --permanent --add-port=80/tcp
sudo firewall-cmd --permanent --add-port=443/tcp
sudo firewall-cmd --reload
```

### Passo 5: Obter Certificado SSL

```bash
sudo certbot --apache -d controlaso.com.br -d www.controlaso.com.br
```

O Certbot irá:
- Fazer perguntas sobre redirecionamento (escolha "2" para redirecionar HTTP para HTTPS)
- Obter o certificado automaticamente
- Configurar o Apache para usar HTTPS

### Passo 6: Configurar Renovação Automática

O Certbot cria automaticamente um cron job, mas você pode verificar:

```bash
sudo certbot renew --dry-run
```

Para adicionar renovação automática manualmente:

```bash
sudo crontab -e
```

Adicione a linha:

```
0 0,12 * * * certbot renew --quiet --deploy-hook 'systemctl reload apache2'
```

## Verificar Configuração

### Testar HTTPS

Acesse no navegador:
```
https://controlaso.com.br
```

Você deve ver o cadeado verde indicando que o SSL está funcionando.

### Verificar Certificado

```bash
# Listar certificados
sudo certbot certificates

# Ver detalhes do certificado
openssl s_client -connect controlaso.com.br:443 -servername controlaso.com.br < /dev/null 2>/dev/null | openssl x509 -noout -dates
```

### Verificar Redirecionamento

Acesse:
```
http://controlaso.com.br
```

Deve redirecionar automaticamente para:
```
https://controlaso.com.br
```

## Configuração Avançada

### Forçar HTTPS no CodeIgniter

Edite o arquivo `.env`:

```bash
nano .env
```

Altere:

```
app.baseURL = 'https://controlaso.com.br/'
app.forceGlobalSecureRequests = true
```

### Configuração de Segurança Adicional

Edite o virtual host SSL:

```bash
sudo nano /etc/apache2/sites-available/controlaso.com.br-le-ssl.conf
```

Adicione headers de segurança:

```apache
<VirtualHost *:443>
    # ... configurações existentes ...
    
    # Headers de segurança
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</VirtualHost>
```

Habilitar módulo headers:

```bash
sudo a2enmod headers
sudo systemctl reload apache2
```

## Troubleshooting

### Erro: "Failed to connect"

**Causa:** DNS não está apontando para o servidor

**Solução:**
```bash
# Verificar DNS
dig +short controlaso.com.br

# Verificar IP do servidor
hostname -I
```

### Erro: "Connection refused"

**Causa:** Porta 80 ou 443 está bloqueada

**Solução:**
```bash
# Verificar se Apache está escutando
sudo netstat -tuln | grep -E ":(80|443)"

# Verificar firewall
sudo ufw status
```

### Erro: "Too many certificates already issued"

**Causa:** Limite de 5 certificados por semana do Let's Encrypt foi atingido

**Solução:** Aguarde alguns dias ou use `--staging` para testes

### Certificado não renova automaticamente

**Solução:**
```bash
# Verificar cron job
sudo crontab -l

# Testar renovação manual
sudo certbot renew --dry-run

# Renovar manualmente
sudo certbot renew
```

### Verificar logs

```bash
# Logs do Certbot
sudo tail -f /var/log/letsencrypt/letsencrypt.log

# Logs do Apache
sudo tail -f /var/log/apache2/error.log
```

## Comandos Úteis

```bash
# Listar certificados
sudo certbot certificates

# Renovar certificado manualmente
sudo certbot renew

# Revogar certificado
sudo certbot revoke --cert-path /etc/letsencrypt/live/controlaso.com.br/cert.pem

# Deletar certificado
sudo certbot delete --cert-name controlaso.com.br

# Testar renovação (dry-run)
sudo certbot renew --dry-run
```

## Renovação Automática

O Certbot configura automaticamente a renovação, mas você pode verificar:

```bash
# Ver cron jobs
sudo crontab -l

# Verificar quando será renovado
sudo certbot certificates
```

Os certificados Let's Encrypt expiram a cada 90 dias e são renovados automaticamente.

## Segurança

### Recomendações:

1. ✅ Use HTTPS em todas as páginas
2. ✅ Configure HSTS (Strict-Transport-Security)
3. ✅ Mantenha o Apache atualizado
4. ✅ Monitore a renovação automática
5. ✅ Use senhas fortes para o servidor

### Verificar Qualidade do SSL

Use ferramentas online:
- [SSL Labs](https://www.ssllabs.com/ssltest/)
- [Security Headers](https://securityheaders.com/)

## Backup

Os certificados ficam em:
```
/etc/letsencrypt/live/controlaso.com.br/
```

Para fazer backup:

```bash
sudo tar -czf ssl-backup-$(date +%Y%m%d).tar.gz /etc/letsencrypt/
```

## Suporte

Se tiver problemas:
1. Verifique os logs: `sudo tail -f /var/log/letsencrypt/letsencrypt.log`
2. Execute o script novamente: `sudo ./configurar-ssl.sh`
3. Consulte a documentação: https://certbot.eff.org/

