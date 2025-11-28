<?php namespace App\Libraries;

class Asaas
{

    public function __construct(){
        /*
        * Criamos uma instância do CodeIgniter na variável $CI
        */
        $this->session = \Config\Services::session();
        $this->usuario = $this->session->get('dadoslogin');
    }

    function CreateSaller($saller)
    {   
      $client = new \GuzzleHttp\Client();

      $response = $client->request('POST', URL_ASAAS.'accounts', [
        'headers' => [
          'accept' => 'application/json',
          'content-type' => 'application/json',
          'User-Agent' => 'GNMClub',
          'access_token' => TOKEN_ASAAS
        ],
        'body' =>json_encode([
          'name' => $saller['NOME'],
          'email' => $saller['EMAIL'],
          'cpfCnpj' => $saller['CPF'],
          'birthDate' => $saller['NASCIMENTO'],
          'mobilePhone' => $saller['TELEFONE'],
          'incomeValue' => $saller['FATURAMENTO'],
          'address' => $saller['ENDERECO'],
          'addressNumber' => $saller['NUMERO'],
          'province' => $saller['BAIRRO'],
          'postalCode' => $saller['CEP']
          ])
      ]);
      
      return json_decode($response->getBody());
    }

    function createSign($dados){
      $client = new \GuzzleHttp\Client();
      $response = $client->request('POST', URL_ASAAS.'subscriptions', [
        'body' => json_encode($dados),
        'headers' => [
          'accept' => 'application/json',
          'content-type' => 'application/json',
          'access_token' => TOKEN_ASAAS
        ],
      ]);

      return json_decode($response->getBody());
    }

    function createClient($dados){
      $client = new \GuzzleHttp\Client();

      $response = $client->request('POST', URL_ASAAS.'customers', [
        'body' => json_encode($dados),
        'headers' => [
          'accept' => 'application/json',
          'content-type' => 'application/json',
          'access_token' => TOKEN_ASAAS
        ],
      ]);

      return json_decode($response->getBody());
    }
    
}