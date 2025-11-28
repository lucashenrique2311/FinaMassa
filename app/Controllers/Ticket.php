<?php namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\TicketModel;
use CodeIgniter\Controller;

class Ticket extends BaseController
{

	public function __construct(){
		$this->usuarioModel = new UsuarioModel();
		$this->ticketModel = new TicketModel();
		$this->session = \Config\Services::session();

		helper('complementos'); 
    }

	public function index()
	{

		$usuario = $this->session->get('dadoslogin');
        if(!isset($usuario)){
            return redirect()->to('/Home');
        }else{	
			$dados['tickets'] = $this->ticketModel->getTickets();		
			echo view('Commons/header');
			echo view('Ticket/ticket',$dados);
            echo view('Commons/footer');
		}
		
	}

	public function Store()
	{
		$usuario = $this->session->get('dadoslogin');
		$dados['FK_ID_APOSTADOR'] = $usuario->ID_APOSTADOR;
		$dados['PROTOCOLO'] = rand(100000, 9999999);
		$dados['STATUS'] = 'P';
		$dados['DATA_CRIACAO'] = date('Y-m-d');
		$dados['SETOR'] = $this->request->getPost('setor');
		$dados['DESCRICAO'] = $this->request->getPost('descricao');
		$result=$this->ticketModel->setTicket($dados);
		if($result){
			echo json_encode('sucesso');
		}else{
			echo json_encode('erro');
		}
	    
	}

	public function Get_TicketID_Ajax()
	{
		$id = $this->request->getPost('id');
		$result=$this->ticketModel->getTicketID($id);
		if($result){
			echo json_encode($result);
		}else{
			echo json_encode('erro');
		}
	    
	}


	

}
