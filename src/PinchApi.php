<?php

namespace mplus\Pinch;

use GuzzleHttp\Client as HttpClient;

class PinchApi{
	public $base_url = 'https://api.getpinch.com.au/';
	public $auth_url = 'https://auth.getpinch.com.au/connect/token';
	public $token = null;
	public $mode;
	public function __construct($merchant_id = '', $secret_key = '', $mode = 'live'){
		/*** getting the token ***/
		$this->getToken($merchant_id, $secret_key);
		$this->mode = $mode;
	}
	public function __get($name){
		switch($name){
			case "payment": return new Endpoint\Payment($this); break;
			case "payer": return new Endpoint\Payer($this); break;
		}
	}
	private function getToken($merchant_id, $secret_key){
		$http_client = new HttpClient();
		
		$resp = $http_client->request('post', $this->auth_url, [
			'auth' => [$merchant_id, $secret_key],
			'form_params' => ['grant_type' => 'client_credentials', 'scope' => 'api1']
		]);
		if($resp->getStatusCode() == 200){
			$json = json_decode($resp->getBody()->getContents());
			if($json->access_token){
				$this->token = $json->access_token;
			}
		}
		
	}

}