# Comando para Configurar SSL

## Problema
O `www.controlaso.com.br` não está configurado no DNS, então precisamos obter o certificado apenas para `controlaso.com.br`.

## Solução Rápida

Execute este comando no servidor:

```bash
certbot --apache -d controlaso.com.br --non-interactive --agree-tos --email lukas123vip29@gmail.com --redirect
```

## Explicação

- `--apache`: Configura automaticamente o Apache
- `-d controlaso.com.br`: Apenas o domínio principal (sem www)
- `--non-interactive`: Não faz perguntas
- `--agree-tos`: Aceita os termos de serviço
- `--email lukas123vip29@gmail.com`: Email para notificações
- `--redirect`: Redireciona HTTP para HTTPS automaticamente

## Se quiser adicionar www depois

Quando configurar o DNS do `www.controlaso.com.br`, você pode adicionar ao certificado:

```bash
certbot --apache -d controlaso.com.br -d www.controlaso.com.br --expand --non-interactive --agree-tos --email lukas123vip29@gmail.com
```

## Verificar se funcionou

Após executar o comando, acesse:
- https://controlaso.com.br

Você deve ver o cadeado verde no navegador.

## Atualizar .env

Depois de configurar SSL, atualize o `.env`:

```bash
nano .env
```

Altere:
```
app.baseURL = 'https://controlaso.com.br/'
app.forceGlobalSecureRequests = true
```

