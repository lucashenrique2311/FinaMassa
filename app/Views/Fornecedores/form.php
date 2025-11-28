<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        <?= $fornecedor_data ? 'Editar Fornecedor' : 'Novo Fornecedor' ?>
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Fornecedores') ?>">
          Fornecedores
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900"><?= $fornecedor_data ? 'Editar' : 'Novo' ?></span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-light" href="<?= base_url('Fornecedores') ?>">
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
          <?= $fornecedor_data ? 'Editar Fornecedor' : 'Novo Fornecedor' ?>
        </h3>
      </div>
      <div class="card-body">
        <form action="<?= $fornecedor_data ? base_url('Fornecedores/atualizar/' . $fornecedor_data['id_fornecedor']) : base_url('Fornecedores/salvar') ?>" method="post" id="form_fornecedor">
          <?= csrf_field() ?>
          
          <div class="grid lg:grid-cols-2 gap-5 lg:gap-7.5">
            <!-- Razão Social -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="razao_social">
                Razão Social <span class="text-red-500">*</span>
              </label>
              <label class="input">
                <i class="ki-filled ki-abstract-26"></i>
                <input 
                  type="text" 
                  id="razao_social" 
                  name="razao_social" 
                  placeholder="Razão Social" 
                  value="<?= old('razao_social', $fornecedor_data['razao_social'] ?? '') ?>" 
                  required
                />
              </label>
            </div>

            <!-- Nome Fantasia -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="nome_fantasia">
                Nome Fantasia
              </label>
              <label class="input">
                <i class="ki-filled ki-abstract-26"></i>
                <input 
                  type="text" 
                  id="nome_fantasia" 
                  name="nome_fantasia" 
                  placeholder="Nome Fantasia" 
                  value="<?= old('nome_fantasia', $fornecedor_data['nome_fantasia'] ?? '') ?>"
                />
              </label>
            </div>

            <!-- CNPJ -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="cnpj">
                CNPJ
              </label>
              <label class="input">
                <i class="ki-filled ki-hash"></i>
                <input 
                  type="text" 
                  id="cnpj" 
                  name="cnpj" 
                  placeholder="00.000.000/0000-00" 
                  value="<?= old('cnpj', !empty($fornecedor_data['cnpj']) ? formatar_cnpj($fornecedor_data['cnpj']) : '') ?>"
                />
              </label>
            </div>

            <!-- CPF -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="cpf">
                CPF
              </label>
              <label class="input">
                <i class="ki-filled ki-profile-user"></i>
                <input 
                  type="text" 
                  id="cpf" 
                  name="cpf" 
                  placeholder="000.000.000-00" 
                  value="<?= old('cpf', !empty($fornecedor_data['cpf']) ? formatar_cpf($fornecedor_data['cpf']) : '') ?>"
                />
              </label>
            </div>

            <!-- Inscrição Estadual -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="inscricao_estadual">
                Inscrição Estadual
              </label>
              <label class="input">
                <i class="ki-filled ki-file"></i>
                <input 
                  type="text" 
                  id="inscricao_estadual" 
                  name="inscricao_estadual" 
                  placeholder="Inscrição Estadual" 
                  value="<?= old('inscricao_estadual', $fornecedor_data['inscricao_estadual'] ?? '') ?>"
                />
              </label>
            </div>

            <!-- Telefone -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="telefone">
                Telefone
              </label>
              <label class="input">
                <i class="ki-filled ki-phone"></i>
                <input 
                  type="text" 
                  id="telefone" 
                  name="telefone" 
                  placeholder="(00) 0000-0000" 
                  value="<?= old('telefone', $fornecedor_data['telefone'] ?? '') ?>"
                />
              </label>
            </div>

            <!-- Celular -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="celular">
                Celular
              </label>
              <label class="input">
                <i class="ki-filled ki-phone"></i>
                <input 
                  type="text" 
                  id="celular" 
                  name="celular" 
                  placeholder="(00) 00000-0000" 
                  value="<?= old('celular', $fornecedor_data['celular'] ?? '') ?>"
                />
              </label>
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="email">
                Email
              </label>
              <label class="input">
                <i class="ki-filled ki-sms"></i>
                <input 
                  type="email" 
                  id="email" 
                  name="email" 
                  placeholder="email@exemplo.com" 
                  value="<?= old('email', $fornecedor_data['email'] ?? '') ?>"
                />
              </label>
            </div>

            <!-- CEP -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="cep">
                CEP
              </label>
              <div class="flex gap-2">
                <label class="input flex-1">
                  <i class="ki-filled ki-geolocation"></i>
                  <input 
                    type="text" 
                    id="cep" 
                    name="cep" 
                    placeholder="00000-000" 
                    value="<?= old('cep', $fornecedor_data['cep'] ?? '') ?>"
                    maxlength="9"
                    onblur="buscarCep()"
                  />
                </label>
                <button type="button" 
                        class="btn btn-sm btn-light" 
                        onclick="buscarCep()"
                        id="btn_buscar_cep">
                  <i class="ki-filled ki-magnifier"></i>
                  Buscar
                </button>
              </div>
            </div>

            <!-- Endereço -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="endereco">
                Endereço
              </label>
              <label class="input">
                <i class="ki-filled ki-geolocation"></i>
                <input 
                  type="text" 
                  id="endereco" 
                  name="endereco" 
                  placeholder="Rua, Avenida, etc" 
                  value="<?= old('endereco', $fornecedor_data['endereco'] ?? '') ?>"
                />
              </label>
            </div>

            <!-- Número -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="numero">
                Número
              </label>
              <label class="input">
                <i class="ki-filled ki-hash"></i>
                <input 
                  type="text" 
                  id="numero" 
                  name="numero" 
                  placeholder="Número" 
                  value="<?= old('numero', $fornecedor_data['numero'] ?? '') ?>"
                />
              </label>
            </div>

            <!-- Complemento -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="complemento">
                Complemento
              </label>
              <label class="input">
                <i class="ki-filled ki-home"></i>
                <input 
                  type="text" 
                  id="complemento" 
                  name="complemento" 
                  placeholder="Complemento" 
                  value="<?= old('complemento', $fornecedor_data['complemento'] ?? '') ?>"
                />
              </label>
            </div>

            <!-- Bairro -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="bairro">
                Bairro
              </label>
              <label class="input">
                <i class="ki-filled ki-geolocation"></i>
                <input 
                  type="text" 
                  id="bairro" 
                  name="bairro" 
                  placeholder="Bairro" 
                  value="<?= old('bairro', $fornecedor_data['bairro'] ?? '') ?>"
                />
              </label>
            </div>

            <!-- Cidade -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="cidade">
                Cidade
              </label>
              <label class="input">
                <i class="ki-filled ki-geolocation"></i>
                <input 
                  type="text" 
                  id="cidade" 
                  name="cidade" 
                  placeholder="Cidade" 
                  value="<?= old('cidade', $fornecedor_data['cidade'] ?? '') ?>"
                />
              </label>
            </div>

            <!-- Estado -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="estado">
                Estado
              </label>
              <select id="estado" name="estado" class="select">
                <option value="">Selecione</option>
                <?php
                $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
                foreach ($estados as $uf):
                ?>
                  <option value="<?= $uf ?>" <?= old('estado', $fornecedor_data['estado'] ?? '') == $uf ? 'selected' : '' ?>>
                    <?= $uf ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Observações -->
            <div class="flex flex-col gap-1 lg:col-span-2">
              <label class="form-label font-normal text-gray-900" for="observacoes">
                Observações
              </label>
              <textarea 
                id="observacoes" 
                name="observacoes" 
                class="textarea" 
                rows="3"
                placeholder="Observações sobre o fornecedor"
              ><?= old('observacoes', $fornecedor_data['observacoes'] ?? '') ?></textarea>
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
                  <?= old('ativo', $fornecedor_data['ativo'] ?? 1) ? 'checked' : '' ?>
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
            <a class="btn btn-light" href="<?= base_url('Fornecedores') ?>">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="ki-filled ki-check"></i>
              <?= $fornecedor_data ? 'Atualizar' : 'Salvar' ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->

