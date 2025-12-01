# Verificação Final do SSL

## ✅ Status Atual

SSL configurado com sucesso! O certificado está ativo e funcionando.

## Verificações Finais

### 1. Verificar se HTTPS está funcionando

Acesse no navegador:
```
https://controlaso.com.br
```

Você deve ver:
- ✅ Cadeado verde no navegador
- ✅ URL começa com `https://`
- ✅ Sem avisos de segurança

### 2. Verificar redirecionamento HTTP -> HTTPS

Acesse:
```
http://controlaso.com.br
```

Deve redirecionar automaticamente para:
```
https://controlaso.com.br
```

### 3. Atualizar .env do CodeIgniter

Edite o arquivo `.env`:

```bash
nano .env
```

Certifique-se de que estas linhas estão configuradas:

```
app.baseURL = 'https://controlaso.com.br/'
app.forceGlobalSecureRequests = true
```

**Importante:** 
- Remova o `#` no início das linhas se estiverem comentadas
- Use `https://` (não `http://`)
- Adicione a barra `/` no final da URL

### 4. Limpar cache do CodeIgniter

Após atualizar o `.env`, limpe o cache:

```bash
php spark cache:clear
```

### 5. Verificar certificado

```bash
sudo certbot certificates
```

Deve mostrar:
- ✅ Domínio: controlaso.com.br
- ✅ Expira em: 2026-03-01 (90 dias)
- ✅ Renovação automática configurada

## Comandos Úteis

```bash
# Ver certificados instalados
sudo certbot certificates

# Ver detalhes do certificado
sudo openssl x509 -in /etc/letsencrypt/live/controlaso.com.br/cert.pem -text -noout | grep -A 2 "Validity"

# Testar renovação
sudo certbot renew --dry-run

# Ver logs do Certbot
sudo tail -f /var/log/letsencrypt/letsencrypt.log
```

## Próximos Passos

1. ✅ SSL configurado
2. ⚠️ Atualizar `.env` com `https://`
3. ⚠️ Limpar cache do CodeIgniter
4. ✅ Testar acesso via HTTPS

## Troubleshooting

### Se o site não carregar via HTTPS:

1. Verificar se Apache está rodando:
   ```bash
   sudo systemctl status apache2
   ```

2. Verificar se o virtual host SSL está habilitado:
   ```bash
   sudo apache2ctl -S
   ```

3. Verificar logs do Apache:
   ```bash
   sudo tail -f /var/log/apache2/error.log
   ```

### Se houver erro de "mixed content":

Alguns recursos (CSS, JS, imagens) podem estar sendo carregados via HTTP. Verifique:
- Links no código que usam `http://`
- Configuração do `baseURL` no `.env`
- Assets que não usam `base_url()`

## Renovação Automática

O certificado será renovado automaticamente antes de expirar. Você pode verificar o status:

```bash
sudo systemctl status certbot.timer
```

O certificado Let's Encrypt expira em 90 dias e é renovado automaticamente.

