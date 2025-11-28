# Plano de Desenvolvimento - Sistema SaaS para Pizzarias

## 📋 Visão Geral
Sistema SaaS desenvolvido em CodeIgniter 4, MySQL e template Metronic 9 para gestão completa de pizzarias, incluindo controle de estoque, vendas, fornecedores e relatórios.

## ✅ Backend - Concluído

### 1. Migrations (Banco de Dados)
Todas as tabelas principais foram criadas:

- ✅ **usuarios** - Cadastro de usuários do sistema
- ✅ **fornecedores** - Cadastro de fornecedores
- ✅ **depositos** - Cadastro de depósitos/estoques
- ✅ **produtos** - Cadastro de produtos
- ✅ **estoque** - Controle de estoque por produto e depósito
- ✅ **movimentacoes_estoque** - Histórico de movimentações (entrada, saída, ajuste)
- ✅ **pedidos_venda** - Cadastro de pedidos de venda
- ✅ **itens_pedido** - Itens dos pedidos
- ✅ **permissoes** - Sistema de permissões
- ✅ **usuario_permissoes** - Relação usuário x permissões

**Todas as tabelas incluem:**
- Suporte multi-tenant (id_cliente para SaaS)
- Soft deletes (deleted_at)
- Timestamps (created_at, updated_at)
- Índices apropriados

### 2. Models
Todos os Models principais foram criados com métodos de busca e manipulação:

- ✅ **ProdutoModel** - CRUD de produtos, busca por categoria, filtros
- ✅ **FornecedorModel** - CRUD de fornecedores
- ✅ **DepositoModel** - CRUD de depósitos
- ✅ **EstoqueModel** - Controle de estoque, atualização automática
- ✅ **MovimentacaoEstoqueModel** - Histórico de movimentações
- ✅ **PedidoVendaModel** - CRUD de pedidos, geração automática de número
- ✅ **ItemPedidoModel** - Gerenciamento de itens do pedido
- ✅ **PermissaoModel** - Sistema de permissões

**Características dos Models:**
- Filtragem automática por cliente (multi-tenant)
- Validações configuradas
- Soft deletes habilitado onde aplicável
- Métodos auxiliares para cálculos e buscas

### 3. Controllers
Controllers completos com CRUD e funcionalidades específicas:

- ✅ **Produtos** - Listagem, cadastro, edição, exclusão, API
- ✅ **Fornecedores** - CRUD completo
- ✅ **Depositos** - CRUD completo
- ✅ **Estoque** - Listagem, entrada, saída, ajuste, histórico
- ✅ **Pedidos** - CRUD completo, atualização de status

**Funcionalidades dos Controllers:**
- Validação de sessão
- Filtros e buscas
- Mensagens de sucesso/erro
- APIs JSON para integração

### 4. Rotas
Todas as rotas foram configuradas em `app/Config/Routes.php`:

- ✅ Rotas de login
- ✅ Rotas de dashboard
- ✅ Rotas de produtos
- ✅ Rotas de fornecedores
- ✅ Rotas de depósitos
- ✅ Rotas de estoque
- ✅ Rotas de pedidos

## 🚧 Próximos Passos - Backend

### 1. Sistema de Permissões (Pendente)
- [ ] Criar Controller de Permissões
- [ ] Criar Controller de Usuários
- [ ] Implementar middleware de verificação de permissões
- [ ] Criar seeders com permissões padrão

### 2. Relatórios
- [ ] Controller de Relatórios de Pedidos
- [ ] Controller de Relatórios de Estoque
- [ ] Métodos para exportação (PDF, Excel)
- [ ] Filtros avançados de relatórios

### 3. Dashboard
- [ ] Métodos no Dashboard para estatísticas
- [ ] Gráficos e métricas principais
- [ ] Alertas de estoque baixo

### 4. Melhorias
- [ ] Validação de CNPJ/CPF
- [ ] Upload de imagens de produtos
- [ ] Cálculo automático de custo médio ponderado
- [ ] Integração com APIs de CEP

## 🎨 Frontend - A Fazer

### 1. Views Base
- [ ] Criar layout base com Metronic 9
- [ ] Header e Sidebar reutilizáveis
- [ ] Componentes comuns (tabelas, formulários, modais)

### 2. Telas de Cadastro
- [ ] Produtos (listagem e formulário)
- [ ] Fornecedores (listagem e formulário)
- [ ] Depósitos (listagem e formulário)
- [ ] Usuários (listagem e formulário)
- [ ] Permissões (configuração)

### 3. Telas de Operação
- [ ] Estoque (listagem, entrada, saída, ajuste)
- [ ] Pedidos (listagem, novo pedido, visualização)
- [ ] Histórico de movimentações

### 4. Relatórios
- [ ] Relatório de Pedidos
- [ ] Relatório de Estoque
- [ ] Dashboard com gráficos

## 📊 Estrutura do Banco de Dados

### Relacionamentos Principais:
- `usuarios` → `id_cliente` (multi-tenant)
- `produtos` → `id_cliente`
- `fornecedores` → `id_cliente`
- `depositos` → `id_cliente`
- `estoque` → `id_produto`, `id_deposito`
- `movimentacoes_estoque` → `id_produto`, `id_deposito`, `id_fornecedor`, `id_pedido_venda`
- `pedidos_venda` → `id_cliente`, `id_usuario`
- `itens_pedido` → `id_pedido`, `id_produto`
- `usuario_permissoes` → `id_usuario`, `id_permissao`

## 🔧 Como Executar as Migrations

```bash
# Executar todas as migrations
php spark migrate

# Reverter última migration
php spark migrate:rollback

# Ver status das migrations
php spark migrate:status
```

## 📝 Notas Importantes

1. **Multi-tenant**: Todos os Models filtram automaticamente por `id_cliente` da sessão
2. **Soft Deletes**: Produtos, Fornecedores, Depósitos e Pedidos usam soft delete
3. **Validações**: Configuradas nos Models, podem ser expandidas
4. **Sessão**: Controllers verificam sessão antes de processar
5. **Custo Médio**: Estoque calcula automaticamente custo médio ponderado nas entradas

## 🎯 Funcionalidades Implementadas

### Produtos
- ✅ CRUD completo
- ✅ Busca e filtros
- ✅ Categorias
- ✅ Controle de estoque mínimo
- ✅ Unidades de medida

### Fornecedores
- ✅ CRUD completo
- ✅ Dados completos (CNPJ, CPF, endereço)
- ✅ Busca avançada

### Depósitos
- ✅ CRUD completo
- ✅ Múltiplos depósitos por cliente

### Estoque
- ✅ Controle por produto e depósito
- ✅ Entrada de estoque (com custo médio)
- ✅ Saída de estoque
- ✅ Ajuste de estoque
- ✅ Histórico de movimentações
- ✅ Alerta de estoque baixo

### Pedidos
- ✅ CRUD completo
- ✅ Geração automática de número
- ✅ Múltiplos itens
- ✅ Cálculo automático de totais
- ✅ Controle de status
- ✅ Tipos de pedido (Balcão, Delivery, Retirada)

## 📚 Próximas Implementações Sugeridas

1. **Autenticação melhorada** - JWT ou tokens
2. **API RESTful** - Para integrações externas
3. **Notificações** - Alertas de estoque baixo, pedidos novos
4. **Integração com delivery** - iFood, Rappi, etc
5. **App mobile** - Para entregadores
6. **Relatórios avançados** - Análise de vendas, lucratividade
7. **Controle financeiro** - Contas a pagar/receber
8. **Cardápio online** - Para clientes fazerem pedidos

