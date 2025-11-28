<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        <?= $title ?>
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Permissoes') ?>">
          Permissões
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900"><?= $permissao_data ? 'Editar' : 'Novo' ?></span>
      </div>
    </div>
  </div>
</div>
<!-- End of Toolbar -->

<!-- Container -->
<div class="container-fixed" style="max-width: 1600px !important;">
  <div class="grid gap-5 lg:gap-7.5">
    <!-- Mensagens -->
    <?php if (session()->getFlashdata('sucesso')): ?>
      <div class="alert alert-success flex items-center gap-2.5 p-3 rounded-md bg-green-50 border border-green-200">
        <i class="ki-filled ki-check-circle text-green-500"></i>
        <span class="text-sm text-green-700"><?= session()->getFlashdata('sucesso') ?></span>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('erro')): ?>
      <div class="alert alert-danger flex items-center gap-2.5 p-3 rounded-md bg-red-50 border border-red-200">
        <i class="ki-filled ki-information-2 text-red-500"></i>
        <span class="text-sm text-red-700"><?= session()->getFlashdata('erro') ?></span>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('erros')): ?>
      <div class="alert alert-danger flex flex-col gap-2 p-3 rounded-md bg-red-50 border border-red-200">
        <div class="flex items-center gap-2.5">
          <i class="ki-filled ki-information-2 text-red-500"></i>
          <span class="text-sm font-medium text-red-700">Erros de validação:</span>
        </div>
        <ul class="list-disc list-inside text-sm text-red-700 ml-7">
          <?php foreach (session()->getFlashdata('erros') as $erro): ?>
            <li><?= esc($erro) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="card card-grid min-w-full">
      <div class="card-header">
        <h3 class="card-title font-medium text-sm">
          <?= $permissao_data ? 'Editar Permissão' : 'Nova Permissão' ?>
        </h3>
      </div>
      <div class="card-body">
        <form method="POST" action="<?= $permissao_data ? base_url('Permissoes/atualizar/' . $permissao_data['id_permissao']) : base_url('Permissoes/salvar') ?>">
          <?= csrf_field() ?>
          
          <?php if ($permissao_data): ?>
            <input type="hidden" name="id" value="<?= $permissao_data['id_permissao'] ?>">
          <?php endif; ?>

          <div class="grid gap-5 lg:grid-cols-2">
            <!-- Nome -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="nome">
                Nome da Permissão <span class="text-red-500">*</span>
              </label>
              <input type="text" 
                     id="nome" 
                     name="nome" 
                     class="input" 
                     placeholder="Ex: Visualizar Produtos"
                     value="<?= old('nome', $permissao_data['nome'] ?? '') ?>"
                     required>
            </div>

            <!-- Descrição -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="descricao">
                Descrição
              </label>
              <input type="text" 
                     id="descricao" 
                     name="descricao" 
                     class="input" 
                     placeholder="Descrição da permissão"
                     value="<?= old('descricao', $permissao_data['descricao'] ?? '') ?>">
            </div>

            <!-- Módulo -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="modulo">
                Módulo <span class="text-red-500">*</span>
              </label>
              <select id="modulo" name="modulo" class="select" required>
                <option value="">Selecione um módulo</option>
                <option value="dashboard" <?= old('modulo', $permissao_data['modulo'] ?? '') == 'dashboard' ? 'selected' : '' ?>>Dashboard</option>
                <option value="usuarios" <?= old('modulo', $permissao_data['modulo'] ?? '') == 'usuarios' ? 'selected' : '' ?>>Usuários</option>
                <option value="produtos" <?= old('modulo', $permissao_data['modulo'] ?? '') == 'produtos' ? 'selected' : '' ?>>Produtos</option>
                <option value="categorias_produto" <?= old('modulo', $permissao_data['modulo'] ?? '') == 'categorias_produto' ? 'selected' : '' ?>>Categorias de Produtos</option>
                <option value="ingredientes_padrao" <?= old('modulo', $permissao_data['modulo'] ?? '') == 'ingredientes_padrao' ? 'selected' : '' ?>>Ingredientes Padrão</option>
                <option value="fornecedores" <?= old('modulo', $permissao_data['modulo'] ?? '') == 'fornecedores' ? 'selected' : '' ?>>Fornecedores</option>
                <option value="depositos" <?= old('modulo', $permissao_data['modulo'] ?? '') == 'depositos' ? 'selected' : '' ?>>Depósitos</option>
                <option value="estoque" <?= old('modulo', $permissao_data['modulo'] ?? '') == 'estoque' ? 'selected' : '' ?>>Estoque</option>
                <option value="pedidos" <?= old('modulo', $permissao_data['modulo'] ?? '') == 'pedidos' ? 'selected' : '' ?>>Pedidos de Venda</option>
                <option value="relatorios" <?= old('modulo', $permissao_data['modulo'] ?? '') == 'relatorios' ? 'selected' : '' ?>>Relatórios</option>
                <option value="permissoes" <?= old('modulo', $permissao_data['modulo'] ?? '') == 'permissoes' ? 'selected' : '' ?>>Permissões</option>
              </select>
            </div>

            <!-- Ação -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="acao">
                Ação <span class="text-red-500">*</span>
              </label>
              <select id="acao" name="acao" class="select" required>
                <option value="">Selecione uma ação</option>
                <option value="visualizar" <?= old('acao', $permissao_data['acao'] ?? '') == 'visualizar' ? 'selected' : '' ?>>Visualizar</option>
                <option value="criar" <?= old('acao', $permissao_data['acao'] ?? '') == 'criar' ? 'selected' : '' ?>>Criar</option>
                <option value="editar" <?= old('acao', $permissao_data['acao'] ?? '') == 'editar' ? 'selected' : '' ?>>Editar</option>
                <option value="excluir" <?= old('acao', $permissao_data['acao'] ?? '') == 'excluir' ? 'selected' : '' ?>>Excluir</option>
              </select>
            </div>
          </div>

          <!-- Botões -->
          <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
            <a href="<?= base_url('Permissoes') ?>" class="btn btn-sm btn-light">
              Cancelar
            </a>
            <button type="submit" class="btn btn-sm btn-primary">
              <i class="ki-filled ki-check"></i>
              <?= $permissao_data ? 'Atualizar' : 'Salvar' ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->
</main>

