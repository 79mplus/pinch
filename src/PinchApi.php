<?php

namespace mplus\Pinch;

use GuzzleHttp\Client as HttpClient;

class PinchApi{
	private $base_url;
	private $auth_url = 'https://auth.getpinch.com.au/connect/token';
	private $token = null;
	private $mode;
	public function __construct($merchant_id = '', $secret_key = '', $mode = 'live'){
		/*** getting the token ***/
		$this->getToken($merchant_id, $secret_key);
		$this->mode = $mode;
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