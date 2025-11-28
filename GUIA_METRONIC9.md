# 📘 Guia de Padrões - Metronic 9 Template

## 🎨 Estrutura Base

### HTML Base
```html
<!DOCTYPE html>
<html class="h-full" data-theme="true" data-theme-mode="light" dir="ltr" lang="en">
<head>
  <base href="../">
  <link href="assets/vendors/apexcharts/apexcharts.css" rel="stylesheet"/>
  <link href="assets/vendors/keenicons/styles.bundle.css" rel="stylesheet"/>
  <link href="assets/css/styles.css" rel="stylesheet"/>
</head>
<body class="antialiased flex h-full text-base text-gray-700 [--tw-page-bg:#F6F6F9] ...">
```

### Layout Principal
```html
<div class="flex grow">
  <!-- Sidebar -->
  <div class="fixed top-0 bottom-0 z-20 ... w-[--tw-sidebar-width]" id="sidebar">
    <!-- Sidebar Header -->
    <!-- Sidebar Menu -->
  </div>
  
  <!-- Content -->
  <div class="flex flex-col grow shrink-0 min-w-0">
    <!-- Toolbar -->
    <!-- Container -->
  </div>
</div>
```

## 📊 Tabelas (DataTables)

### Estrutura Básica
```html
<div class="card card-grid min-w-full">
  <div class="card-header py-5 flex-wrap">
    <h3 class="card-title">Título da Tabela</h3>
    <div class="flex gap-5">
      <a class="btn btn-sm btn-primary" href="#">
        Adicionar
      </a>
    </div>
  </div>
  <div class="card-body">
    <div data-datatable="true" data-datatable-page-size="10">
      <div class="scrollable-x-auto">
        <table class="table table-auto table-border" data-datatable-table="true">
          <thead>
            <tr>
              <th class="w-[60px]">
                <input class="checkbox checkbox-sm" data-datatable-check="true" type="checkbox"/>
              </th>
              <th class="min-w-[250px]">
                <span class="sort asc">
                  <span class="sort-label text-gray-700 text-2sm font-normal">
                    Coluna
                  </span>
                  <span class="sort-icon"></span>
                </span>
              </th>
              <th class="w-[60px]"></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="1"/>
              </td>
              <td>Conteúdo</td>
              <td>
                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                  <i class="ki-filled ki-notepad-edit"></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
```

### Card Header com Busca e Filtros
```html
<div class="card-header flex-wrap gap-2">
  <h3 class="card-title font-medium text-sm">
    Mostrando 10 de 49 registros
  </h3>
  <div class="flex flex-wrap gap-2 lg:gap-5">
    <!-- Busca -->
    <div class="flex">
      <label class="input input-sm">
        <i class="ki-filled ki-magnifier"></i>
        <input placeholder="Buscar..." type="text" value=""/>
      </label>
    </div>
    <!-- Filtros -->
    <div class="flex flex-wrap gap-2.5">
      <select class="select select-sm w-28">
        <option value="1">Ativo</option>
        <option value="2">Inativo</option>
      </select>
      <button class="btn btn-sm btn-outline btn-primary">
        <i class="ki-filled ki-setting-4"></i>
        Filtros
      </button>
    </div>
  </div>
</div>
```

## 🔘 Botões

### Botões Principais
```html
<!-- Botão Primário -->
<a class="btn btn-sm btn-primary" href="#">
  Adicionar
</a>

<!-- Botão Secundário -->
<button class="btn btn-sm btn-light">Cancelar</button>

<!-- Botão Outline -->
<button class="btn btn-sm btn-outline btn-primary">Filtros</button>

<!-- Botão com Ícone -->
<a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
  <i class="ki-filled ki-notepad-edit"></i>
</a>

<!-- Botão de Excluir -->
<a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
  <i class="ki-filled ki-trash"></i>
</a>
```

### Tamanhos de Botões
- `btn-sm` - Pequeno
- `btn` - Médio (padrão)
- `btn-lg` - Grande

### Variantes de Botões
- `btn-primary` - Azul primário
- `btn-light` - Cinza claro
- `btn-dark` - Escuro
- `btn-outline` - Outline
- `btn-clear` - Sem background

## 📝 Formulários

### Input Básico
```html
<label class="input">
  <span class="input-label">Label</span>
  <input type="text" placeholder="Digite..." value=""/>
</label>
```

### Input com Ícone
```html
<label class="input">
  <i class="ki-filled ki-magnifier"></i>
  <input type="text" placeholder="Buscar..." value=""/>
</label>
```

### Input Pequeno
```html
<label class="input input-sm">
  <input type="text" placeholder="Buscar..." value=""/>
</label>
```

### Select
```html
<select class="select select-sm w-28">
  <option value="1">Opção 1</option>
  <option value="2">Opção 2</option>
</select>
```

### Textarea
```html
<label class="textarea">
  <span class="textarea-label">Descrição</span>
  <textarea rows="4" placeholder="Digite a descrição..."></textarea>
</label>
```

### Checkbox
```html
<label class="checkbox-group">
  <input class="checkbox checkbox-sm" type="checkbox" value="1"/>
  <span class="checkbox-label">Lembrar-me</span>
</label>
```

## 🎴 Cards

### Card Básico
```html
<div class="card card-grid min-w-full">
  <div class="card-header">
    <h3 class="card-title">Título do Card</h3>
  </div>
  <div class="card-body">
    <!-- Conteúdo -->
  </div>
</div>
```

