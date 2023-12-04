<?php

namespace Serpro\Datavalid;

use Serpro\Datavalid\Endpoints\ApiStatus; 
use Serpro\Datavalid\Endpoints\Document; 
use Serpro\Datavalid\ResponseHandler; 
use GuzzleHttp\Client as HttpClient;

class Client
{ 
    /**
     * @var \GuzzleHttp\Client
     */
    private $http;

    /**
     * @var \GuzzleHttp\Client
     */
    private $http_auth;

    /**
     * @var string
     */
    private $access_token;

    /**
     * @var string
     */
    private $consumer_key;

    /**
     * @var string
     */
    private $consumer_secret;

    /**
     * @var string
     */
    private $base_uri; 
    
    public function __construct()
    {
        $this->consumer_key    = env('SERPRO_CONSUMER_KEY');
        $this->consumer_secret = env('SERPRO_CONSUMER_SECRET');
        $this->base_uri = 'https://gateway.apiserpro.serpro.gov.br/';

        $access_token = $this->getAcessToken()->access_token;

        $this->http = new HttpClient([
            'base_uri' => $this->base_uri,  
            'headers' => [
                'Content-Type'   => 'application/json',
                'Accept'         => 'application/json',
                'Authorization'  => 'Bearer '.$access_token
            ]
        ]);  

        $this->document = new Document($this); 
        $this->api_status = new ApiStatus($this); 
    }
  
    private function getAcessToken()
    {
        try {
            $chaveB64 = base64_encode("$this->consumer_key:$this->consumer_secret");

            $this->http_auth = new HttpClient([
                'base_uri' => $this->base_uri,  
                'headers' => [
                    'Content-Type'   => 'application/x-www-form-urlencoded',
                    'Authorization'  => 'Basic '.$chaveB64
                ],
                'form_params'=>[
                    'grant_type' => 'client_credentials',
                ]
            ]);  
            $response = $this->http_auth->request("POST",'token');
        } catch (\Exception $ex) {
            throw new \Exception("Erro ao solicitar o token (0007) / {$ex->getMessage()}", $ex->getCode());
        }  
        return ResponseHandler::success((string)$response->getBody());
    } 

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return \ArrayObject
     */
    public function request($method, $uri, $options = [])
    {
        try { 
            $response = $this->http->request(
                $method,
                $uri, 
                $options, 
            );
            return ResponseHandler::success((string)$response->getBody());
        } catch (\Exception $e) {
            switch ($e->getCode()) {
                case 422:
                    $r = $e->getResponse();
                    $string = $r->getBody()->getContents();
                    throw new \Exception($string,422); 
                    break;
                default:
                    throw $e;
                    break;
            }
        }
    }  
    
    /**
     * @return Serpro\Datavalid\Endpoints\Document
     */
    public function document()
    {
        return $this->document;
    }  
    
    /**
     * @return Serpro\Datavalid\Endpoints\ApiStatus
     */
    public function api_status()
    {
        return $this->api_status;
    }  

}