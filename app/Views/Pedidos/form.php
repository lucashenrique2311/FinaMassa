<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-3">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Novo Pedido
      </h1>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-light" href="<?= base_url('Pedidos') ?>">
        <i class="ki-filled ki-cross !text-base"></i>
        Cancelar
      </a>
    </div>
  </div>
</div>
<!-- End of Toolbar -->

<!-- Container -->
<div class="container-fixed" style="max-width: 1600px !important;">
  <div class="grid gap-3 lg:gap-4">
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
    <form action="<?= base_url('Pedidos/salvar') ?>" method="post" id="form_pedido">
      <?= csrf_field() ?>
      
      <div class="grid gap-3 lg:gap-4">
        <!-- Dados do Cliente - Layout Compacto -->
        <div class="card">
          <div class="card-header py-2">
            <h3 class="card-title text-sm">
              Dados do Cliente
            </h3>
          </div>
          <div class="card-body py-3">
            <div class="grid lg:grid-cols-6 gap-2.5">
              <!-- Nome do Cliente -->
              <div class="flex flex-col gap-1 lg:col-span-2">
                <label class="form-label text-xs font-normal text-gray-900" for="cliente_nome">
                  Nome
                </label>
                <label class="input input-sm">
                  <i class="ki-filled ki-profile-user text-xs"></i>
                  <input 
                    type="text" 
                    id="cliente_nome" 
                    name="cliente_nome" 
                    placeholder="Nome do cliente" 
                    class="text-sm"
                  />
                </label>
              </div>

              <!-- Telefone -->
              <div class="flex flex-col gap-1">
                <label class="form-label text-xs font-normal text-gray-900" for="cliente_telefone">
                  Telefone
                </label>
                <label class="input input-sm">
                  <i class="ki-filled ki-phone text-xs"></i>
                  <input 
                    type="text" 
                    id="cliente_telefone" 
                    name="cliente_telefone" 
                    placeholder="(00) 00000-0000" 
                    class="text-sm"
                  />
                </label>
              </div>

              <!-- Tipo de Pedido -->
              <div class="flex flex-col gap-1">
                <label class="form-label text-xs font-normal text-gray-900" for="tipo_pedido">
                  Tipo
                </label>
                <select id="tipo_pedido" name="tipo_pedido" class="select2-select select-sm">
                  <option value="BALCAO">Balcão</option>
                  <option value="DELIVERY">Delivery</option>
                  <option value="RETIRADA">Retirada</option>
                </select>
              </div>

              <!-- Status -->
              <div class="flex flex-col gap-1">
                <label class="form-label text-xs font-normal text-gray-900" for="status">
                  Status
                </label>
                <select id="status" name="status" class="select2-select select-sm">
                  <option value="ABERTO">Aberto</option>
                  <option value="PREPARANDO">Preparando</option>
                  <option value="PRONTO">Pronto</option>
                </select>
              </div>

              <!-- Forma de Pagamento -->
              <div class="flex flex-col gap-1">
                <label class="form-label text-xs font-normal text-gray-900" for="forma_pagamento">
                  Pagamento
                </label>
                <select id="forma_pagamento" name="forma_pagamento" class="select2-select select-sm">
                  <option value="">Selecione</option>
                  <option value="DINHEIRO">Dinheiro</option>
                  <option value="CARTAO_CREDITO">Cartão de Crédito</option>
                  <option value="CARTAO_DEBITO">Cartão de Débito</option>
                  <option value="PIX">PIX</option>
                </select>
              </div>

              <!-- Endereço (para delivery) -->
              <div class="flex flex-col gap-1 lg:col-span-6">
                <label class="form-label text-xs font-normal text-gray-900" for="cliente_endereco">
                  Endereço (para delivery)
                </label>
                <textarea 
                  id="cliente_endereco" 
                  name="cliente_endereco" 
                  class="textarea textarea-sm" 
                  rows="1"
                  placeholder="Endereço completo para entrega"
                ></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Itens do Pedido - Layout Compacto -->
        <div class="card">
          <div class="card-header py-2 flex items-center justify-between">
            <h3 class="card-title text-sm">
              Itens do Pedido
            </h3>
            <button type="button" class="btn btn-sm btn-primary" onclick="adicionarItem()">
              <i class="ki-filled ki-plus !text-xs"></i>
              Adicionar
            </button>
          </div>
          <div class="card-body py-3">
            <div id="itens_container">
              <!-- Itens serão adicionados aqui via JavaScript -->
            </div>
            <input type="hidden" id="itens_json" name="itens" value="[]">
          </div>
        </div>

        <!-- Totais - Layout Compacto -->
        <div class="card">
          <div class="card-header py-2">
            <h3 class="card-title text-sm">
              Totais
            </h3>
          </div>
          <div class="card-body py-3">
            <div class="grid lg:grid-cols-5 gap-2.5">
              <!-- Subtotal -->
              <div class="flex flex-col gap-1">
                <label class="form-label text-xs font-normal text-gray-900" for="subtotal">
                  Subtotal
                </label>
                <label class="input input-sm">
                  <i class="ki-filled ki-dollar text-xs"></i>
                  <input 
                    type="text" 
                    id="subtotal" 
                    name="subtotal" 
                    value="0,00" 
                    readonly
                    class="text-sm font-medium"
                  />
                </label>
              </div>

              <!-- Desconto -->
              <div class="flex flex-col gap-1">
                <label class="form-label text-xs font-normal text-gray-900" for="desconto">
                  Desconto
                </label>
                <label class="input input-sm">
                  <i class="ki-filled ki-dollar text-xs"></i>
                  <input 
                    type="text" 
                    id="desconto" 
                    name="desconto" 
                    value="0,00" 
                    placeholder="0,00"
                    class="text-sm"
                  />
                </label>
              </div>

              <!-- Taxa de Entrega -->
              <div class="flex flex-col gap-1">
                <label class="form-label text-xs font-normal text-gray-900" for="taxa_entrega">
                  Taxa Entrega
                </label>
                <label class="input input-sm">
                  <i class="ki-filled ki-dollar text-xs"></i>
                  <input 
                    type="text" 
                    id="taxa_entrega" 
                    name="taxa_entrega" 
                    value="0,00" 
                    placeholder="0,00"
                    class="text-sm"
                  />
                </label>
              </div>

              <!-- Total -->
              <div class="flex flex-col gap-1">
                <label class="form-label text-xs font-normal text-gray-900" for="total">
                  Total
                </label>
                <label class="input input-sm">
                  <i class="ki-filled ki-dollar text-xs"></i>
                  <input 
                    type="text" 
                    id="total" 
                    name="total" 
                    value="0,00" 
                    readonly
                    class="text-sm font-bold text-primary"
                  />
                </label>
              </div>

              <!-- Observações Gerais -->
              <div class="flex flex-col gap-1">
                <label class="form-label text-xs font-normal text-gray-900" for="observacoes">
                  Obs. Gerais
                </label>
                <textarea 
                  id="observacoes" 
                  name="observacoes" 
                  class="textarea textarea-sm" 
                  rows="2"
                  placeholder="Observações do pedido"
                ></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Botões -->
        <div class="flex items-center gap-2.5 justify-end">
          <a class="btn btn-sm btn-light" href="<?= base_url('Pedidos') ?>">
            Cancelar
          </a>
          <button type="submit" class="btn btn-sm btn-primary">
            <i class="ki-filled ki-check"></i>
            Salvar Pedido
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- End of Container -->

