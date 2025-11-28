<?php
namespace App\Libraries;
use App\Models\IntegracaoModel;

use \DateTimeImmutable;

Class Shopee{

    private $partner_id         = '1011063';
    private $client_secret      = 'xRjoP5sqUnFg0xWEySjZ8fTyEDUzrwVT';
    private $redirect_url       = 'http://homologacao.finanplace.com.br/Integracao/localhostShopee';
    private $token_shopee       = "62487047415347494379544e6d567557507048726b4d725346676a705246526e";
    


    public function __construct($codigo_conta, $token, $refresh_token, $client_id) {
        $this->integracaoModel = new IntegracaoModel();
        $credenciais = 'credenciais';      
        $novo_token  = 'novo_token';
        $id_vendedor = 'id_vendedor';
        /**
         * Quando construir a classe verifica se esta acessando os dados do usuário
         * Caso não esteja, faz um refresh token
         */
        $request_validacao = Shopee::getDadosConta($token);
        if(isset($request_validacao->message) && ($request_validacao->message == "invalid_token" || $request_validacao->message == "Error validating grant. Your authorization code or refresh token may be expired or it was already used" )){
            $this->$credenciais = Shopee::refreshToken($refresh_token);
            $this->$novo_token  = 'S';

            //Armazena o novo token no BD
            if(isset($this->$credenciais->access_token)){
                $dados['FK_ID_CLIENTE'] = $client_id;
                $dados['TOKEN_ACESSO']  = $this->$credenciais->access_token;
                $dados['REFRESH_TOKEN'] = $this->$credenciais->refresh_token;
                $this->integracaoModel->atualizaTokenML($dados);
                $dados_conta = Shopee::getDadosConta($this->$credenciais->access_token);
                $this->$id_vendedor  = $dados_conta->id;
            }else{
                $this->$novo_token  = 'N';
            }
            
        }else{
            $this->$novo_token  = 'N';
            if(isset($request_validacao->id)){
                $this->$id_vendedor  = $request_validacao->id;
            }
        }
    }

    public function getToken($code, $shop_id){
        $date       = new DateTimeImmutable();
        $timestamp  = $date->getTimestamp();
        $host = "https://partner.test-stable.shopeemobile.com";
        $path = "/api/v2/auth/token/get";
        $partner_id = 1011063;
        $token = "62487047415347494379544e6d567557507048726b4d725346676a705246526e";
        $chave = $token;
        $tmp_base_string = $partner_id.$path.$timestamp;
        $base_string = $tmp_base_string;
        $sign = hash_hmac("sha256", $base_string, $chave);

        $body = json_encode(array(
            "partner_id" => (int) $partner_id,
            "code" => $code,
            "shop_id" => (int) $shop_id
        ));


        $url = $host . $path . "?partner_id=".$partner_id."&timestamp=".$timestamp."&sign=".$sign;



        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }

    public function getDadosConta($token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.mercadolibre.com/users/me',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token
            ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);

    }

    public function getTarifasProdutos($token, $preco, $lista_categoria_produto,  $categoria_produto){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadolibre.com/sites/MLB/listing_prices/'.$lista_categoria_produto.'?price='.$preco.'&category_id='.$categoria_produto,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }

    public function getCustoFrete($token, $id_envio){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadolibre.com/shipments/'.$id_envio.'/costs',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    public function getVendas($token, $id_vendedor, $offset=0, $limit=1){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.mercadolibre.com/orders/search?seller='.$id_vendedor.'&sort=date_desc&offset='.$offset.'&limit='.$limit,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);
        
    }

    public function refreshToken($refresh_token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.mercadolibre.com/oauth/token',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => 'grant_type=refresh_token&client_id='.$this->client_id.'&client_secret='.$this->client_secret.'&refresh_token='.$refresh_token,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));
        
        $response = curl_exec($curl);
    
        curl_close($curl);
        return json_decode($response);
        
    }

    public function getVendasDia($token, $id_vendedor, $offset=0, $limit=1){
        $data_amanha = date('Y-m-d',strtotime("+1 day"));
        $data_hoje  = date('Y-m-d');
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadolibre.com/orders/search?seller='.$id_vendedor.'&sort=date_desc&order.date_created.from='.$data_hoje.'T00:00:00.000-00:00&order.date_created.to='.$data_amanha.'T00:00:00.000-00:00&offset='.$offset.'&limit='.$limit,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

}