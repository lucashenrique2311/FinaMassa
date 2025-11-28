<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->post('/Login/validaLogin', 'Login::validaLogin');
$routes->get('/Login/sair', 'Login::sair');

// Imagens
$routes->get('imagens/produto/(:segment)', 'Imagens::produto/$1');

// Dashboard
$routes->get('/Dashboard', 'Dashboard::index');

// Usuários
$routes->group('Usuarios', function($routes) {
    $routes->get('/', 'Usuarios::index');
    $routes->get('criar', 'Usuarios::criar');
    $routes->get('editar/(:num)', 'Usuarios::editar/$1');
    $routes->post('salvar', 'Usuarios::salvar');
    $routes->post('atualizar/(:num)', 'Usuarios::atualizar/$1');
    $routes->get('excluir/(:num)', 'Usuarios::excluir/$1');
    $routes->get('api', 'Usuarios::api');
});

// Produtos
$routes->group('Produtos', function($routes) {
    $routes->get('/', 'Produtos::index');
    $routes->get('criar', 'Produtos::criar');
    $routes->get('editar/(:num)', 'Produtos::editar/$1');
    $routes->post('salvar', 'Produtos::salvar');
    $routes->post('atualizar/(:num)', 'Produtos::atualizar/$1');
    $routes->get('excluir/(:num)', 'Produtos::excluir/$1');
});

// Fornecedores
$routes->group('Fornecedores', function($routes) {
    $routes->get('/', 'Fornecedores::index');
    $routes->get('criar', 'Fornecedores::criar');
    $routes->get('editar/(:num)', 'Fornecedores::editar/$1');
    $routes->post('salvar', 'Fornecedores::salvar');
    $routes->post('atualizar/(:num)', 'Fornecedores::atualizar/$1');
    $routes->get('excluir/(:num)', 'Fornecedores::excluir/$1');
    $routes->get('buscar-cep', 'Fornecedores::buscarCep');
});

// Depósitos
$routes->group('Depositos', function($routes) {
    $routes->get('/', 'Depositos::index');
    $routes->get('criar', 'Depositos::criar');
    $routes->get('editar/(:num)', 'Depositos::editar/$1');
    $routes->post('salvar', 'Depositos::salvar');
    $routes->post('atualizar/(:num)', 'Depositos::atualizar/$1');
    $routes->get('excluir/(:num)', 'Depositos::excluir/$1');
});

// Categorias de Produtos
$routes->group('CategoriasProduto', function($routes) {
    $routes->get('/', 'CategoriasProduto::index');
    $routes->get('criar', 'CategoriasProduto::criar');
    $routes->get('editar/(:num)', 'CategoriasProduto::editar/$1');
    $routes->post('salvar', 'CategoriasProduto::salvar');
    $routes->post('atualizar/(:num)', 'CategoriasProduto::atualizar/$1');
    $routes->get('excluir/(:num)', 'CategoriasProduto::excluir/$1');
});

// Ingredientes Padrão
$routes->group('IngredientesPadrao', function($routes) {
    $routes->get('/', 'IngredientesPadrao::index');
    $routes->get('criar', 'IngredientesPadrao::criar');
    $routes->get('editar/(:num)', 'IngredientesPadrao::editar/$1');
    $routes->post('salvar', 'IngredientesPadrao::salvar');
    $routes->post('atualizar/(:num)', 'IngredientesPadrao::atualizar/$1');
    $routes->get('excluir/(:num)', 'IngredientesPadrao::excluir/$1');
});

// Estoque
$routes->group('Estoque', function($routes) {
    $routes->get('/', 'Estoque::index');
    $routes->get('entrada', 'Estoque::entrada');
    $routes->get('saida', 'Estoque::saida');
    $routes->get('ajuste', 'Estoque::ajuste');
    $routes->get('historico', 'Estoque::historico');
    $routes->get('get-estoque-atual', 'Estoque::getEstoqueAtual');
    $routes->get('get-estoque-atual-ingrediente', 'Estoque::getEstoqueAtualIngrediente');
    $routes->post('registrar-entrada', 'Estoque::registrarEntrada');
    $routes->post('registrar-saida', 'Estoque::registrarSaida');
    $routes->post('registrar-ajuste', 'Estoque::registrarAjuste');
});

// Pedidos
$routes->group('Pedidos', function($routes) {
    $routes->get('/', 'Pedidos::index');
    $routes->get('novo', 'Pedidos::novo');
    $routes->get('visualizar/(:num)', 'Pedidos::visualizar/$1');
    $routes->get('buscar-cliente', 'Pedidos::buscarCliente');
    $routes->post('salvar', 'Pedidos::salvar');
    $routes->post('atualizar-status/(:num)', 'Pedidos::atualizarStatus/$1');
    $routes->post('alterar-status-lote', 'Pedidos::alterarStatusLote');
    $routes->post('excluir-lote', 'Pedidos::excluirLote');
    $routes->get('excluir/(:num)', 'Pedidos::excluir/$1');
});

// Relatórios
$routes->get('Relatorios/pedidos', 'Relatorios::pedidos');
$routes->get('Relatorios/estoque', 'Relatorios::estoque');
$routes->get('Relatorios/produtos', 'Relatorios::produtos');

// Permissões
$routes->group('Permissoes', function($routes) {
    $routes->get('/', 'Permissoes::index');
    $routes->get('criar', 'Permissoes::criar');
    $routes->get('editar/(:num)', 'Permissoes::editar/$1');
    $routes->post('salvar', 'Permissoes::salvar');
    $routes->post('atualizar/(:num)', 'Permissoes::atualizar/$1');
    $routes->get('excluir/(:num)', 'Permissoes::excluir/$1');
});

// Permissões de Usuários
$routes->group('UsuarioPermissoes', function($routes) {
    $routes->get('atribuir/(:num)', 'UsuarioPermissoes::atribuir/$1');
    $routes->post('salvar', 'UsuarioPermissoes::salvar');
});
