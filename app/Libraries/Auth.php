<?php namespace App\Libraries;

class Auth
{

    public function __construct(){
        /*
        * Criamos uma instância do CodeIgniter na variável $CI
        */
        $this->session = \Config\Services::session();
        $this->usuario = $this->session->get('dadoslogin');
        $this->usuariosModel = new \App\Models\UsuarioModel;
    }

    function CheckAuth($id_metodo)
    {
        /*
        * Se o usuário estiver logado faz a verificação da permissão
        * caso contrário redireciona para uma tela de login
        */
        if(isset($this->usuario)){
            $array = array('FK_ID_METODO' => $id_metodo, 'FK_ID_USUARIO' => $this->usuario->ID_USUARIO);
            $qryPermissoes = $this->usuariosModel->getPermissao($array);
            /*
            * Se o usuário não tiver a permissão para acessar o método,
            * ou seja, não estiver relacionado na tabela "permissoes",
            * ele deve ser redirecionado para uma tela informando que
            * não tem permissão de acesso;
            * caso contrário o acesso é liberado
            */
            if(count($qryPermissoes)==0){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            return false;
        }
    }
    
}