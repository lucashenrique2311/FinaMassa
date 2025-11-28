<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Entrada de Estoque
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Estoque') ?>">
          Estoque
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900">Entrada</span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-light" href="<?= base_url('Estoque') ?>">
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
    <!-- Mensagens -->
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
          Nova Entrada de Estoque
        </h3>
      </div>
      <div class="card-body">
        <form action="<?= base_url('Estoque/registrarEntrada') ?>" method="post" id="form_entrada">
          <?= csrf_field() ?>
          
          <div class="grid lg:grid-cols-2 gap-5 lg:gap-7.5">
            <!-- Ingrediente Padrão -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="id_ingrediente_padrao">
                Ingrediente <span class="text-red-500">*</span>
              </label>
              <select id="id_ingrediente_padrao" name="id_ingrediente_padrao" class="select" required onchange="atualizarEstoqueAtual()">
                <option value="">Selecione um ingrediente</option>
                <?php if (empty($ingredientes)): ?>
                  <option value="" disabled>Nenhum ingrediente cadastrado</option>
                <?php else: ?>
                  <?php foreach ($ingredientes as $ingrediente): ?>
                    <option value="<?= $ingrediente['id_ingrediente_padrao'] ?>" 
                            data-unidade="<?= esc($ingrediente['unidade_medida'] ?? 'UN') ?>"
                            <?= ($id_ingrediente_selecionado ?? '') == $ingrediente['id_ingrediente_padrao'] ? 'selected' : '' ?>>
                      <?= esc($ingrediente['nome']) ?> (<?= esc($ingrediente['unidade_medida'] ?? 'UN') ?>)
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <?php if (empty($ingredientes)): ?>
                <div class="alert alert-warning flex items-center gap-2.5 p-3 rounded-md bg-yellow-50 border border-yellow-200 mt-2">
                  <i class="ki-filled ki-information-2 text-yellow-500"></i>
                  <div class="flex flex-col">
                    <span class="text-sm text-yellow-700 font-medium">Nenhum ingrediente cadastrado!</span>
                    <span class="text-xs text-yellow-600 mt-1">
                      Você precisa cadastrar ingredientes padrão para poder fazer entrada de estoque.
                      <a href="<?= base_url('IngredientesPadrao/criar') ?>" class="text-primary hover:underline">Cadastrar ingrediente agora</a>
                    </span>
                  </div>
                </div>
              <?php else: ?>
                <span class="text-xs text-gray-500 mt-1">
                  Selecione o ingrediente que você comprou
                </span>
              <?php endif; ?>
            </div>
            
            <!-- Estoque Atual (será preenchido via JavaScript) -->
            <div id="estoque_atual_container" class="hidden lg:col-span-2">
              <div class="card bg-gray-50 border border-gray-200">
                <div class="card-body p-4">
                  <h4 class="text-sm font-medium text-gray-900 mb-2">Estoque Atual do Ingrediente</h4>
                  <div id="estoque_atual_info" class="text-sm text-gray-600">
                    <!-- Será preenchido via JavaScript -->
                  </div>
                </div>
              </div>
            </div>

            <!-- Depósito -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="id_deposito">
                Depósito <span class="text-red-500">*</span>
              </label>
              <select id="id_deposito" name="id_deposito" class="select" required>
                <option value="">Selecione um depósito</option>
                <?php foreach ($depositos as $deposito): ?>
                  <option value="<?= $deposito['id_deposito'] ?>">
                    <?= esc($deposito['nome']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Quantidade -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="quantidade">
                Quantidade Comprada <span class="text-red-500">*</span>
              </label>
              <label class="input">
                <i class="ki-filled ki-archive"></i>
                <input 
                  type="text" 
                  id="quantidade" 
                  name="quantidade" 
                  placeholder="0,000" 
                  required
                />
              </label>
              <span class="text-xs text-gray-500 mt-1">
                Quantidade que você comprou (ex: 2,000 kg de calabresa)
              </span>
            </div>

            <!-- Custo Unitário -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="custo_unitario">
                Custo Unitário (Preço por Unidade) <span class="text-red-500">*</span>
              </label>
              <label class="input">
                <i class="ki-filled ki-dollar"></i>
                <input 
                  type="text" 
                  id="custo_unitario" 
                  name="custo_unitario" 
                  placeholder="0,00" 
                  required
                />
              </label>
              <span class="text-xs text-gray-500 mt-1">
                Preço que você pagou por unidade (ex: R$ 15,00 por kg)
              </span>
            </div>

            <!-- Fornecedor -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="id_fornecedor">
                Fornecedor
              </label>
              <select id="id_fornecedor" name="id_fornecedor" class="select">
                <option value="">Selecione um fornecedor (opcional)</option>
                <?php foreach ($fornecedores as $fornecedor): ?>
                  <option value="<?= $fornecedor['id_fornecedor'] ?>">
                    <?= esc($fornecedor['razao_social']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Observações -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="observacoes">
                Observações
              </label>
              <textarea 
                id="observacoes" 
                name="observacoes" 
                class="textarea" 
                rows="3"
                placeholder="Observações sobre a entrada"
              ></textarea>
            </div>
          </div>

          <!-- Botões -->
          <div class="flex items-center gap-2.5 justify-end mt-7.5 pt-5 border-t border-gray-200">
            <a class="btn btn-light" href="<?= base_url('Estoque') ?>">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="ki-filled ki-check"></i>
              Registrar Entrada
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->

<script>
// Máscara de valores monetários
function mascaraMoeda(input) {
  let value = input.value.replace(/\D/g, '');
  value = (value / 100).toFixed(2) + '';
  value = value.replace('.', ',');
  value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  input.value = value;
}

document.getElementById('custo_unitario')?.addEventListener('input', function(e) {
  mascaraMoeda(e.target);
});

// Máscara de quantidade (decimal com 3 casas)
document.getElementById('quantidade')?.addEventListener('input', function(e) {
  let value = e.target.value.replace(/[^\d,]/g, '');
  value = value.replace(',', '.');
  if (value.split('.').length > 2) {
    value = value.substring(0, value.lastIndexOf('.'));
  }
  e.target.value = value;
});

// Atualiza informações do estoque atual quando seleciona um ingrediente
function atualizarEstoqueAtual() {
  const ingredienteSelect = document.getElementById('id_ingrediente_padrao');
  const ingredienteId = ingredienteSelect.value;
  const container = document.getElementById('estoque_atual_container');
  const info = document.getElementById('estoque_atual_info');
  
  if (!ingredienteId) {
    container.classList.add('hidden');
    return;
  }
  
  // Busca estoque atual via AJAX (precisa buscar pelo produto vinculado)
  fetch(`<?= base_url('Estoque/get-estoque-atual-ingrediente') ?>?id_ingrediente_padrao=${ingredienteId}`)
    .then(response => response.json())
    .then(data => {
      if (data.estoque && data.estoque.length > 0) {
        const unidade = ingredienteSelect.options[ingredienteSelect.selectedIndex].dataset.unidade || 'UN';
        let html = '<div class="space-y-2">';
        data.estoque.forEach(item => {
          html += `<div class="flex justify-between items-center py-1 border-b border-gray-200">
            <span class="text-gray-700">${item.deposito}:</span>
            <span class="font-medium text-gray-900">${parseFloat(item.quantidade).toFixed(3).replace('.', ',')} ${unidade}</span>
            <span class="text-gray-500 text-xs">Custo médio: R$ ${parseFloat(item.custo_medio).toFixed(2).replace('.', ',')}</span>
          </div>`;
        });
        html += '</div>';
        info.innerHTML = html;
        container.classList.remove('hidden');
      } else {
        info.innerHTML = '<span class="text-gray-500">Nenhum estoque registrado ainda para este ingrediente.</span>';
        container.classList.remove('hidden');
      }
    })
    .catch(error => {
      console.error('Erro ao buscar estoque:', error);
      container.classList.add('hidden');
    });
}

// Inicializa se já tiver ingrediente selecionado
document.addEventListener('DOMContentLoaded', function() {
  const ingredienteSelect = document.getElementById('id_ingrediente_padrao');
  if (ingredienteSelect && ingredienteSelect.value) {
    atualizarEstoqueAtual();
  }
});

// Validação do formulário
document.getElementById('form_entrada')?.addEventListener('submit', function(e) {
  const ingrediente = document.getElementById('id_ingrediente_padrao').value;
  const deposito = document.getElementById('id_deposito').value;
  const quantidade = document.getElementById('quantidade').value;
  const custo = document.getElementById('custo_unitario').value;
  
  if (!ingrediente) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, selecione um ingrediente.');
    return false;
  }
  
  if (!deposito) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, selecione um depósito.');
    return false;
  }
  
  if (!quantidade || parseFloat(quantidade.replace(',', '.')) <= 0) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, informe uma quantidade válida.');
    return false;
  }
  
  if (!custo || parseFloat(custo.replace(',', '.').replace('.', '')) <= 0) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, informe um custo unitário válido.');
    return false;
  }
});
</script>
</main>