### Card com Ações no Header
```html
<div class="card card-grid min-w-full">
  <div class="card-header py-5 flex-wrap">
    <h3 class="card-title">Título</h3>
    <div class="flex gap-5">
      <a class="btn btn-sm btn-primary" href="#">Adicionar</a>
    </div>
  </div>
  <div class="card-body">
    <!-- Conteúdo -->
  </div>
</div>
```

## 🎯 Ícones (Keenicons)

### Uso Básico
```html
<i class="ki-filled ki-home-3"></i>
```

### Ícones Comuns
- `ki-home-3` - Home
- `ki-magnifier` - Busca
- `ki-notepad-edit` - Editar
- `ki-trash` - Excluir
- `ki-setting-4` - Configurações
- `ki-down` - Seta para baixo
- `ki-up` - Seta para cima
- `ki-menu` - Menu
- `ki-profile-circle` - Perfil
- `ki-users` - Usuários
- `ki-security-user` - Segurança

## 📱 Sidebar/Menu

### Estrutura do Menu
```html
<div class="menu flex flex-col w-full gap-1.5 px-3.5" data-menu="true">
  <div class="menu-item">
    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md ..." href="#">
      <span class="menu-icon">
        <i class="ki-filled ki-home-3"></i>
      </span>
      <span class="menu-title">Dashboard</span>
    </a>
  </div>
  
  <!-- Menu com Submenu -->
  <div class="menu-item" data-menu-item-toggle="accordion">
    <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md">
      <span class="menu-icon">
        <i class="ki-filled ki-profile-circle"></i>
      </span>
      <span class="menu-title">Cadastros</span>
      <span class="menu-arrow">
        <i class="ki-filled ki-down text-xs"></i>
      </span>
    </div>
    <div class="menu-accordion gap-px ps-7">
      <div class="menu-item">
        <a class="menu-link" href="#">
          <span class="menu-title">Produtos</span>
        </a>
      </div>
    </div>
  </div>
</div>
```

## 🎨 Classes Utilitárias Tailwind

### Espaçamento
- `gap-2`, `gap-5`, `gap-7.5` - Espaçamento entre elementos
- `py-5`, `px-3.5` - Padding
- `mb-1`, `mt-5` - Margin

### Flexbox
- `flex` - Display flex
- `flex-wrap` - Wrap
- `items-center` - Alinhar itens ao centro
- `justify-between` - Espaçar entre elementos

### Cores de Texto
- `text-gray-700` - Cinza médio
- `text-gray-900` - Cinza escuro
- `text-primary` - Cor primária

### Tamanhos de Texto
- `text-sm` - Pequeno
- `text-2sm` - Muito pequeno
- `text-base` - Base (padrão)
- `text-lg` - Grande

### Larguras
- `w-[60px]` - Largura fixa
- `min-w-[250px]` - Largura mínima
- `w-full` - Largura total

## 🔧 Data Attributes para Funcionalidades

### DataTable
- `data-datatable="true"` - Ativa DataTable
- `data-datatable-page-size="10"` - Itens por página
- `data-datatable-table="true"` - Marca a tabela
- `data-datatable-check="true"` - Checkbox de seleção geral
- `data-datatable-row-check="true"` - Checkbox de linha

### Menu
- `data-menu="true"` - Ativa menu
- `data-menu-item-toggle="accordion"` - Menu acordeon
- `data-menu-item-trigger="click"` - Trigger do menu

### Drawer (Sidebar Mobile)
- `data-drawer="true"` - Ativa drawer
- `data-drawer-toggle="#sidebar"` - Botão para abrir drawer

## 📦 Estrutura de Arquivos de Assets

```
public/
  assets/
    vendors/
      apexcharts/        # Gráficos
      keenicons/         # Ícones
      @form-validation/  # Validação de formulários
    css/
      styles.css         # CSS principal
    js/
      core.bundle.js     # JavaScript principal
      datatables/        # Scripts de DataTables
    media/
      app/               # Logos, favicons
      avatars/           # Avatares
      brand-logos/       # Logos de marcas
```

## 🎯 Padrões de Nomenclatura

### Classes de Componentes
- `card` - Card
- `btn` - Botão
- `input` - Input
- `select` - Select
- `table` - Tabela
- `menu` - Menu
- `checkbox` - Checkbox

### Modificadores
- `-sm` - Pequeno (btn-sm, input-sm)
- `-lg` - Grande (btn-lg)
- `-primary` - Cor primária
- `-light` - Cor clara
- `-clear` - Sem background

## 💡 Dicas de Uso

1. **Sempre use `card` para agrupar conteúdo**
2. **Use `flex-wrap` no header quando houver múltiplos elementos**
3. **DataTables são ativados automaticamente com `data-datatable="true"`**
4. **Ícones sempre dentro de `<i>` com classes `ki-filled ki-*`**
5. **Use `scrollable-x-auto` para tabelas responsivas**
6. **Menu lateral usa sistema de accordion para submenus**

## 📚 Exemplos de Páginas de Referência

- **Tabelas**: `network/user-table/market-authors.html`
- **Formulários**: `account/home/user-profile.html`
- **Listagem com Filtros**: `account/security/device-management.html`
- **Dashboard**: `index.html`

