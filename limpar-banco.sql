-- ============================================
-- Script para Limpar Banco de Dados
-- FinaMassa - Sistema de Gestão
-- ============================================
-- 
-- Este script limpa todos os dados operacionais,
-- mantendo apenas:
-- - Usuários
-- - Permissões
-- - Configurações (categorias, ingredientes padrão)
-- - Tabela de migrations
--
-- ATENÇÃO: Este script é DESTRUTIVO!
-- Faça backup antes de executar!
-- ============================================

-- Desabilitar verificação de chaves estrangeiras temporariamente
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- LIMPAR DADOS OPERACIONAIS
-- ============================================

-- Limpar itens de pedidos
TRUNCATE TABLE itens_pedido;

-- Limpar pedidos de venda
TRUNCATE TABLE pedidos_venda;

-- Limpar movimentações de estoque
TRUNCATE TABLE movimentacoes_estoque;

-- Limpar estoque
TRUNCATE TABLE estoque;

-- Limpar composições de produtos
TRUNCATE TABLE produto_composicao;

-- Limpar produtos
TRUNCATE TABLE produtos;

-- Limpar fornecedores
TRUNCATE TABLE fornecedores;

-- Limpar depósitos
TRUNCATE TABLE depositos;

-- ============================================
-- MANTER (NÃO LIMPAR)
-- ============================================
-- usuarios - MANTIDO
-- permissoes - MANTIDO
-- usuario_permissoes - MANTIDO
-- categorias_produto - MANTIDO (configuração)
-- ingredientes_padrao - MANTIDO (configuração)
-- migrations - MANTIDO (sistema)

-- Reabilitar verificação de chaves estrangeiras
SET FOREIGN_KEY_CHECKS = 1;

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

-- ============================================
-- RESUMO
-- ============================================
-- Tabelas LIMPAS:
--   - itens_pedido
--   - pedidos_venda
--   - movimentacoes_estoque
--   - estoque
--   - produto_composicao
--   - produtos
--   - fornecedores
--   - depositos
--
-- Tabelas MANTIDAS:
--   - usuarios
--   - permissoes
--   - usuario_permissoes
--   - categorias_produto
--   - ingredientes_padrao
--   - migrations

SELECT 'Limpeza concluída com sucesso!' AS Status;

