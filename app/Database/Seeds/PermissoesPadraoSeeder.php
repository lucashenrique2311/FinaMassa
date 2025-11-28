<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissoesPadraoSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Lista de permissões padrão do sistema
        $permissoes = [
            // Dashboard
            ['nome' => 'Visualizar Dashboard', 'descricao' => 'Permite visualizar o dashboard', 'modulo' => 'dashboard', 'acao' => 'visualizar'],
            
            // Usuários
            ['nome' => 'Visualizar Usuários', 'descricao' => 'Permite visualizar a lista de usuários', 'modulo' => 'usuarios', 'acao' => 'visualizar'],
            ['nome' => 'Criar Usuários', 'descricao' => 'Permite criar novos usuários', 'modulo' => 'usuarios', 'acao' => 'criar'],
            ['nome' => 'Editar Usuários', 'descricao' => 'Permite editar usuários existentes', 'modulo' => 'usuarios', 'acao' => 'editar'],
            ['nome' => 'Excluir Usuários', 'descricao' => 'Permite excluir usuários', 'modulo' => 'usuarios', 'acao' => 'excluir'],
            
            // Produtos
            ['nome' => 'Visualizar Produtos', 'descricao' => 'Permite visualizar a lista de produtos', 'modulo' => 'produtos', 'acao' => 'visualizar'],
            ['nome' => 'Criar Produtos', 'descricao' => 'Permite criar novos produtos', 'modulo' => 'produtos', 'acao' => 'criar'],
            ['nome' => 'Editar Produtos', 'descricao' => 'Permite editar produtos existentes', 'modulo' => 'produtos', 'acao' => 'editar'],
            ['nome' => 'Excluir Produtos', 'descricao' => 'Permite excluir produtos', 'modulo' => 'produtos', 'acao' => 'excluir'],
            
            // Categorias de Produtos
            ['nome' => 'Visualizar Categorias', 'descricao' => 'Permite visualizar categorias de produtos', 'modulo' => 'categorias_produto', 'acao' => 'visualizar'],
            ['nome' => 'Criar Categorias', 'descricao' => 'Permite criar novas categorias', 'modulo' => 'categorias_produto', 'acao' => 'criar'],
            ['nome' => 'Editar Categorias', 'descricao' => 'Permite editar categorias existentes', 'modulo' => 'categorias_produto', 'acao' => 'editar'],
            ['nome' => 'Excluir Categorias', 'descricao' => 'Permite excluir categorias', 'modulo' => 'categorias_produto', 'acao' => 'excluir'],
            
            // Ingredientes Padrão
            ['nome' => 'Visualizar Ingredientes Padrão', 'descricao' => 'Permite visualizar ingredientes padrão', 'modulo' => 'ingredientes_padrao', 'acao' => 'visualizar'],
            ['nome' => 'Criar Ingredientes Padrão', 'descricao' => 'Permite criar novos ingredientes padrão', 'modulo' => 'ingredientes_padrao', 'acao' => 'criar'],
            ['nome' => 'Editar Ingredientes Padrão', 'descricao' => 'Permite editar ingredientes padrão existentes', 'modulo' => 'ingredientes_padrao', 'acao' => 'editar'],
            ['nome' => 'Excluir Ingredientes Padrão', 'descricao' => 'Permite excluir ingredientes padrão', 'modulo' => 'ingredientes_padrao', 'acao' => 'excluir'],
            
            // Fornecedores
            ['nome' => 'Visualizar Fornecedores', 'descricao' => 'Permite visualizar a lista de fornecedores', 'modulo' => 'fornecedores', 'acao' => 'visualizar'],
            ['nome' => 'Criar Fornecedores', 'descricao' => 'Permite criar novos fornecedores', 'modulo' => 'fornecedores', 'acao' => 'criar'],
            ['nome' => 'Editar Fornecedores', 'descricao' => 'Permite editar fornecedores existentes', 'modulo' => 'fornecedores', 'acao' => 'editar'],
            ['nome' => 'Excluir Fornecedores', 'descricao' => 'Permite excluir fornecedores', 'modulo' => 'fornecedores', 'acao' => 'excluir'],
            
            // Depósitos
            ['nome' => 'Visualizar Depósitos', 'descricao' => 'Permite visualizar a lista de depósitos', 'modulo' => 'depositos', 'acao' => 'visualizar'],
            ['nome' => 'Criar Depósitos', 'descricao' => 'Permite criar novos depósitos', 'modulo' => 'depositos', 'acao' => 'criar'],
            ['nome' => 'Editar Depósitos', 'descricao' => 'Permite editar depósitos existentes', 'modulo' => 'depositos', 'acao' => 'editar'],
            ['nome' => 'Excluir Depósitos', 'descricao' => 'Permite excluir depósitos', 'modulo' => 'depositos', 'acao' => 'excluir'],
            
            // Estoque
            ['nome' => 'Visualizar Estoque', 'descricao' => 'Permite visualizar o controle de estoque', 'modulo' => 'estoque', 'acao' => 'visualizar'],
            ['nome' => 'Registrar Entrada', 'descricao' => 'Permite registrar entrada de estoque', 'modulo' => 'estoque', 'acao' => 'criar'],
            ['nome' => 'Registrar Saída', 'descricao' => 'Permite registrar saída de estoque', 'modulo' => 'estoque', 'acao' => 'editar'],
            ['nome' => 'Ajustar Estoque', 'descricao' => 'Permite fazer ajustes no estoque', 'modulo' => 'estoque', 'acao' => 'excluir'],
            ['nome' => 'Visualizar Histórico', 'descricao' => 'Permite visualizar histórico de movimentações', 'modulo' => 'estoque', 'acao' => 'visualizar'],
            
            // Pedidos de Venda
            ['nome' => 'Visualizar Pedidos', 'descricao' => 'Permite visualizar a lista de pedidos', 'modulo' => 'pedidos', 'acao' => 'visualizar'],
            ['nome' => 'Criar Pedidos', 'descricao' => 'Permite criar novos pedidos de venda', 'modulo' => 'pedidos', 'acao' => 'criar'],
            ['nome' => 'Editar Pedidos', 'descricao' => 'Permite editar pedidos existentes', 'modulo' => 'pedidos', 'acao' => 'editar'],
            ['nome' => 'Excluir Pedidos', 'descricao' => 'Permite excluir pedidos', 'modulo' => 'pedidos', 'acao' => 'excluir'],
            
            // Relatórios
            ['nome' => 'Visualizar Relatório de Pedidos', 'descricao' => 'Permite visualizar relatórios de pedidos', 'modulo' => 'relatorios', 'acao' => 'visualizar'],
            ['nome' => 'Visualizar Relatório de Estoque', 'descricao' => 'Permite visualizar relatórios de estoque', 'modulo' => 'relatorios', 'acao' => 'visualizar'],
            
            // Permissões
            ['nome' => 'Visualizar Permissões', 'descricao' => 'Permite visualizar a lista de permissões', 'modulo' => 'permissoes', 'acao' => 'visualizar'],
            ['nome' => 'Criar Permissões', 'descricao' => 'Permite criar novas permissões', 'modulo' => 'permissoes', 'acao' => 'criar'],
            ['nome' => 'Editar Permissões', 'descricao' => 'Permite editar permissões existentes', 'modulo' => 'permissoes', 'acao' => 'editar'],
            ['nome' => 'Excluir Permissões', 'descricao' => 'Permite excluir permissões', 'modulo' => 'permissoes', 'acao' => 'excluir'],
            ['nome' => 'Atribuir Permissões', 'descricao' => 'Permite atribuir permissões a usuários', 'modulo' => 'permissoes', 'acao' => 'editar'],
        ];
        
        // Insere as permissões
        foreach ($permissoes as $permissao) {
            // Verifica se já existe
            $builder = $db->table('permissoes');
            $builder->where('modulo', $permissao['modulo']);
            $builder->where('acao', $permissao['acao']);
            $existe = $builder->countAllResults() > 0;
            
            if (!$existe) {
                $permissao['created_at'] = date('Y-m-d H:i:s');
                $permissao['updated_at'] = date('Y-m-d H:i:s');
                $db->table('permissoes')->insert($permissao);
            }
        }
        
        echo "Permissões padrão criadas com sucesso!\n";
    }
}

