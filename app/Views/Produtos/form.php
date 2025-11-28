<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        <?= $produto_data ? 'Editar Produto' : 'Novo Produto' ?>
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Produtos') ?>">
          Produtos
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900"><?= $produto_data ? 'Editar' : 'Novo' ?></span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-light" href="<?= base_url('Produtos') ?>">
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
          <?= $produto_data ? 'Editar Produto' : 'Novo Produto' ?>
        </h3>
      </div>
      <div class="card-body">
        <form action="<?= $produto_data ? base_url('Produtos/atualizar/' . $produto_data['id_produto']) : base_url('Produtos/salvar') ?>" method="post" id="form_produto">
          <?= csrf_field() ?>
          
          <div class="grid lg:grid-cols-2 gap-5 lg:gap-7.5">
            <!-- Código -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="codigo">
                Código
              </label>
              <label class="input">
                <i class="ki-filled ki-hash"></i>
                <input 
                  type="text" 
                  id="codigo" 
                  name="codigo" 
                  placeholder="Código do produto" 
                  value="<?= old('codigo', $produto_data['codigo'] ?? '') ?>"
                />
              </label>
            </div>

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
                  placeholder="Nome do produto" 
                  value="<?= old('nome', $produto_data['nome'] ?? '') ?>" 
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
                  <?php foreach ($categorias_completas ?? [] as $cat): ?>
                    <option value="<?= esc($cat['nome']) ?>" 
                            <?= old('categoria', $produto_data['categoria'] ?? '') == $cat['nome'] ? 'selected' : '' ?>
                            data-cor="<?= esc($cat['cor'] ?? '') ?>">
                      <?= esc($cat['nome']) ?>
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
                Unidade de Medida <span id="unidade_obrigatoria" class="text-red-500 hidden">*</span>
              </label>
              <select id="unidade_medida" name="unidade_medida" class="select2-select">
                <option value="UN" <?= old('unidade_medida', $produto_data['unidade_medida'] ?? 'UN') == 'UN' ? 'selected' : '' ?>>UN - Unidade</option>
                <option value="KG" <?= old('unidade_medida', $produto_data['unidade_medida'] ?? '') == 'KG' ? 'selected' : '' ?>>KG - Quilograma</option>
                <option value="L" <?= old('unidade_medida', $produto_data['unidade_medida'] ?? '') == 'L' ? 'selected' : '' ?>>L - Litro</option>
                <option value="M" <?= old('unidade_medida', $produto_data['unidade_medida'] ?? '') == 'M' ? 'selected' : '' ?>>M - Metro</option>
                <option value="M2" <?= old('unidade_medida', $produto_data['unidade_medida'] ?? '') == 'M2' ? 'selected' : '' ?>>M² - Metro Quadrado</option>
              </select>
              <span class="text-xs text-gray-500 mt-1" id="unidade_hint">
                Unidade de medida do produto (ex: KG para calabresa, L para óleo)
              </span>
            </div>
          </div>

          <!-- Calculadora de Custo (Composição) -->
          <div class="card mt-5">
            <div class="card-header flex items-center justify-between">
              <h3 class="card-title">
                Calculadora de Custo - Composição do Produto
              </h3>
              <div class="flex items-center gap-2">
                <select id="combo_ingrediente_padrao" class="select2-select select-sm" onchange="adicionarIngredientePadrao()">
                  <option value="">Adicionar Ingrediente Padrão</option>
                  <?php 
                  $categoriaAtual = '';
                  foreach ($ingredientes_padrao ?? [] as $ingPadrao): 
                    if ($categoriaAtual != $ingPadrao['categoria']):
                      if ($categoriaAtual != ''):
                        echo '</optgroup>';
                      endif;
                      $categoriaAtual = $ingPadrao['categoria'];
                      echo '<optgroup label="' . esc($categoriaAtual) . '">';
                    endif;
                  ?>
                    <option value="<?= $ingPadrao['id_ingrediente_padrao'] ?>" 
                            data-nome="<?= esc($ingPadrao['nome']) ?>"
                            data-custo="<?= $ingPadrao['custo_padrao'] ?>"
                            data-unidade="<?= esc($ingPadrao['unidade_medida']) ?>">
                      <?= esc($ingPadrao['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                  <?php if ($categoriaAtual != ''): ?>
                    </optgroup>
                  <?php endif; ?>
                </select>
              </div>
            </div>
            <div class="card-body">
              <div id="ingredientes_container">
                <!-- Ingredientes serão adicionados aqui via JavaScript -->
              </div>
              <input type="hidden" id="composicao_json" name="composicao_json" value="[]">
              
              <!-- Resumo -->
              <div class="mt-5 pt-5 border-t border-gray-200">
                <div class="flex items-center justify-between">
                  <span class="text-sm font-medium text-gray-700">Custo Total Calculado:</span>
                  <span class="text-xl font-bold text-primary" id="custo_total_calculado">R$ 0,00</span>
                </div>
                <button type="button" class="btn btn-sm btn-primary mt-3" onclick="aplicarCustoCalculado()">
                  <i class="ki-filled ki-check"></i>
                  Aplicar Custo Calculado
                </button>
              </div>
            </div>
          </div>

          <div class="grid lg:grid-cols-2 gap-5 lg:gap-7.5 mt-5">
            <!-- Custo Unitário -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="custo_unitario">
                Custo Unitário
              </label>
              <label class="input">
                <i class="ki-filled ki-dollar"></i>
                <input 
                  type="text" 
                  id="custo_unitario" 
                  name="custo_unitario" 
                  placeholder="0,00" 
                  value="<?= old('custo_unitario', isset($produto_data['custo_unitario']) ? number_format($produto_data['custo_unitario'], 2, ',', '.') : '0,00') ?>"
                  readonly
                />
              </label>
              <span class="text-xs text-gray-500 mt-1">
                Calculado automaticamente pela composição acima
              </span>
            </div>

            <!-- Preço de Venda -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="preco_venda">
                Preço de Venda
              </label>
              <label class="input">
                <i class="ki-filled ki-dollar"></i>
                <input 
                  type="text" 
                  id="preco_venda" 
                  name="preco_venda" 
                  placeholder="0,00" 
                  value="<?= old('preco_venda', isset($produto_data['preco_venda']) && $produto_data['preco_venda'] ? number_format($produto_data['preco_venda'], 2, ',', '.') : '') ?>"
                />
              </label>
            </div>

            <!-- Estoque Mínimo -->
            <div class="flex flex-col gap-1" id="estoque_minimo_container">
              <label class="form-label font-normal text-gray-900" for="estoque_minimo">
                Estoque Mínimo
              </label>
              <label class="input">
                <i class="ki-filled ki-archive"></i>
                <input 
                  type="text" 
                  id="estoque_minimo" 
                  name="estoque_minimo" 
                  placeholder="0,000" 
                  value="<?= old('estoque_minimo', isset($produto_data['estoque_minimo']) ? number_format($produto_data['estoque_minimo'], 3, ',', '.') : '0,000') ?>"
                />
              </label>
              <span class="text-xs text-gray-500 mt-1">
                Quantidade mínima em estoque para alerta (ex: 0,500 kg)
              </span>
            </div>

            <!-- Controla Estoque (oculto, sempre 0 para produtos finais) -->
            <input type="hidden" id="controla_estoque" name="controla_estoque" value="0">
            <input type="hidden" id="eh_ingrediente" name="eh_ingrediente" value="0">

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
                placeholder="Descrição do produto"
              ><?= old('descricao', $produto_data['descricao'] ?? '') ?></textarea>
            </div>

            <!-- Imagem do Produto - COMENTADO TEMPORARIAMENTE -->
            <?php /*
            <div class="flex flex-col gap-1 lg:col-span-2">
              <label class="form-label font-normal text-gray-900" for="imagem">
                Imagem do Produto
              </label>
              <div class="flex flex-col gap-3">
                <?php if (!empty($produto_data['imagem'])): ?>
                  <div class="flex items-center gap-3">
                    <img src="<?= base_url('imagens/produto/' . basename($produto_data['imagem'])) ?>" 
                         alt="Imagem do produto" 
                         class="w-32 h-32 object-cover rounded-md border border-gray-200"
                         id="preview_imagem">
                    <div class="flex flex-col gap-2">
                      <span class="text-sm text-gray-600">Imagem atual</span>
                      <button type="button" 
                              class="btn btn-sm btn-light-danger" 
                              onclick="removerImagem()">
                        <i class="ki-filled ki-trash"></i>
                        Remover Imagem
                      </button>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="hidden" id="preview_container">
                    <div class="flex items-center gap-3">
                      <img src="" 
                           alt="Preview" 
                           class="w-32 h-32 object-cover rounded-md border border-gray-200"
                           id="preview_imagem">
                      <button type="button" 
                              class="btn btn-sm btn-light-danger" 
                              onclick="removerImagem()">
                        <i class="ki-filled ki-trash"></i>
                        Remover
                      </button>
                    </div>
                  </div>
                <?php endif; ?>
                <label class="input">
                  <i class="ki-filled ki-picture"></i>
                  <input 
                    type="file" 
                    id="imagem" 
                    name="imagem" 
                    accept="image/*"
                    onchange="previewImagem(this)"
                  />
                </label>
                <span class="text-xs text-gray-500">
                  Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB
                </span>
              </div>
            </div>
            */ ?>

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
                  <?= old('ativo', $produto_data['ativo'] ?? 1) ? 'checked' : '' ?>
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
            <a class="btn btn-light" href="<?= base_url('Produtos') ?>">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="ki-filled ki-check"></i>
              <?= $produto_data ? 'Atualizar' : 'Salvar' ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->

<script>
// Dados dos ingredientes disponíveis
const ingredientes = <?= json_encode($ingredientes ?? []) ?>;
const ingredientesPadrao = <?= json_encode($ingredientes_padrao ?? []) ?>;
const composicaoExistente = <?= json_encode($composicao ?? []) ?>;
let ingredientesAdicionados = [];
let contadorIngredientes = 0;

// Máscara de valores monetários
function mascaraMoeda(input) {
  let value = input.value.replace(/\D/g, '');
  value = (value / 100).toFixed(2) + '';
  value = value.replace('.', ',');
  value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  input.value = value;
}

document.getElementById('preco_venda')?.addEventListener('input', function(e) {
  mascaraMoeda(e.target);
});

// Máscara de estoque mínimo (decimal com 3 casas)
document.getElementById('estoque_minimo')?.addEventListener('input', function(e) {
  let value = e.target.value.replace(/[^\d,]/g, '');
  value = value.replace(',', '.');
  if (value.split('.').length > 2) {
    value = value.substring(0, value.lastIndexOf('.'));
  }
  e.target.value = value;
});

// Função para adicionar ingrediente padrão
function adicionarIngredientePadrao() {
  const select = document.getElementById('combo_ingrediente_padrao');
  const selectedOption = select.options[select.selectedIndex];
  
  if (!selectedOption.value) return;
  
  const nome = selectedOption.dataset.nome;
  const custo = parseFloat(selectedOption.dataset.custo || 0);
  const unidade = selectedOption.dataset.unidade || 'UN';
  
  // Adiciona como ingrediente
  adicionarIngrediente({
    nome: nome,
    custo_unitario: custo,
    unidade_medida: unidade,
    is_padrao: true
  });
  
  // Reseta o select
  select.value = '';
}

// Funções da Calculadora de Custo
function adicionarIngrediente(ingredienteData = null) {
  contadorIngredientes++;
  const ingredienteId = ingredienteData ? (ingredienteData.id_ingrediente || '') : '';
  const quantidade = ingredienteData && ingredienteData.quantidade !== undefined && ingredienteData.quantidade !== null ? ingredienteData.quantidade : '';
  const custoUnitario = ingredienteData ? ingredienteData.custo_unitario : '';
  const isPadrao = ingredienteData ? ingredienteData.is_padrao : false;
  
  // Busca dados do ingrediente selecionado
  let ingredienteNome = '';
  let ingredienteUnidade = '';
  let custoPadrao = 0;
  
  if (isPadrao && ingredienteData.nome) {
    // É um ingrediente padrão
    ingredienteNome = ingredienteData.nome;
    ingredienteUnidade = ingredienteData.unidade_medida || 'UN';
    custoPadrao = ingredienteData.custo_unitario || 0;
  } else if (ingredienteId) {
    // É um produto cadastrado
    const ingrediente = ingredientes.find(i => i.id_produto == ingredienteId);
    if (ingrediente) {
      ingredienteNome = ingrediente.nome;
      ingredienteUnidade = ingrediente.unidade_medida || 'UN';
      custoPadrao = ingrediente.custo_unitario || 0;
    }
  }
  
  const itemHtml = `
    <div class="ingrediente-item border border-gray-200 rounded-md p-4 mb-3" data-item-id="${contadorIngredientes}" data-is-padrao="${isPadrao ? '1' : '0'}">
      <div class="grid lg:grid-cols-5 gap-3">
        <div class="flex flex-col gap-1">
          <label class="form-label text-sm">Ingrediente</label>
          ${isPadrao ? `
            <input type="text" class="input input-sm" value="${ingredienteNome}" readonly>
            <input type="hidden" class="ingrediente-nome" value="${ingredienteNome}">
          ` : `
            <select class="select2-select select-sm ingrediente-select" onchange="atualizarIngrediente(${contadorIngredientes})">
              <option value="">Selecione um ingrediente</option>
              ${ingredientes.map(i => `<option value="${i.id_produto}" data-custo="${i.custo_unitario || 0}" data-unidade="${i.unidade_medida || 'UN'}" ${i.id_produto == ingredienteId ? 'selected' : ''}>${i.codigo ? i.codigo + ' - ' : ''}${i.nome}</option>`).join('')}
            </select>
          `}
        </div>
        <div class="flex flex-col gap-1">
          <label class="form-label text-sm">Quantidade</label>
          <input type="text" class="input input-sm quantidade-input" placeholder="0,000" value="${quantidade !== undefined && quantidade !== null && quantidade !== '' ? quantidade : ''}" onchange="atualizarIngrediente(${contadorIngredientes})" onkeyup="atualizarIngrediente(${contadorIngredientes})">
          <span class="text-xs text-gray-500 ingrediente-unidade">${ingredienteUnidade}</span>
        </div>
        <div class="flex flex-col gap-1">
          <label class="form-label text-sm">Custo Unit.</label>
          <input type="text" class="input input-sm custo-input" placeholder="0,00" value="${custoUnitario ? parseFloat(custoUnitario).toFixed(2).replace('.', ',') : ''}" onchange="atualizarIngrediente(${contadorIngredientes})" onkeyup="atualizarIngrediente(${contadorIngredientes})">
        </div>
        <div class="flex flex-col gap-1">
          <label class="form-label text-sm">Subtotal</label>
          <input type="text" class="input input-sm subtotal-input" value="0,00" readonly>
        </div>
        <div class="flex flex-col gap-1">
          <label class="form-label text-sm">&nbsp;</label>
          <button type="button" class="btn btn-sm btn-light" onclick="removerIngrediente(${contadorIngredientes})">
            <i class="ki-filled ki-trash"></i>
            Remover
          </button>
        </div>
      </div>
    </div>
  `;
  
  document.getElementById('ingredientes_container').insertAdjacentHTML('beforeend', itemHtml);
  
  // Inicializa Select2 no novo select (se existir)
  const itemEl = document.querySelector(`[data-item-id="${contadorIngredientes}"]`);
  const newSelect = itemEl.querySelector('.select2-select');
  if (newSelect) {
    if (typeof initSelect2 === 'function') {
      initSelect2(newSelect);
    } else if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
      jQuery(newSelect).select2({
        theme: 'default',
        width: '100%',
        language: {
          noResults: function() {
            return "Nenhum resultado encontrado";
          },
          searching: function() {
            return "Buscando...";
          }
        }
      });
    }
  }
  
  // Aplica máscaras
  itemEl.querySelector('.custo-input')?.addEventListener('input', function(e) {
    mascaraMoeda(e.target);
    atualizarIngrediente(contadorIngredientes);
  });
  
  itemEl.querySelector('.quantidade-input')?.addEventListener('input', function(e) {
    // Permite números e vírgula como separador decimal
    let value = e.target.value.replace(/[^\d,]/g, '');
    // Garante que só tenha uma vírgula
    const partes = value.split(',');
    if (partes.length > 2) {
      value = partes[0] + ',' + partes.slice(1).join('');
    }
    // Mantém o formato com vírgula (não converte para ponto)
    e.target.value = value;
    atualizarIngrediente(contadorIngredientes);
  });
  
  if (ingredienteData) {
    atualizarIngrediente(contadorIngredientes);
  }
  
  atualizarCustoTotal();
}

function removerIngrediente(id) {
  document.querySelector(`[data-item-id="${id}"]`).remove();
  ingredientesAdicionados = ingredientesAdicionados.filter(item => item.id !== id);
  atualizarComposicaoJson();
  atualizarCustoTotal();
}

function atualizarIngrediente(id) {
  const itemEl = document.querySelector(`[data-item-id="${id}"]`);
  const isPadrao = itemEl.dataset.isPadrao == '1';
  const ingredienteSelect = itemEl.querySelector('.ingrediente-select');
  const ingredienteNomeInput = itemEl.querySelector('.ingrediente-nome');
  const quantidadeInput = itemEl.querySelector('.quantidade-input');
  const custoInput = itemEl.querySelector('.custo-input');
  const subtotalInput = itemEl.querySelector('.subtotal-input');
  const unidadeSpan = itemEl.querySelector('.ingrediente-unidade');
  
  let ingredienteId = '';
  let ingredienteNome = '';
  let custoPadrao = 0;
  let unidade = 'UN';
  
  if (isPadrao) {
    // Ingrediente padrão - usa nome direto
    ingredienteNome = ingredienteNomeInput ? ingredienteNomeInput.value : '';
    // Busca custo padrão do ingrediente padrão
    const ingPadrao = ingredientesPadrao.find(ip => ip.nome === ingredienteNome);
    if (ingPadrao) {
      custoPadrao = parseFloat(ingPadrao.custo_padrao || 0);
      unidade = ingPadrao.unidade_medida || 'UN';
    }
  } else if (ingredienteSelect) {
    // Produto cadastrado
    ingredienteId = ingredienteSelect.value;
    const ingredienteOption = ingredienteSelect.options[ingredienteSelect.selectedIndex];
    custoPadrao = parseFloat(ingredienteOption.dataset.custo || 0);
    unidade = ingredienteOption.dataset.unidade || 'UN';
    
    // Busca nome do produto
    const ingrediente = ingredientes.find(i => i.id_produto == ingredienteId);
    if (ingrediente) {
      ingredienteNome = ingrediente.nome;
    }
  }
  
  // Atualiza unidade
  if (unidadeSpan) {
    unidadeSpan.textContent = unidade;
  }
  
  // Se tem custo padrão e o campo está vazio, preenche
  if (custoPadrao > 0 && custoInput && !custoInput.value) {
    custoInput.value = custoPadrao.toFixed(2).replace('.', ',');
  }
  
  const quantidade = parseFloat(quantidadeInput.value.replace(',', '.') || 0);
  const custo = custoInput ? (parseFloat(custoInput.value.replace(',', '.').replace('.', '') || 0) / 100) : custoPadrao;
  const subtotal = quantidade * custo;
  
  if (subtotalInput) {
    subtotalInput.value = subtotal.toFixed(2).replace('.', ',');
  }
  
  // Atualiza array de ingredientes
  const itemIndex = ingredientesAdicionados.findIndex(item => item.id === id);
  const itemData = {
    id: id,
    id_ingrediente: ingredienteId || null,
    nome_ingrediente: ingredienteNome,
    quantidade: quantidade,
    custo_unitario: custo,
    subtotal: subtotal,
    is_padrao: isPadrao
  };
  
  if (itemIndex >= 0) {
    ingredientesAdicionados[itemIndex] = itemData;
  } else {
    ingredientesAdicionados.push(itemData);
  }
  
  atualizarComposicaoJson();
  atualizarCustoTotal();
}

function atualizarComposicaoJson() {
  // Remove itens sem ingrediente selecionado ou nome
  ingredientesAdicionados = ingredientesAdicionados.filter(item => 
    (item.id_ingrediente || item.nome_ingrediente) && item.quantidade > 0
  );
  document.getElementById('composicao_json').value = JSON.stringify(ingredientesAdicionados);
}

function atualizarCustoTotal() {
  const total = ingredientesAdicionados.reduce((sum, item) => sum + (item.subtotal || 0), 0);
  document.getElementById('custo_total_calculado').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
}

function aplicarCustoCalculado() {
  const total = ingredientesAdicionados.reduce((sum, item) => sum + (item.subtotal || 0), 0);
  document.getElementById('custo_unitario').value = total.toFixed(2).replace('.', ',');
}

// Carrega composição existente ao editar
if (composicaoExistente && composicaoExistente.length > 0) {
  composicaoExistente.forEach(item => {
    // Verifica se é ingrediente padrão (tem nome_ingrediente mas não tem id_ingrediente válido)
    if (item.nome_ingrediente && (!item.id_ingrediente || item.id_ingrediente == null)) {
      // É um ingrediente padrão
      adicionarIngrediente({
        nome: item.nome_ingrediente,
        quantidade: item.quantidade,
        custo_unitario: item.custo_unitario,
        unidade_medida: item.ingrediente_unidade || 'UN',
        is_padrao: true
      });
    } else if (item.id_ingrediente) {
      // É um produto cadastrado
      adicionarIngrediente({
        id_ingrediente: item.id_ingrediente,
        quantidade: item.quantidade,
        custo_unitario: item.custo_unitario
      });
    }
  });
  atualizarCustoTotal();
}

// Produtos são sempre produtos finais (pizzas), não ingredientes
// Não precisa de função de atualização

// Validação do formulário
document.getElementById('form_produto')?.addEventListener('submit', function(e) {
  const nome = document.getElementById('nome').value.trim();
  
  if (!nome) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, preencha o nome do produto.');
    return false;
  }
  
  // Atualiza composição antes de enviar
  atualizarComposicaoJson();
});

// Preview de imagem - COMENTADO TEMPORARIAMENTE
/*
function previewImagem(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const preview = document.getElementById('preview_imagem');
      const container = document.getElementById('preview_container');
      if (preview) {
        preview.src = e.target.result;
        if (container) {
          container.classList.remove('hidden');
        }
      }
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Remover imagem
function removerImagem() {
  const input = document.getElementById('imagem');
  const preview = document.getElementById('preview_imagem');
  const container = document.getElementById('preview_container');
  
  if (input) {
    input.value = '';
  }
  
  if (preview) {
    preview.src = '';
  }
  
  if (container) {
    container.classList.add('hidden');
  }
  
  // Adiciona campo hidden para indicar remoção
  let hiddenInput = document.getElementById('remover_imagem');
  if (!hiddenInput) {
    hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.id = 'remover_imagem';
    hiddenInput.name = 'remover_imagem';
    hiddenInput.value = '1';
    document.getElementById('form_produto').appendChild(hiddenInput);
  }
}
*/
</script>
</main>

