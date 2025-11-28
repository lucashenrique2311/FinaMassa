<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        <?= $ingrediente_data ? 'Editar Ingrediente Padrão' : 'Novo Ingrediente Padrão' ?>
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('IngredientesPadrao') ?>">
          Ingredientes Padrão
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900"><?= $ingrediente_data ? 'Editar' : 'Novo' ?></span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-light" href="<?= base_url('IngredientesPadrao') ?>">
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

    <!-- Formulário -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <?= $ingrediente_data ? 'Editar Ingrediente Padrão' : 'Novo Ingrediente Padrão' ?>
        </h3>
      </div>
      <div class="card-body">
        <form action="<?= $ingrediente_data ? base_url('IngredientesPadrao/atualizar/' . $ingrediente_data['id_ingrediente_padrao']) : base_url('IngredientesPadrao/salvar') ?>" method="post" id="form_ingrediente">
          <?= csrf_field() ?>
          
          <div class="grid lg:grid-cols-2 gap-5 lg:gap-7.5">
            <!-- Nome -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="nome">
                Nome <span class="text-red-500">*</span>
              </label>
              <label class="input">
                <i class="ki-filled ki-box"></i>
                <input 
                  type="text" 
                  id="nome" 
                  name="nome" 
                  placeholder="Nome do ingrediente" 
                  value="<?= old('nome', $ingrediente_data['nome'] ?? '') ?>" 
                  required
                />
              </label>
            </div>

            <!-- Categoria -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="categoria">
                Categoria
              </label>
              <div class="flex gap-2">
                <select id="categoria" name="categoria" class="select2-select flex-1">
                  <option value="">Selecione uma categoria</option>
                  <?php foreach ($categorias as $cat): ?>
                    <option value="<?= esc($cat) ?>" <?= old('categoria', $ingrediente_data['categoria'] ?? '') == $cat ? 'selected' : '' ?>>
                      <?= esc($cat) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <a href="<?= base_url('CategoriasProduto/criar') ?>" target="_blank" class="btn btn-sm btn-light" title="Cadastrar nova categoria">
                  <i class="ki-filled ki-plus"></i>
                </a>
              </div>
              <span class="text-xs text-gray-500 mt-1">
                <a href="<?= base_url('CategoriasProduto') ?>" target="_blank" class="text-primary hover:underline">
                  Gerenciar categorias
                </a>
              </span>
            </div>

            <!-- Unidade de Medida -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="unidade_medida">
                Unidade de Medida
              </label>
              <select id="unidade_medida" name="unidade_medida" class="select">
                <option value="UN" <?= old('unidade_medida', $ingrediente_data['unidade_medida'] ?? 'UN') == 'UN' ? 'selected' : '' ?>>UN - Unidade</option>
                <option value="KG" <?= old('unidade_medida', $ingrediente_data['unidade_medida'] ?? '') == 'KG' ? 'selected' : '' ?>>KG - Quilograma</option>
                <option value="L" <?= old('unidade_medida', $ingrediente_data['unidade_medida'] ?? '') == 'L' ? 'selected' : '' ?>>L - Litro</option>
                <option value="M" <?= old('unidade_medida', $ingrediente_data['unidade_medida'] ?? '') == 'M' ? 'selected' : '' ?>>M - Metro</option>
                <option value="M2" <?= old('unidade_medida', $ingrediente_data['unidade_medida'] ?? '') == 'M2' ? 'selected' : '' ?>>M² - Metro Quadrado</option>
              </select>
            </div>

            <!-- Custo Padrão -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="custo_padrao">
                Custo Padrão
              </label>
              <label class="input">
                <i class="ki-filled ki-dollar"></i>
                <input 
                  type="text" 
                  id="custo_padrao" 
                  name="custo_padrao" 
                  placeholder="0,00" 
                  value="<?= old('custo_padrao', isset($ingrediente_data['custo_padrao']) ? number_format($ingrediente_data['custo_padrao'], 2, ',', '.') : '0,00') ?>"
                />
              </label>
              <span class="text-xs text-gray-500 mt-1">
                Custo padrão do ingrediente (usado como referência)
              </span>
            </div>

            <!-- Quantidade Inicial -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="quantidade_inicial">
                Quantidade Inicial
              </label>
              <label class="input">
                <i class="ki-filled ki-archive"></i>
                <input 
                  type="text" 
                  id="quantidade_inicial" 
                  name="quantidade_inicial" 
                  placeholder="0,000" 
                  value="<?= old('quantidade_inicial', isset($ingrediente_data['quantidade_inicial']) ? number_format($ingrediente_data['quantidade_inicial'], 3, ',', '.') : '') ?>"
                />
              </label>
              <span class="text-xs text-gray-500 mt-1">
                Quantidade inicial em estoque (apenas para referência - faça a entrada de estoque na tela de Estoque)
              </span>
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
                  <?= old('ativo', $ingrediente_data['ativo'] ?? 1) ? 'checked' : '' ?>
                />
                <span class="switch-label">
                  <span class="switch-label-active">Ativo</span>
                  <span class="switch-label-inactive">Inativo</span>
                </span>
              </label>
            </div>
          </div>

          <!-- Botões -->
          <div class="flex items-center gap-2.5 justify-end mt-7.5 pt-5 border-t border-gray-200">
            <a class="btn btn-light" href="<?= base_url('IngredientesPadrao') ?>">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="ki-filled ki-check"></i>
              <?= $ingrediente_data ? 'Atualizar' : 'Salvar' ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->

<script>
// Inicializar Select2
$(document).ready(function() {
  $('#categoria').select2({
    placeholder: 'Selecione uma categoria',
    allowClear: true,
    width: '100%'
  });
});

// Máscara de valores monetários
function mascaraMoeda(input) {
  let value = input.value.replace(/\D/g, '');
  value = (value / 100).toFixed(2) + '';
  value = value.replace('.', ',');
  value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  input.value = value;
}

document.getElementById('custo_padrao')?.addEventListener('input', function(e) {
  mascaraMoeda(e.target);
});

// Máscara de quantidade inicial
document.getElementById('quantidade_inicial')?.addEventListener('input', function(e) {
  let value = e.target.value.replace(/[^\d,]/g, '');
  // Permite apenas uma vírgula
  if (value.split(',').length > 2) {
    value = value.substring(0, value.lastIndexOf(','));
  }
  e.target.value = value;
});

// Validação do formulário
document.getElementById('form_ingrediente')?.addEventListener('submit', function(e) {
  const nome = document.getElementById('nome').value.trim();
  
  if (!nome) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, preencha o nome do ingrediente padrão.');
    return false;
  }
});
</script>
</main>

