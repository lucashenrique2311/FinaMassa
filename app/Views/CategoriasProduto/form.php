<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        <?= $categoria_data ? 'Editar Categoria' : 'Nova Categoria' ?>
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('CategoriasProduto') ?>">
          Categorias
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900"><?= $categoria_data ? 'Editar' : 'Nova' ?></span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-light" href="<?= base_url('CategoriasProduto') ?>">
        <i class="ki-filled ki-cross !text-base"></i>
        Cancelar
      </a>
    </div>
  </div>
</div>
<!-- End of Toolbar -->

<!-- Container -->
<div class="container-fixed" style="max-width: 1600px !important;">
  <div class="grid gap-5 lg:gap-7.5">
    <!-- Mensagens de Erro -->
    <?php if (session()->getFlashdata('erros')): ?>
      <?php $erros = session()->getFlashdata('erros'); ?>
      <?php if (is_array($erros)): ?>
        <div class="alert alert-danger flex flex-col gap-2 p-3 rounded-md bg-red-50 border border-red-200">
          <?php foreach ($erros as $erro): ?>
            <div class="flex items-center gap-2.5">
              <i class="ki-filled ki-information-2 text-red-500"></i>
              <span class="text-sm text-red-700"><?= esc($erro) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (session()->getFlashdata('erro')): ?>
      <div class="alert alert-danger flex items-center gap-2.5 p-3 rounded-md bg-red-50 border border-red-200">
        <i class="ki-filled ki-information-2 text-red-500"></i>
        <span class="text-sm text-red-700"><?= session()->getFlashdata('erro') ?></span>
      </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <?= $categoria_data ? 'Editar Categoria' : 'Nova Categoria' ?>
        </h3>
      </div>
      <div class="card-body">
        <form action="<?= $categoria_data ? base_url('CategoriasProduto/atualizar/' . $categoria_data['id_categoria']) : base_url('CategoriasProduto/salvar') ?>" method="post" id="form_categoria">
          <?= csrf_field() ?>
          
          <div class="grid lg:grid-cols-2 gap-5 lg:gap-7.5">
            <!-- Nome -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="nome">
                Nome <span class="text-red-500">*</span>
              </label>
              <label class="input">
                <i class="ki-filled ki-tag"></i>
                <input 
                  type="text" 
                  id="nome" 
                  name="nome" 
                  placeholder="Nome da categoria" 
                  value="<?= old('nome', $categoria_data['nome'] ?? '') ?>" 
                  required
                />
              </label>
            </div>

            <!-- Cor -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="cor">
                Cor (opcional)
              </label>
              <div class="flex gap-2">
                <input 
                  type="color" 
                  id="cor_picker" 
                  value="<?= old('cor', $categoria_data['cor'] ?? '#3B82F6') ?>"
                  class="w-16 h-10 rounded border border-gray-200"
                  onchange="document.getElementById('cor').value = this.value;"
                />
                <label class="input flex-1">
                  <i class="ki-filled ki-palette"></i>
                  <input 
                    type="text" 
                    id="cor" 
                    name="cor" 
                    placeholder="#3B82F6" 
                    value="<?= old('cor', $categoria_data['cor'] ?? '') ?>"
                    pattern="^#[0-9A-Fa-f]{6}$"
                  />
                </label>
              </div>
            </div>

            <!-- Status -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="ativo">
                Status
              </label>
              <label class="switch">
                <input 
                  type="checkbox" 
                  id="ativo" 
                  name="ativo" 
                  value="1" 
                  <?= old('ativo', $categoria_data['ativo'] ?? 1) ? 'checked' : '' ?>
                />
                <span class="switch-label">
                  <span class="switch-label-active">Ativo</span>
                  <span class="switch-label-inactive">Inativo</span>
                </span>
              </label>
            </div>

            <!-- Descrição -->
            <div class="flex flex-col gap-1 lg:col-span-2">
              <label class="form-label font-normal text-gray-900" for="descricao">
                Descrição
              </label>
              <textarea 
                id="descricao" 
                name="descricao" 
                class="textarea" 
                rows="3"
                placeholder="Descrição da categoria"
              ><?= old('descricao', $categoria_data['descricao'] ?? '') ?></textarea>
            </div>
          </div>

          <!-- Botões -->
          <div class="flex items-center gap-2.5 justify-end mt-7.5 pt-5 border-t border-gray-200">
            <a class="btn btn-light" href="<?= base_url('CategoriasProduto') ?>">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="ki-filled ki-check"></i>
              <?= $categoria_data ? 'Atualizar' : 'Salvar' ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->

<script>
// Sincroniza cor picker com input
document.getElementById('cor')?.addEventListener('input', function(e) {
  const value = e.target.value;
  if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
    document.getElementById('cor_picker').value = value;
  }
});

// Validação do formulário
document.getElementById('form_categoria')?.addEventListener('submit', function(e) {
  const nome = document.getElementById('nome').value.trim();
  
  if (!nome) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, preencha o nome da categoria.');
    return false;
  }
});
</script>
</main>

