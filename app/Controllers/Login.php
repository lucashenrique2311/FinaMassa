<?php namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class Login extends BaseController
{

	public function __construct(){
		$this->usuarioModel = new UsuarioModel();
		$this->session = \Config\Services::session();
    }

	public function index()
	{

		$usuario = $this->session->get('dadoslogin');
        if(isset($usuario)){
            return redirect()->to('/Dashboard');
        }else{		
			echo view('Login/login');
		}
		
	}

	//--------------------------------------------------------------------

	public function validaLogin(){
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');
        
        // Validação básica
        if (empty($email) || empty($senha)) {
            $dados = array('errologin' => 'Por favor, preencha todos os campos.');
            return view('Login/login', $dados);
        }
        
        // Hash da senha (mesmo padrão do sistema antigo)
        $senhaHash = sha1(preg_replace('/[^[:alnum:]_]/', '', $senha));
        
        // Busca informações de login
        $login = $this->usuarioModel->getInformacoesLogin(strtoupper($email), $senhaHash);
        
        if(!empty($login)){	
			$dadosuser['dadoslogin'] = $login[0];	
			$this->session->set($dadosuser);
			
			// Redireciona para Dashboard (removida verificação de EXPIRADO por enquanto)
			return redirect()->to('/Dashboard');
        }else{
			$dados = array('errologin' => 'Usuário ou senha estão incorretos');
			return view('Login/login', $dados);
        }
	}
	
	public function sair(){
		//Destroi a sessão atual no navegador do usuario.
		$this->session->destroy();
		//chama o metodo (tela) de login
		return redirect()->to('/');
	}


	public function esqueceuSenha()
	{		
		return view('Login/nova_senha');	
	}

	public function recuperarSenha(){
		$dados=array();
		$usuario = $this->request->getPost('email');
		$token = $this->usuarioModel->getToken($usuario);
		if(($token) && $token->EMAIL != null && $token->EMAIL != ""){
			$email = \Config\Services::email();
			$config['SMTPHost'] = 'mail.cnsistemas.net';
			$config['SMTPPort'] = '465';
			$config['SMTPUser'] = 'lucas.silva@cnsistemas.net';
			$config['SMTPPass'] = 'System@#!!';
			$config['protocol'] = 'smtp';
			$config['SMTPCrypto'] = 'ssl';
			$config['charset'] = 'utf-8';
			$email->initialize($config);
			$email->setFrom('lucas.silva@cnsistemas.net', 'Finanplace');
			$email->setTo($token->EMAIL);
			$email->setSubject('RECUPERAÇÃO DE SENHA');
			$email->setMessage('Link para a recuperação da senha: http://homologacao.finanplace.com.br/Login/novaSenha/'.base64_encode($token->TOKEN));
			if($email->send()){
				$dados = array('rec_sucesso_email' => "E-mail enviado com sucesso para ".$token->EMAIL."");
				return view('Login/login', $dados);
			}else{
				$data = $email->printDebugger(['headers']);
            	print_r($data);
			}
		}else{
			$dados = array('rec_erro_user' => "Não encontramos nenhum usuário cadastrado com as informações fornecidas!");
			return view('Login/login', $dados);
		}
	}

	public function novaSenha($token){
		$verificacao = $this->usuarioModel->consultaToken(base64_decode($token));
		if($verificacao){
			$dados['token'] = $token;
			return view('Login/alteracao_senha', $dados);
		}else{
			$dados = array('rec_erro_token' => "Token inválido!");
			return view('Login/login', $dados);
		}
	}

	public function gravarSenha(){
		if($this->request->getPost('password_new') == $this->request->getPost('password_new_confirm')){
			if($this->usuarioModel->updatePassword($this->request->getPost('password_new'), base64_decode($this->request->getPost('token')))){
				$dados = array('rec_sucesso' => 'Senha alterada com sucesso!');
				return view('Login/login', $dados);
			}else{
				$dados = array('rec_erro' => 'Não foi possível alterar sua senha, tente novamente!');
				return view('Login/alteracao_senha', $dados);
			}
		}else{
			$dados = array('rec_erro_senha' => 'As senhas divergem, verifique se informou a mesma senha!');
			return view('Login/alteracao_senha', $dados);
		}
	}

}
