<?php

if (!function_exists('usuario_tem_permissao')) {
    /**
     * Verifica se o usuário logado tem uma permissão específica
     * 
     * @param string $modulo Nome do módulo (ex: 'produtos', 'estoque')
     * @param string $acao Ação (ex: 'visualizar', 'criar', 'editar', 'excluir')
     * @return bool
     */
    function usuario_tem_permissao($modulo, $acao)
    {
        $session = \Config\Services::session();
        $usuario = $session->get('dadoslogin');
        
        if (!$usuario) {
            return false;
        }
        
        // Administradores têm todas as permissões
        $isAdmin = $usuario['admin'] ?? $usuario['ADMIN'] ?? 0;
        if ($isAdmin) {
            return true;
        }
        
        // Busca permissão do usuário
        $idUsuario = $usuario['id_usuario'] ?? $usuario['ID_USUARIO'] ?? null;
        if (!$idUsuario) {
            return false;
        }
        
        $permissaoModel = new \App\Models\PermissaoModel();
        return $permissaoModel->usuarioTemPermissao($idUsuario, $modulo, $acao);
    }
}

if (!function_exists('verificar_permissao')) {
    /**
     * Verifica permissão e redireciona se não tiver acesso
     * 
     * @param string $modulo Nome do módulo
     * @param string $acao Ação
     * @return void|redirect
     */
    function verificar_permissao($modulo, $acao)
    {
        if (!usuario_tem_permissao($modulo, $acao)) {
            return redirect()->to('/Dashboard')->with('erro', 'Você não tem permissão para acessar esta funcionalidade.');
        }
    }
}