<script>
let itens = [];
let contadorItens = 0;
const produtos = <?= json_encode($produtos) ?>;

// Máscara de valores monetários
function mascaraMoeda(input) {
  let value = input.value.replace(/\D/g, '');
  value = (value / 100).toFixed(2) + '';
  value = value.replace('.', ',');
  value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  input.value = value;
}

// Converte valor formatado (ex: "0,60" ou "1.234,56") para número
function converterMoedaParaNumero(valorFormatado) {
  if (!valorFormatado) return 0;
  // Remove pontos (separadores de milhar) e substitui vírgula por ponto
  let valor = valorFormatado.toString().replace(/\./g, '').replace(',', '.');
  return parseFloat(valor) || 0;
}

// Máscara de quantidade
function mascaraQuantidade(input) {
  let value = input.value.replace(/[^\d,]/g, '');
  value = value.replace(',', '.');
  if (value.split('.').length > 2) {
    value = value.substring(0, value.lastIndexOf('.'));
  }
  input.value = value;
}

function adicionarItem() {
  contadorItens++;
  const itemHtml = `
    <div class="item-pedido border border-gray-200 rounded-md p-3 mb-3 bg-gray-50" data-item-id="${contadorItens}">
      <!-- Linha 1: Produto, Dois Sabores, Qtd, Preço Unit., Subtotal -->
      <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
        <!-- Produto -->
        <div style="flex: 0 0 25%; min-width: 200px;">
          <label class="form-label text-xs font-medium text-gray-700">Produto</label>
          <select class="select2-select select-sm produto-select" data-item-id="${contadorItens}" onchange="atualizarItem(${contadorItens})" style="width: 100%;">
            <option value="">Selecione um produto</option>
            ${produtos.map(p => `<option value="${p.id_produto}" data-preco="${p.preco_venda || 0}">${p.codigo ? p.codigo + ' - ' : ''}${p.nome}</option>`).join('')}
          </select>
        </div>
        
        <!-- Dois Sabores / 2º Sabor -->
        <div style="flex: 0 0 25%; min-width: 200px;">
          <div class="dois-sabores-wrapper">
            <label class="cursor-pointer" style="display: flex; align-items: center; margin-top: 24px;">
              <input type="checkbox" class="checkbox checkbox-sm checkbox-primary meio-a-meio-checkbox" onchange="toggleMeioAMeio(${contadorItens})">
              <span class="text-xs font-medium text-gray-700" style="margin-left: 8px;">Dois Sabores</span>
            </label>
          </div>
          <div class="meio-a-meio-container" style="display: none;">
            <label class="form-label text-xs font-medium text-gray-700">2º Sabor</label>
            <select class="select2-select select-sm produto-meio-a-meio-select" data-item-id="${contadorItens}" onchange="atualizarItem(${contadorItens})" style="width: 100%;">
              <option value="">Selecione o 2º sabor</option>
              ${produtos.map(p => `<option value="${p.id_produto}" data-preco="${p.preco_venda || 0}">${p.codigo ? p.codigo + ' - ' : ''}${p.nome}</option>`).join('')}
            </select>
          </div>
        </div>
        
        <!-- Quantidade -->
        <div style="flex: 0 0 8%; min-width: 80px;">
          <label class="form-label text-xs font-medium text-gray-700">Qtd</label>
          <input type="text" class="input input-sm quantidade-input text-center" placeholder="1" value="1" onchange="atualizarItem(${contadorItens})" onkeyup="atualizarItem(${contadorItens})" oninput="mascaraQuantidade(this)" style="width: 100%;">
        </div>
        
        <!-- Preço Unit. -->
        <div style="flex: 0 0 15%; min-width: 120px;">
          <label class="form-label text-xs font-medium text-gray-700">Preço Unit.</label>
          <input type="text" class="input input-sm preco-input" placeholder="0,00" onchange="atualizarItem(${contadorItens})" onkeyup="atualizarItem(${contadorItens})" oninput="mascaraMoeda(this)" style="width: 100%;">
        </div>
        
        <!-- Subtotal -->
        <div style="flex: 0 0 20%; min-width: 150px;">
          <label class="form-label text-xs font-medium text-gray-700">Subtotal</label>
          <input type="text" class="input input-sm subtotal-input font-semibold text-primary" value="0,00" readonly style="width: 100%;">
        </div>
      </div>
      
      <!-- Linha 2: Observações e Lixeira -->
      <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; gap: 15px; flex-wrap: wrap;">
        <!-- Observações -->
        <div style="flex: 1; min-width: 200px;">
          <label class="form-label text-xs font-medium text-gray-700">Observações</label>
          <input type="text" class="input input-sm obs-item-input" placeholder="Ex: Sem cebola" onchange="atualizarItem(${contadorItens})" style="width: 100%;">
        </div>
        
        <!-- Lixeira -->
        <div style="flex: 0 0 auto;">
          <button type="button" class="btn btn-sm btn-icon btn-light-danger" onclick="removerItem(${contadorItens})" title="Remover item">
            <i class="ki-filled ki-trash"></i>
          </button>
        </div>
      </div>
    </div>
  `;
  
  document.getElementById('itens_container').insertAdjacentHTML('beforeend', itemHtml);
  
  // Inicializa Select2 nos novos selects
  const itemEl = document.querySelector(`[data-item-id="${contadorItens}"]`);
  const selects = itemEl.querySelectorAll('.select2-select');
  selects.forEach(select => {
    if (typeof initSelect2 === 'function') {
      initSelect2(select);
    } else if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
      jQuery(select).select2({
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
  });
  
  atualizarTotais();
}

function toggleMeioAMeio(id) {
  const itemEl = document.querySelector(`[data-item-id="${id}"]`);
  const checkbox = itemEl.querySelector('.meio-a-meio-checkbox');
  const wrapper = itemEl.querySelector('.dois-sabores-wrapper');
  const container = itemEl.querySelector('.meio-a-meio-container');
  const select = itemEl.querySelector('.produto-meio-a-meio-select');
  
  if (checkbox.checked) {
    wrapper.style.display = 'none';
    container.style.display = 'block';
    // Inicializa Select2 se ainda não foi inicializado
    if (select && typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
      if (!jQuery(select).hasClass('select2-hidden-accessible')) {
        jQuery(select).select2({
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
  } else {
    wrapper.style.display = 'block';
    container.style.display = 'none';
    if (select) {
      select.value = '';
      // Atualiza o Select2 se estiver inicializado
      if (typeof jQuery !== 'undefined' && jQuery(select).hasClass('select2-hidden-accessible')) {
        jQuery(select).val('').trigger('change');
      }
    }
    atualizarItem(id);
  }
}

function removerItem(id) {
  document.querySelector(`[data-item-id="${id}"]`).remove();
  itens = itens.filter(item => item.id !== id);
  atualizarItensJson();
  atualizarTotais();
}

function atualizarItem(id) {
  const itemEl = document.querySelector(`[data-item-id="${id}"]`);
  const produtoSelect = itemEl.querySelector('.produto-select');
  const produtoMeioAMeioSelect = itemEl.querySelector('.produto-meio-a-meio-select');
  const checkboxMeioAMeio = itemEl.querySelector('.meio-a-meio-checkbox');
  const quantidadeInput = itemEl.querySelector('.quantidade-input');
  const precoInput = itemEl.querySelector('.preco-input');
  const subtotalInput = itemEl.querySelector('.subtotal-input');
  const obsInput = itemEl.querySelector('.obs-item-input');
  
  const produtoId = produtoSelect.value;
  const produtoOption = produtoSelect.options[produtoSelect.selectedIndex];
  const precoPadrao = parseFloat(produtoOption.dataset.preco || 0);
  
  // Preenche preço se não tiver valor ou se o produto mudou
  if (precoPadrao > 0) {
    // Se não tem valor ou se o valor atual é diferente do padrão, atualiza
    if (!precoInput.value || converterMoedaParaNumero(precoInput.value) !== precoPadrao) {
      let precoFormatado = precoPadrao.toFixed(2).replace('.', ',');
      precoFormatado = precoFormatado.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      precoInput.value = precoFormatado;
    }
  }
  
  const quantidade = parseFloat(quantidadeInput.value.replace(',', '.') || 1);
  const preco = precoInput.value ? converterMoedaParaNumero(precoInput.value) : precoPadrao;
  const subtotal = quantidade * preco;
  
  // Formata o subtotal com máscara de moeda
  let subtotalFormatado = subtotal.toFixed(2).replace('.', ',');
  subtotalFormatado = subtotalFormatado.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  subtotalInput.value = subtotalFormatado;
  
  // Atualiza array de itens
  const itemIndex = itens.findIndex(item => item.id === id);
  const itemData = {
    id: id,
    id_produto: produtoId,
    id_produto_meio_a_meio: checkboxMeioAMeio.checked && produtoMeioAMeioSelect ? produtoMeioAMeioSelect.value : null,
    quantidade: quantidade,
    preco_unitario: preco,
    subtotal: subtotal,
    observacoes: obsInput ? obsInput.value : ''
  };
  
  if (itemIndex >= 0) {
    itens[itemIndex] = itemData;
  } else {
    itens.push(itemData);
  }
  
  atualizarItensJson();
  atualizarTotais();
}

function atualizarItensJson() {
  // Remove itens sem produto selecionado
  itens = itens.filter(item => item.id_produto);
  document.getElementById('itens_json').value = JSON.stringify(itens);
}

function atualizarTotais() {
  const subtotal = itens.reduce((sum, item) => sum + (item.subtotal || 0), 0);
  const desconto = document.getElementById('desconto').value ? converterMoedaParaNumero(document.getElementById('desconto').value) : 0;
  const taxaEntrega = document.getElementById('taxa_entrega').value ? converterMoedaParaNumero(document.getElementById('taxa_entrega').value) : 0;
  const total = subtotal - desconto + taxaEntrega;
  
  // Formata valores com máscara de moeda
  let subtotalFormatado = subtotal.toFixed(2).replace('.', ',');
  subtotalFormatado = subtotalFormatado.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  
  let totalFormatado = Math.max(0, total).toFixed(2).replace('.', ',');
  totalFormatado = totalFormatado.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  
  document.getElementById('subtotal').value = subtotalFormatado;
  document.getElementById('total').value = totalFormatado;
}

// Máscaras
document.getElementById('desconto')?.addEventListener('input', function(e) {
  mascaraMoeda(e.target);
  atualizarTotais();
});

document.getElementById('taxa_entrega')?.addEventListener('input', function(e) {
  mascaraMoeda(e.target);
  atualizarTotais();
});

document.getElementById('cliente_telefone')?.addEventListener('input', function(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length <= 11) {
    value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
  }
  e.target.value = value;
  
  // Busca cliente após digitar telefone (aguarda 1 segundo)
  if (value.length >= 10) {
    clearTimeout(window.buscaClienteTimeout);
    window.buscaClienteTimeout = setTimeout(() => {
      buscarClientePorTelefone(value);
    }, 1000);
  }
});

// Busca cliente por nome (aguarda 2 segundos após parar de digitar)
let buscaClienteTimeoutNome = null;
document.getElementById('cliente_nome')?.addEventListener('input', function(e) {
  const nome = e.target.value.trim();
  
  if (nome.length >= 3) {
    clearTimeout(buscaClienteTimeoutNome);
    buscaClienteTimeoutNome = setTimeout(() => {
      buscarClientePorNome(nome);
    }, 2000);
  }
});

// Função para buscar cliente por nome
function buscarClientePorNome(nome) {
  if (!nome || nome.length < 3) return;
  
  const nomeInput = document.getElementById('cliente_nome');
  const telefoneInput = document.getElementById('cliente_telefone');
  const enderecoInput = document.getElementById('cliente_endereco');
  
  // Só busca se o nome digitado for diferente do que já está preenchido
  if (nomeInput.value.trim().toLowerCase() !== nome.trim().toLowerCase()) {
    return;
  }
  
  fetch(`<?= base_url('Pedidos/buscar-cliente') ?>?nome=${encodeURIComponent(nome)}`)
    .then(response => response.json())
    .then(data => {
      if (data.encontrado) {
        // Preenche apenas campos vazios
        if (data.cliente_nome && !nomeInput.value.trim()) {
          nomeInput.value = data.cliente_nome;
        }
        if (data.cliente_telefone && !telefoneInput.value.trim()) {
          telefoneInput.value = data.cliente_telefone;
        }
        if (data.cliente_endereco && !enderecoInput.value.trim()) {
          enderecoInput.value = data.cliente_endereco;
        }
      }
    })
    .catch(error => {
      console.error('Erro ao buscar cliente:', error);
    });
}

// Função para buscar cliente por telefone
function buscarClientePorTelefone(telefone) {
  if (!telefone || telefone.replace(/\D/g, '').length < 10) return;
  
  const nomeInput = document.getElementById('cliente_nome');
  const telefoneInput = document.getElementById('cliente_telefone');
  const enderecoInput = document.getElementById('cliente_endereco');
  
  // Remove formatação para comparar
  const telefoneLimpo = telefone.replace(/\D/g, '');
  const telefoneAtualLimpo = telefoneInput.value.replace(/\D/g, '');
  
  // Só busca se o telefone digitado for diferente do que já está preenchido
  if (telefoneLimpo !== telefoneAtualLimpo) {
    return;
  }
  
  fetch(`<?= base_url('Pedidos/buscar-cliente') ?>?telefone=${encodeURIComponent(telefone)}`)
    .then(response => response.json())
    .then(data => {
      if (data.encontrado) {
        // Preenche apenas campos vazios
        if (data.cliente_nome && !nomeInput.value.trim()) {
          nomeInput.value = data.cliente_nome;
        }
        if (data.cliente_telefone && !telefoneInput.value.trim()) {
          telefoneInput.value = data.cliente_telefone;
        }
        if (data.cliente_endereco && !enderecoInput.value.trim()) {
          enderecoInput.value = data.cliente_endereco;
        }
      }
    })
    .catch(error => {
      console.error('Erro ao buscar cliente:', error);
    });
}

// Validação
document.getElementById('form_pedido')?.addEventListener('submit', function(e) {
  atualizarItensJson();
  if (itens.length === 0) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, adicione pelo menos um item ao pedido.');
    return false;
  }
});

// Adiciona primeiro item automaticamente
adicionarItem();
</script>
</main>