<script>
// Máscara de CNPJ
document.getElementById('cnpj')?.addEventListener('input', function(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length <= 14) {
    value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
  }
  e.target.value = value;
});

// Máscara de CPF
document.getElementById('cpf')?.addEventListener('input', function(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length <= 11) {
    value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
  }
  e.target.value = value;
});

// Máscara de CEP
document.getElementById('cep')?.addEventListener('input', function(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length <= 8) {
    value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
  }
  e.target.value = value;
});

// Máscara de telefone
document.getElementById('telefone')?.addEventListener('input', function(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length <= 10) {
    value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
  }
  e.target.value = value;
});

// Máscara de celular
document.getElementById('celular')?.addEventListener('input', function(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length <= 11) {
    value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
  }
  e.target.value = value;
});

// Buscar CEP
function buscarCep() {
  const cepInput = document.getElementById('cep');
  const btnBuscar = document.getElementById('btn_buscar_cep');
  
  if (!cepInput) return;
  
  let cep = cepInput.value.replace(/\D/g, '');
  
  if (cep.length !== 8) {
    SwalWarning('Atenção!', 'CEP deve conter 8 dígitos.');
    return;
  }
  
  // Mostra loading
  if (btnBuscar) {
    btnBuscar.disabled = true;
    btnBuscar.innerHTML = '<i class="ki-filled ki-loading"></i> Buscando...';
  }
  
  // Busca CEP
  fetch('<?= base_url('Fornecedores/buscar-cep') ?>?cep=' + cep)
    .then(response => response.json())
    .then(data => {
      if (data.erro) {
        SwalError('Erro!', data.mensagem || 'CEP não encontrado.');
      } else {
        // Preenche campos
        if (data.endereco) document.getElementById('endereco').value = data.endereco;
        if (data.bairro) document.getElementById('bairro').value = data.bairro;
        if (data.cidade) document.getElementById('cidade').value = data.cidade;
        if (data.estado) document.getElementById('estado').value = data.estado;
        if (data.complemento) document.getElementById('complemento').value = data.complemento;
        
        SwalSuccess('Sucesso!', 'CEP encontrado e campos preenchidos.');
      }
    })
    .catch(error => {
      SwalError('Erro!', 'Erro ao buscar CEP. Tente novamente.');
    })
    .finally(() => {
      if (btnBuscar) {
        btnBuscar.disabled = false;
        btnBuscar.innerHTML = '<i class="ki-filled ki-magnifier"></i> Buscar';
      }
    });
}

