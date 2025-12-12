-- ============================================
-- Script para Limpar Banco de Dados (Versão Segura)
-- FinaMassa - Sistema de Gestão
-- ============================================
-- 
-- Este script limpa todos os dados operacionais,
-- mantendo apenas:
-- - Usuários
-- - Permissões
-- - Tabela de migrations
--
-- ATENÇÃO: Este script é DESTRUTIVO!
-- Faça backup antes de executar!
-- ============================================

-- Verificar se as tabelas existem antes de limpar
SET @tables_exist = (
    SELECT COUNT(*) 
    FROM information_schema.tables 
    WHERE table_schema = DATABASE()
    AND table_name IN (
        'itens_pedido', 'pedidos_venda', 'movimentacoes_estoque',
        'estoque', 'produto_composicao', 'produtos',
        'fornecedores', 'depositos'
    )
);

-- Se todas as tabelas existem, proceder com a limpeza
SET @proceed = IF(@tables_exist = 8, 1, 0);

-- Desabilitar verificação de chaves estrangeiras temporariamente
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- LIMPAR DADOS OPERACIONAIS
-- ============================================

-- Limpar itens de pedidos
DELETE FROM itens_pedido WHERE 1=1;

-- Limpar pedidos de venda
DELETE FROM pedidos_venda WHERE 1=1;

-- Limpar movimentações de estoque
DELETE FROM movimentacoes_estoque WHERE 1=1;

-- Limpar estoque
DELETE FROM estoque WHERE 1=1;

-- Limpar composições de produtos
DELETE FROM produto_composicao WHERE 1=1;

-- Limpar produtos
DELETE FROM produtos WHERE 1=1;

-- Limpar fornecedores
DELETE FROM fornecedores WHERE 1=1;

-- Limpar depósitos
DELETE FROM depositos WHERE 1=1;

-- Limpar categorias de produtos
DELETE FROM categorias_produto WHERE 1=1;

-- Limpar ingredientes padrão
DELETE FROM ingredientes_padrao WHERE 1=1;

-- ============================================
-- RESETAR AUTO_INCREMENT
-- ============================================

ALTER TABLE itens_pedido AUTO_INCREMENT = 1;
ALTER TABLE pedidos_venda AUTO_INCREMENT = 1;
ALTER TABLE movimentacoes_estoque AUTO_INCREMENT = 1;
ALTER TABLE estoque AUTO_INCREMENT = 1;
ALTER TABLE produto_composicao AUTO_INCREMENT = 1;
ALTER TABLE produtos AUTO_INCREMENT = 1;
ALTER TABLE fornecedores AUTO_INCREMENT = 1;
ALTER TABLE depositos AUTO_INCREMENT = 1;
ALTER TABLE categorias_produto AUTO_INCREMENT = 1;
ALTER TABLE ingredientes_padrao AUTO_INCREMENT = 1;

-- Reabilitar verificação de chaves estrangeiras
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- RESUMO
-- ============================================
SELECT 
    'Limpeza concluída com sucesso!' AS Status,
    'Dados operacionais removidos' AS Detalhes,
    'Apenas usuários e permissões mantidos' AS Observacao;

