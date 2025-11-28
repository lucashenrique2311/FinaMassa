<?php
namespace App\Libraries;
use Exception;
use App\Models\IntegracaoModel;

Class Bling{

    private $client_id        = '695947932703340';
    private $client_secret    = 'xRjoP5sqUnFg0xWEySjZ8fTyEDUzrwVT';
    private $redirect_url     = 'http://homologacao.finanplace.com.br/Integracao/localhostmercadolivre';

    private $token;
    private $refreshToken;
    private $clientId;
    private $clientSecret;

    public function __construct($token, $refreshToken, $clientId, $clientSecret, $id_cliente) {
        $this->integracaoModel = new IntegracaoModel();
        $this->token = $token;
        $this->refreshToken = $refreshToken;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        // Teste o token ao instanciar a classe
        if (!$this->testToken($token)) {
                $this->refreshToken();

            $dados['fk_id_cliente'] = $id_cliente;
            $dados['token']  = $this->token;
            $dados['refresh_token'] = $this->refreshToken;
            $this->integracaoModel->atualizaTokenBling($dados);
        }
    }

    private function testToken($token) {
        $response = $this->makeRequest('https://www.bling.com.br/Api/v3/produtos?pagina=&limite=1', $token);
        $decodedResponse = json_decode($response);
        
        // Verifica se o token é inválido
        if (isset($decodedResponse->error) && $decodedResponse->error->type === 'invalid_token') {
            return false;
        }
        return true;
    }

    private function makeRequest($url, $token) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer ' . $token,
                'Cookie: PHPSESSID=hmen3otv8amton7p2k37po70ld'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    private function refreshToken() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/oauth/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=refresh_token&refresh_token='.$this->refreshToken,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: 1.0',
            'Cookie: PHPSESSID=f54fad6h1jid562rome9m73gaf; PHPSESSID=g9pqq6cqt61qjk7p39g915bjk1',
            'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret)
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $decodedResponse = json_decode($response);
        
        if (isset($decodedResponse->access_token)) {
            $this->token = $decodedResponse->access_token;
            $this->refreshToken = $decodedResponse->refresh_token; // Atualiza o refresh token se disponível
        } else {
            // Lide com o erro de refresh token se necessário
            throw new Exception('Erro ao atualizar o token: ' . $response);
        }
    }
    
    public function getDados($token, $page){
        $curl = curl_init();    

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bling.com.br/Api/v2/produtos/page='.$page.'/json/?estoque=S&apikey='.$token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);
    }

    public function getProdutosRecentes($token, $page){
        $curl = curl_init();

        $dataInicial = date('d/m/Y', strtotime('-5 days', strtotime(date("Y-m-d"))));
        $dataFinal = date('d/m/Y', strtotime('+1 days', strtotime(date("Y-m-d"))));

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bling.com.br/Api/v2/produtos/page='.$page.'/json/?filters=dataAlteracao%5B'.$dataInicial.'%20TO%20'.$dataFinal.'%5D&estoque=S&apikey='.$token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);
    }

    public function getProdutosNovos($token, $page){
        $curl = curl_init();

        $dataInicial = date('d/m/Y', strtotime('-30 days', strtotime(date("Y-m-d"))));
        $dataFinal = date('d/m/Y', strtotime('+1 days', strtotime(date("Y-m-d"))));

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bling.com.br/Api/v2/produtos/page='.$page.'/json/?filters=dataInclusao%5B'.$dataInicial.'%20TO%20'.$dataFinal.'%5D&estoque=S&apikey='.$token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);
    }

    public function getProdutoIndividual($token, $codigo){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bling.com.br/Api/v2/produto/'.$codigo.'/json/?estoque=S&apikey='.$token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);

    }

    public function getFornecedores($token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bling.com.br/Api/v2/contatos/json/?filters=tipoPessoa%5BJ%5D&apikey='.$token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);


    }

    public function getDepositos($token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bling.com.br/Api/v2/depositos/json/?apikey='.$token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => 'apikey=%7Bapikey%7D',
        CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);


    }

    public function getPedidosCompra($token, $page){
        $dataInicial = date('d/m/Y', strtotime('-60 days', strtotime(date("Y-m-d"))));
        $dataFinal = date("d/m/Y");

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bling.com.br/Api/v2/pedidoscompra/page='.$page.'/json/?filters=dataEmissao%5B'.$dataInicial.'%20TO%20'.$dataFinal.'%5D&apikey='.$token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => 'apikey=%7Bapikey%7D',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);


    }

    public function getPedidos($token, $page){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bling.com.br/Api/v2/pedidos/page='.$page.'/json/?apikey='.$token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => 'apikey=%7Bapikey%7D',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
        
    }

    public function setPedidoCompra($token, $xml){
        $url = 'https://bling.com.br/Api/v2/pedidocompra/json/';
        $posts = array (
            "apikey" => $token,
            "xml" => rawurlencode($xml)
        );

        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_POST, count($posts));
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $posts);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);

        return json_decode($response);
        
    }

    public function setPedidoCompraV3($token, $json){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/pedidos/compras',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($json),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }

    public function atualizaProdutoFornecedor($token, $json, $id){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.bling.com.br/Api/v3/produtos/fornecedores/'.$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS =>json_encode($json),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=75f63shl21dg0pba2jf7hq8c4m'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }

    public function atualizaProdutoMkt($token, $json, $id){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.bling.com.br/Api/v3/produtos/lojas/'.$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS =>json_encode($json),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=75f63shl21dg0pba2jf7hq8c4m'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }

    public function getContasPagar($token, $dataInicial, $dataFinal){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.bling.com.br/Api/v3/contas/pagar?dataPagamentoInicial='.$dataInicial.'&dataPagamentoFinal='.$dataFinal.'&situacao=2',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=b5h7elebl2qlsvkr36conj250m'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);

    }

    public function getEstoqueProduto($token, $codigo, $deposito){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.bling.com.br/Api/v3/estoques/saldos/'.$deposito.'?codigos[]='.$codigo,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token,

            'Cookie: PHPSESSID=0cvaos0aab50oc4ah31authcve'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;


        return json_decode($response);

    }

    public function getProdutoAtivo($token, $codigo){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.bling.com.br/Api/v3/produtos?criterio=2&codigos[]='.$codigo,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=61n2bt3m1mes2vfej0ne77hkgc'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

        return json_decode($response);

    }

    public function getProdutoFornecedor($token, $produtoID){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.bling.com.br/Api/v3/produtos/fornecedores?idProduto='.$produtoID,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=50v2vncnvam955r4lobdp598ea'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

        return json_decode($response);

    }

    public function getDadosProduto($token, $produtoID){


        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.bling.com.br/Api/v3/produtos/'.$produtoID,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=53eclcmmi82t8o3skkq50i7qgc'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);


        return json_decode($response);

    }

    public function atualizaEstoqueBling($token, $json){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.bling.com.br/Api/v3/estoques',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($json),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '.$token,
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }

    

    public function atualizaAnuncio($token, $xml, $codigo, $id_loja_api){
        $url = 'https://bling.com.br/Api/v2/produtoLoja/'.$id_loja_api.'/'.$codigo;
        $posts = array (
            "apikey" => $token,
            "xml" => rawurlencode($xml)
        );

        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_POST, count($posts));
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $posts);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);

        return json_decode($response);
        
    }

    public function getPedidoIndividual($token, $codigo){
        $codigo = intval($codigo);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bling.com.br/Api/v2/pedido/'.$codigo.'/json/?apikey='.$token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => 'apikey=%7Bapikey%7D',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);
    }


    public function getPedidosAtuais($token, $page, $situacao_separacao){

        $dataInicial = date('d/m/Y', strtotime('-30 days', strtotime(date("Y-m-d"))));
        $dataFinal = date("d/m/Y");


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bling.com.br/Api/v2/pedidos/page='.$page.'/json/?filters=dataEmissao%5B'.$dataInicial.'%20TO%20'.$dataFinal.'%5D;%20idSituacao%5B'.$situacao_separacao.'%5D&apikey='.$token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);

    }

    public function getPedidosAtuaisAtualizacao($token, $page){

        $dataInicial = date('d/m/Y', strtotime('-5 days', strtotime(date("Y-m-d"))));
        $dataFinal = date("d/m/Y");


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bling.com.br/Api/v2/pedidos/page='.$page.'/json/?filters=dataEmissao%5B'.$dataInicial.'%20TO%20'.$dataFinal.'%5D&apikey='.$token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);

    }


    public function getPedidosNumero($token, $numero){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bling.com.br/Api/v2/pedido/'.$numero.'/json/?apikey='.$token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => 'apikey=%7Bapikey%7D',
        CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);

    }

    public function getPedidosCompraNumero($token, $numero){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bling.com.br/Api/v2/pedidocompra/'.$numero.'/json/?apikey='.$token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => 'apikey=%7Bapikey%7D',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);

    }

    public function getPedidosCompraAtuais($token, $page){
        $dataInicial = date('d/m/Y', strtotime('-60 days', strtotime(date("Y-m-d"))));
        $dataFinal = date("d/m/Y");

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bling.com.br/Api/v2/pedidoscompra/page='.$page.'/json/?filters=dataEmissao%5B'.$dataInicial.'%20TO%20'.$dataFinal.'%5D&apikey='.$token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => 'apikey=%7Bapikey%7D',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);


    }

    public function getSituacoes($token){
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bling.com.br/Api/v2/situacao/Vendas/json/?apikey='.$token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => 'apikey=%7Bapikey%7D',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));
    
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);
    }

    public function getPedidosDeVendaEmAberto($token){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/pedidos/vendas?idsSituacoes[]=6',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=hmen3otv8amton7p2k37po70ld'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    public function getPedidosDeCompraEmAberto($token, $pagina){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/pedidos/compras?pagina='.$pagina,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=hmen3otv8amton7p2k37po70ld'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    public function getPedidosDeVendaSeparacao($token, $situacao){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/pedidos/vendas?idsSituacoes[]='.$situacao,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=hmen3otv8amton7p2k37po70ld'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    

    public function getPedidosDeVendaId($id, $token){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/pedidos/vendas/'.$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=hmen3otv8amton7p2k37po70ld'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }

    public function getContaPagarId($id, $token){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/contas/pagar/'.$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=hmen3otv8amton7p2k37po70ld'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }

    


    public function getPedidosDeCompraId($id, $token){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/pedidos/compras/'.$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: PHPSESSID=hmen3otv8amton7p2k37po70ld'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }





}