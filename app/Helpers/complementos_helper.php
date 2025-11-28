<?php

    function limpaCPF_CNPJ($valor){
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        return $valor;
    }

    function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if(isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }


    function status($s){
        if($s == 'I'){
            return '<span class="text-write-50 badge badge-info">Inativo</span</span>';
        }else if($s == 'A'){
            return '<span class="text-write-50 badge badge-success">Ativo</span</span>';
        }
    }

    function tipo($s){
        if($s == 'C'){
            return '<span class="text-write-50 badge badge-info">Cliente</span</span>';
        }else if($s == 'F'){
            return '<span class="text-write-50 badge badge-success">Fornecedor</span</span>';
        }
    }

    function usuario($s){
        if($s == 'R'){
            return '<span class="text-write-50 badge badge-info">Representante</span</span>';
        }else if($s == 'A'){
            return '<span class="text-write-50 badge badge-success">Administrador</span</span>';
        }
    }

    function sn($s){
        if($s == 'N'){
            return '<span class="text-write-50 badge badge-danger">Não</span</span>';
        }else if($s == 'S'){
            return '<span class="text-write-50 badge badge-success">Sim</span</span>';
        }
    }

    function pg($s){
        if($s == 'CC'){
            return '<span class="text-write-50 badge badge-warning">Cartão de crédito</span</span>';
        }else if($s == 'BB'){
            return '<span class="text-write-50 badge badge-success">Boleto/Pix</span</span>';
        }else if($s== 'BC'){
            return '<span class="text-write-50 badge badge-info">Ambos</span</span>';
        }else{
            return '<span class="text-write-50 badge badge-danger">Grátis</span</span>';
        }
    }

    function recorrencia($s){
        switch ($s) {
            case 'WE':
                return 'Semanal';
                break;
            case 'BI':
                return 'Quinzenal';
                break;
            case 'MO':
                return 'Mensal';
                break;
            case 'QU':
                return 'Trimestral';
                break;
            case 'SE':
                return 'Semestral';
                break;
            case 'YE':
                return 'Anual';
                break;
            default:
                return 'Mensal';
                break;
        }
    }

    function recorrencia_asaas($s){
        switch ($s) {
            case 'WE':
                return 'WEEKLY';
                break;
            case 'BI':
                return 'BIWEEKLY';
                break;
            case 'MO':
                return 'MONTHLY';
                break;
            case 'QU':
                return 'BIMONTHLY';
                break;
            case 'SE':
                return 'QUARTERLY';
                break;
            case 'YE':
                return 'SEMIANNUALLY';
                break;
            default:
                return 'YEARLY';
                break;
        }
    }
	
	function frm_moeda($valor){
        $x = number_format($valor,2,",",".");
        return $x;
    }

    function tirarAcentos($string){
        // matriz de entrada
        $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','Ã','Õ','ñ','Ñ','ç','Ç','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º','¬','.','£','©');
    
        // matriz de saída
        $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','A','O','n','n','c','C','','','','','','','','','','','','','','','','','','','','','','','','','','');
    
        // devolver a string
        return str_replace($what, $by, $string);
    }
    
    function inverterData($data){
        if(count(explode("/",$data)) > 1){
            return implode("-",array_reverse(explode("/",$data)));
        }elseif(count(explode("-",$data)) > 1){
            return implode("/",array_reverse(explode("-",$data)));
        }
    }

    function Countdata($data){
		$sodata = substr($data, 0, 10);
		$datainvertida = inverterData($sodata);
		$hora = substr($data, 10);
		$dataehora = $datainvertida." ".$hora;

		return ($dataehora);

	}

    function converterDataParaBR($data) {
        $dataFormatada = DateTime::createFromFormat('Y-m-d H:i:s.u', $data);
        return $dataFormatada->format('d/m/Y H:i:s');
    }
    

    function inverterDataHora($data){
		$sodata = substr($data, 0, 10);
		$datainvertida = inverterData($sodata);
		$hora = substr($data, 10);
		$dataehora = $datainvertida." ".$hora;

		return ($dataehora);

    }

    function getBarMenu($usuario){
        $usuariosModel = new \App\Models\UsuarioModel;
		$dadosnavleft['permissaomenu'] = $usuariosModel->getPermissaoMenu($usuario->ID_USUARIO);
		$i = 0;
		foreach ($dadosnavleft['permissaomenu']->getResult() as $menu) {
			if($dadosnavleft['permissaomenu']->getResult()[$i]->ID_MENU == $menu->ID_MENU){
				$dadosnavleft['permissaomenu']->getResult()[$i]->submenu = $usuariosModel->getPermissaoSubmenu($usuario->ID_USUARIO,$menu->ID_MENU);
			}
			$i++;
		}
		return $dadosnavleft;
    }

    function getFretesTabela($id_tabela){
        $financeiroModel = new \App\Models\FinanceiroModel;
        return $financeiroModel->getFretesTabela($id_tabela);
    }

    function getUltimaSinc(){
        $session = \Config\Services::session();
        $usuario = $session->get('dadoslogin');

        $integracaoModel = new \App\Models\IntegracaoModel;
		$dadosnavleft['data_hora'] = $integracaoModel->getUltimaSincronizacao($usuario['ID_CLIENTE']);
		return converterDataParaBR($dadosnavleft['data_hora']->synced_at);
    }

    function sys_log($usuario, $comando, $tabela){
        $dados['FK_ID_USUARIO'] = $usuario;
        $dados['COMANDO'] = $comando;
        $dados['DATA'] = date('Y-m-d H:i:s');
        $dados['TABELA'] = $tabela;
        $usuariosModel = new \App\Models\UsuarioModel;
        $usuariosModel->setLog($dados);
    }

    function limpaString($valor){
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        $valor = str_replace("(", "", $valor);
        $valor = str_replace(")", "", $valor);
        $valor = str_replace(" ", "", $valor);
        return $valor;
    }

    function encryptData($data) {
        $cipherMethod = 'AES-256-CBC';
        $encryptionKey = ENCRYPTION_KEY;
        $ivLength = openssl_cipher_iv_length($cipherMethod);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encryptedData = openssl_encrypt($data, $cipherMethod, $encryptionKey, OPENSSL_RAW_DATA, $iv);
        $encryptedData = base64_encode($iv . $encryptedData); // Combine IV com os dados criptografados
        return $encryptedData;
    }
    
    // Função para descriptografar dados
    function decryptData($encryptedData) {
        $cipherMethod = 'AES-256-CBC';
        $encryptionKey = ENCRYPTION_KEY;
        $encryptedData = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length($cipherMethod);
        $iv = substr($encryptedData, 0, $ivLength);
        $encryptedPayload = substr($encryptedData, $ivLength);
        $decryptedData = openssl_decrypt($encryptedPayload, $cipherMethod, $encryptionKey, OPENSSL_RAW_DATA, $iv);
        return $decryptedData;
    }
?>