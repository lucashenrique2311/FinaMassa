# Como Limpar o Banco de Dados

## ⚠️ ATENÇÃO

Este script é **DESTRUTIVO** e irá remover **TODOS** os dados operacionais do sistema!

## O que será REMOVIDO:

- ❌ Pedidos de venda
- ❌ Itens de pedidos
- ❌ Movimentações de estoque
- ❌ Estoque
- ❌ Produtos
- ❌ Fornecedores
- ❌ Depósitos
- ❌ Composições de produtos

## O que será MANTIDO:

- ✅ Usuários
- ✅ Permissões
- ✅ Usuário-Permissões
- ✅ Categorias de produtos
- ✅ Ingredientes padrão
- ✅ Tabela de migrations

## Método 1: Script Automatizado (Recomendado)

Execute o script que faz backup e limpeza:

```bash
./executar-limpeza.sh
```

O script irá:
1. Ler configurações do `.env`
2. Perguntar se deseja fazer backup
3. Criar backup (se solicitado)
4. Executar limpeza
5. Mostrar resumo

## Método 2: Manual via MySQL

### Passo 1: Fazer Backup (IMPORTANTE!)

```bash
# Ler configurações do .env primeiro
# Depois executar:
mysqldump -h[host] -u[usuario] -p[nome_banco] > backup_$(date +%Y%m%d_%H%M%S).sql
```

Exemplo:
```bash
mysqldump -h159.195.56.32 -uroot -p finamassa > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Passo 2: Executar Script SQL

```bash
mysql -h[host] -u[usuario] -p[nome_banco] < limpar-banco-seguro.sql
```

Exemplo:
```bash
mysql -h159.195.56.32 -uroot -p finamassa < limpar-banco-seguro.sql
```

## Método 3: Via MySQL Workbench

1. Abra o MySQL Workbench
2. Conecte ao banco
3. Abra o arquivo `limpar-banco-seguro.sql`
4. Execute o script (Ctrl+Shift+Enter)

## Método 4: Via phpMyAdmin

1. Acesse o phpMyAdmin
2. Selecione o banco de dados
3. Vá em "SQL"
4. Cole o conteúdo do arquivo `limpar-banco-seguro.sql`
5. Clique em "Executar"

## Restaurar Backup

Se precisar restaurar o backup:

```bash
mysql -h[host] -u[usuario] -p[nome_banco] < backup_arquivo.sql
```

## Verificar Limpeza

Após executar, verifique:

```sql
-- Verificar contagem de registros
SELECT 
    (SELECT COUNT(*) FROM pedidos_venda) AS pedidos,
    (SELECT COUNT(*) FROM itens_pedido) AS itens,
    (SELECT COUNT(*) FROM produtos) AS produtos,
    (SELECT COUNT(*) FROM estoque) AS estoque,
    (SELECT COUNT(*) FROM usuarios) AS usuarios;
```

Todos devem estar em 0, exceto `usuarios`.

## Scripts Disponíveis

1. **limpar-banco.sql** - Versão simples (TRUNCATE)
2. **limpar-banco-seguro.sql** - Versão segura (DELETE com verificações)
3. **executar-limpeza.sh** - Script bash automatizado

## Dicas

- ⚠️ **SEMPRE** faça backup antes de limpar
- ✅ Teste primeiro em ambiente de desenvolvimento
- 📝 Mantenha backups organizados por data
- 🔒 Proteja os scripts de limpeza (não deixe acessíveis publicamente)

## Troubleshooting

### Erro: "Table doesn't exist"

Algumas tabelas podem não existir. Use a versão segura (`limpar-banco-seguro.sql`) que verifica antes de limpar.

### Erro: "Foreign key constraint fails"

O script desabilita temporariamente as verificações de chaves estrangeiras. Se ainda der erro, verifique se todas as tabelas existem.

### Erro de permissão

Certifique-se de que o usuário do MySQL tem permissões para:
- DELETE
- TRUNCATE
- ALTER TABLE

## Segurança

⚠️ **NUNCA** exponha estes scripts publicamente na web!

Mantenha-os apenas no servidor e com permissões restritas:

```bash
chmod 600 limpar-banco*.sql
chmod 700 executar-limpeza.sh
```

