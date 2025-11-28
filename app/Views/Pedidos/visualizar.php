<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Pedido #<?= esc($pedido['numero_pedido']) ?>
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Pedidos') ?>">
          Pedidos
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900">Visualizar</span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-light" href="<?= base_url('Pedidos') ?>">
        <i class="ki-filled ki-cross !text-base"></i>
        Voltar
      </a>
    </div>
  </div>
</div>
<!-- End of Toolbar -->

<!-- Container -->
<div class="container-fixed" style="max-width: 1600px !important;">
  <div class="grid gap-5 lg:gap-7.5">
    <!-- Dados do Pedido -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          Dados do Pedido
        </h3>
      </div>
      <div class="card-body">
        <div class="grid lg:grid-cols-2 gap-5 lg:gap-7.5">
          <div>
            <span class="text-sm text-gray-500">Número do Pedido:</span>
            <p class="text-sm font-medium text-gray-900">#<?= esc($pedido['numero_pedido']) ?></p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Data:</span>
            <p class="text-sm font-medium text-gray-900"><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Cliente:</span>
            <p class="text-sm font-medium text-gray-900"><?= esc($pedido['cliente_nome'] ?? 'Não informado') ?></p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Telefone:</span>
            <p class="text-sm font-medium text-gray-900"><?= esc($pedido['cliente_telefone'] ?? 'Não informado') ?></p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Tipo:</span>
            <p class="text-sm font-medium text-gray-900">
              <?php
              $tipoLabels = [
                'BALCAO' => 'Balcão',
                'DELIVERY' => 'Delivery',
                'RETIRADA' => 'Retirada'
              ];
              echo $tipoLabels[$pedido['tipo_pedido']] ?? $pedido['tipo_pedido'];
              ?>
            </p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Status:</span>
            <p class="text-sm font-medium text-gray-900">
              <?php
              $statusClass = '';
              $statusLabel = '';
              switch($pedido['status']) {
                case 'ABERTO':
                  $statusClass = 'badge-warning';
                  $statusLabel = 'Aberto';
                  break;
                case 'PREPARANDO':
                  $statusClass = 'badge-info';
                  $statusLabel = 'Preparando';
                  break;
                case 'PRONTO':
                  $statusClass = 'badge-primary';
                  $statusLabel = 'Pronto';
                  break;
                case 'ENTREGUE':
                  $statusClass = 'badge-success';
                  $statusLabel = 'Entregue';
                  break;
                case 'CANCELADO':
                  $statusClass = 'badge-danger';
                  $statusLabel = 'Cancelado';
                  break;
                default:
                  $statusClass = 'badge';
                  $statusLabel = $pedido['status'];
              }
              ?>
              <span class="badge badge-sm <?= $statusClass ?>"><?= $statusLabel ?></span>
            </p>
          </div>
          <?php if ($pedido['cliente_endereco']): ?>
          <div class="lg:col-span-2">
            <span class="text-sm text-gray-500">Endereço:</span>
            <p class="text-sm font-medium text-gray-900"><?= esc($pedido['cliente_endereco']) ?></p>
          </div>
          <?php endif; ?>
          <?php if ($pedido['observacoes']): ?>
          <div class="lg:col-span-2">
            <span class="text-sm text-gray-500">Observações:</span>
            <p class="text-sm font-medium text-gray-900"><?= esc($pedido['observacoes']) ?></p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Itens do Pedido -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          Itens do Pedido
        </h3>
      </div>
      <div class="card-body">
        <div class="scrollable-x-auto">
          <table class="table table-auto table-border">
            <thead>
              <tr>
                <th class="min-w-[250px]">Produto</th>
                <th class="min-w-[100px]">Quantidade</th>
                <th class="min-w-[120px]">Preço Unit.</th>
                <th class="min-w-[120px]">Subtotal</th>
                <th class="min-w-[150px]">Observações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($itens)): ?>
                <tr>
                  <td colspan="5" class="text-center py-10 text-gray-500">
                    Nenhum item encontrado.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($itens as $item): ?>
                  <tr>
                    <td>
                      <div class="flex flex-col gap-1">
                        <div>
                          <?= esc($item['produto_cadastrado'] ?? $item['nome_produto'] ?? 'Produto não cadastrado') ?>
                          <?php if ($item['produto_codigo']): ?>
                            <span class="text-xs text-gray-500">(Cód: <?= esc($item['produto_codigo']) ?>)</span>
                          <?php endif; ?>
                        </div>
                        <?php if ($item['id_produto_meio_a_meio'] || $item['nome_produto_meio_a_meio']): ?>
                          <div class="text-xs text-primary font-medium">
                            <i class="ki-filled ki-arrow-right"></i>
                            Meio a Meio: <?= esc($item['produto_meio_a_meio_cadastrado'] ?? $item['nome_produto_meio_a_meio'] ?? '') ?>
                            <?php if ($item['produto_meio_a_meio_codigo']): ?>
                              <span class="text-gray-500">(Cód: <?= esc($item['produto_meio_a_meio_codigo']) ?>)</span>
                            <?php endif; ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    </td>
                    <td class="text-gray-800 font-medium">
                      <?= number_format($item['quantidade'] ?? 0, 3, ',', '.') ?>
                    </td>
                    <td class="text-gray-800 font-medium">
                      R$ <?= number_format($item['preco_unitario'] ?? 0, 2, ',', '.') ?>
                    </td>
                    <td class="text-gray-800 font-medium">
                      R$ <?= number_format($item['subtotal'] ?? 0, 2, ',', '.') ?>
                    </td>
                    <td class="text-sm text-gray-600">
                      <?= esc($item['observacoes'] ?? '-') ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Totais -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          Totais
        </h3>
      </div>
      <div class="card-body">
        <div class="grid lg:grid-cols-2 gap-5 lg:gap-7.5">
          <div>
            <span class="text-sm text-gray-500">Subtotal:</span>
            <p class="text-lg font-medium text-gray-900">R$ <?= number_format($pedido['subtotal'] ?? 0, 2, ',', '.') ?></p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Desconto:</span>
            <p class="text-lg font-medium text-gray-900">R$ <?= number_format($pedido['desconto'] ?? 0, 2, ',', '.') ?></p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Taxa de Entrega:</span>
            <p class="text-lg font-medium text-gray-900">R$ <?= number_format($pedido['taxa_entrega'] ?? 0, 2, ',', '.') ?></p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Total:</span>
            <p class="text-xl font-bold text-primary">R$ <?= number_format($pedido['total'] ?? 0, 2, ',', '.') ?></p>
          </div>
          <?php if ($pedido['forma_pagamento']): ?>
          <div>
            <span class="text-sm text-gray-500">Forma de Pagamento:</span>
            <p class="text-sm font-medium text-gray-900"><?= esc($pedido['forma_pagamento']) ?></p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->
</main>

