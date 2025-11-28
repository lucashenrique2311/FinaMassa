# 🚀 Próximos Passos - Sistema SaaS Pizzarias

## ✅ O que já está pronto

- ✅ **Banco de Dados**: 10 tabelas criadas e migradas
- ✅ **Models**: 8 models completos com métodos de busca
- ✅ **Controllers**: 5 controllers com CRUD completo
- ✅ **Rotas**: Todas as rotas configuradas
- ✅ **Estrutura Base**: Header/Footer existentes (mas precisam adaptação para Metronic 9)

## 📋 Próximos Passos - Ordem de Prioridade

### 1️⃣ **Adaptar Layout Base para Metronic 9** (PRIORIDADE ALTA)

**Objetivo**: Criar estrutura base reutilizável com Metronic 9

**Tarefas**:
- [ ] Criar `app/Views/Commons/header_metronic.php` baseado no template Metronic 9
- [ ] Criar `app/Views/Commons/sidebar_metronic.php` com menu lateral
- [ ] Criar `app/Views/Commons/footer_metronic.php` com scripts
- [ ] Adaptar Dashboard para usar novo layout
- [ ] Criar helper para mensagens de sucesso/erro

**Arquivos a criar**:
```
app/Views/Commons/
  ├── header_metronic.php
  ├── sidebar_metronic.php
  ├── footer_metronic.php
  └── layout_metronic.php (wrapper completo)
```

### 2️⃣ **Criar Views dos Cadastros Principais** (PRIORIDADE ALTA)

**Ordem sugerida**:

#### 2.1 Produtos
- [ ] `app/Views/Produtos/index.php` - Listagem com DataTables
- [ ] `app/Views/Produtos/form.php` - Formulário de cadastro/edição
- [ ] Implementar busca e filtros
- [ ] Upload de imagem de produto

#### 2.2 Fornecedores
- [ ] `app/Views/Fornecedores/index.php` - Listagem
- [ ] `app/Views/Fornecedores/form.php` - Formulário completo
- [ ] Integração com API de CEP (opcional)

#### 2.3 Depósitos
- [ ] `app/Views/Depositos/index.php` - Listagem
- [ ] `app/Views/Depositos/form.php` - Formulário

#### 2.4 Estoque
- [ ] `app/Views/Estoque/index.php` - Listagem de estoque
- [ ] `app/Views/Estoque/entrada.php` - Formulário de entrada
- [ ] `app/Views/Estoque/saida.php` - Formulário de saída
- [ ] `app/Views/Estoque/ajuste.php` - Formulário de ajuste
- [ ] `app/Views/Estoque/historico.php` - Histórico de movimentações

#### 2.5 Pedidos
- [ ] `app/Views/Pedidos/index.php` - Listagem de pedidos
- [ ] `app/Views/Pedidos/form.php` - Formulário de novo pedido
- [ ] `app/Views/Pedidos/visualizar.php` - Visualização completa
- [ ] Implementar adição dinâmica de itens (JavaScript)

### 3️⃣ **Melhorar Dashboard** (PRIORIDADE MÉDIA)

- [ ] Adicionar cards com estatísticas principais
- [ ] Gráficos de vendas (últimos 30 dias)
- [ ] Alertas de estoque baixo
- [ ] Lista de pedidos recentes
- [ ] Métricas de vendas do dia/mês

### 4️⃣ **Sistema de Permissões** (PRIORIDADE MÉDIA)

- [ ] Controller de Usuários
- [ ] Controller de Permissões
- [ ] Views de gerenciamento de usuários
- [ ] Views de configuração de permissões
- [ ] Middleware de verificação de permissões

### 5️⃣ **Relatórios** (PRIORIDADE BAIXA)

- [ ] Relatório de Pedidos (filtros por data, status, tipo)
- [ ] Relatório de Estoque (produtos, depósitos, movimentações)
- [ ] Exportação para PDF/Excel
- [ ] Gráficos e análises

### 6️⃣ **Melhorias e Ajustes** (CONTÍNUO)

- [ ] Validações no frontend (JavaScript)
- [ ] Máscaras de input (CNPJ, CPF, telefone, CEP)
- [ ] Upload e gerenciamento de imagens
- [ ] Notificações toast para ações
- [ ] Confirmações de exclusão
- [ ] Paginação nas listagens
- [ ] Busca avançada

## 🎯 Plano de Execução Sugerido

### Semana 1: Layout e Estrutura Base
1. Adaptar layout Metronic 9
2. Criar componentes reutilizáveis
3. Configurar menu lateral
4. Criar helper de mensagens

### Semana 2: Cadastros Básicos
1. Produtos (CRUD completo)
2. Fornecedores (CRUD completo)
3. Depósitos (CRUD completo)

### Semana 3: Operações
1. Estoque (listagem, entrada, saída, ajuste)
2. Histórico de movimentações

### Semana 4: Pedidos e Dashboard
1. Pedidos (CRUD completo)
2. Melhorar Dashboard com estatísticas

### Semana 5: Permissões e Relatórios
1. Sistema de permissões
2. Relatórios básicos

## 🛠️ Tecnologias a Usar no Frontend

- **Template**: Metronic 9 (Tailwind CSS)
- **JavaScript**: Vanilla JS ou jQuery (conforme template)
- **DataTables**: Para listagens (já incluído no Metronic)
- **Select2**: Para selects avançados
- **Toastr/SweetAlert**: Para notificações
- **Chart.js/ApexCharts**: Para gráficos (já incluído no Metronic)

## 📝 Notas Importantes

1. **Multi-tenant**: Todos os dados já estão filtrados por `id_cliente` automaticamente
2. **Sessão**: Controllers já verificam sessão antes de processar
3. **Validações**: Models já têm validações configuradas
4. **APIs**: Endpoints JSON já estão disponíveis para integração

## 🚀 Começar Agora

**Próximo passo imediato**: Criar layout base com Metronic 9

Posso começar criando:
1. Layout base adaptado do Metronic 9
2. Primeira view completa (ex: Produtos)
3. Componentes reutilizáveis

Qual você prefere que eu comece?

