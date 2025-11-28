<?php namespace App\Libraries;

class Lecupon
{

  public function __construct(){
      /*
      * Criamos uma instância do CodeIgniter na variável $CI
      */
      $this->session = \Config\Services::session();
      $this->usuario = $this->session->get('dadoslogin');
  }

  function getToken()
  {   
    $client = new \GuzzleHttp\Client();

    $response = $client->request('POST', URL_LECUPON.'/client/v2/sign_in', [
      'body' => '{"email":"'.EMAIL_LECUPON.'","password":"'.SENHA_LECUPON.'"}',
      'headers' => [
        'accept' => 'application/json',
        'content-type' => 'application/json',
      ],
    ]);

    return json_decode($response->getBody());
  }

  function activeUser($dados){
    $client = new \GuzzleHttp\Client();
    $token = $this->getToken();

    $response = $client->request('POST', URL_LECUPON.'/client/v2/businesses/'.BUSINESS_ID.'/authorized_users', [
      'body' => json_encode($dados),
      'headers' => [
        'X-ClientEmployee-Email' => EMAIL_LECUPON,
        'X-ClientEmployee-Token' => $token->auth_token,
        'accept' => 'application/json',
        'content-type' => 'application/json',
      ],
    ]);

    return json_decode($response->getBody());
  }

  function createUser($dados){

    $client = new \GuzzleHttp\Client();

    $response = $client->request('POST', URL_LECUPON.'/api/v1/public_integration/users', [
      'body' => json_encode($dados),
      'headers' => [
        'Api-Secret' => API_KEY_SECRET,
        'accept' => 'application/json',
        'content-type' => 'application/json',
      ],
    ]);

    return json_decode($response->getBody());
  }
    
}