// Validação de CNPJ/CPF no frontend
function validarCnpjCpf() {
  const cnpj = document.getElementById('cnpj')?.value.replace(/\D/g, '') || '';
  const cpf = document.getElementById('cpf')?.value.replace(/\D/g, '') || '';
  
  if (cnpj && cnpj.length === 14) {
    // Validação básica de CNPJ (pode ser melhorada)
    if (!validarCNPJ(cnpj)) {
      SwalWarning('Atenção!', 'CNPJ inválido. Verifique os dígitos.');
      return false;
    }
  }
  
  if (cpf && cpf.length === 11) {
    // Validação básica de CPF (pode ser melhorada)
    if (!validarCPF(cpf)) {
      SwalWarning('Atenção!', 'CPF inválido. Verifique os dígitos.');
      return false;
    }
  }
  
  return true;
}

// Funções auxiliares de validação (simplificadas para frontend)
function validarCNPJ(cnpj) {
  if (cnpj.length !== 14) return false;
  if (/^(\d)\1+$/.test(cnpj)) return false;
  // Validação completa seria muito longa, deixamos para o backend
  return true;
}

function validarCPF(cpf) {
  if (cpf.length !== 11) return false;
  if (/^(\d)\1+$/.test(cpf)) return false;
  // Validação completa seria muito longa, deixamos para o backend
  return true;
}

// Validação do formulário
document.getElementById('form_fornecedor')?.addEventListener('submit', function(e) {
  const razao_social = document.getElementById('razao_social').value.trim();
  
  if (!razao_social) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, preencha a Razão Social.');
    return false;
  }
  
  // Valida CNPJ/CPF
  if (!validarCnpjCpf()) {
    e.preventDefault();
    return false;
  }
});
</script>
</main>

