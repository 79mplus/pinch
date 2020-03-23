<?php

namespace mplus\Pinch\Endpoint;

use GuzzleHttp\Client as HttpClient;

class Payment extends Endpoint
{
    private $token;
    public function execute($publishable_key, $card_no, $cvc, $expiry_month, $expiry_year, $card_holder_name, $email, $amount, $description = ''){
        /*getting credit card token*/
        $http_client                  = new HttpClient();
        // Tokens
        $token_body = array(
            'PublishableKey' => $publishable_key,
            'cardNumber' => $card_no,
            'cvc' => $cvc,
            'expiryMonth' => $expiry_month,
            'expiryYear' => $expiry_year,
            'cardHolderName' => $card_holder_name
        );
        $token_body_json              = json_encode( (object) $token_body );

        $resp = $http_client->request('POST', $this->pinch->base_url . $this->pinch->mode . '/tokens', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $token_body_json
        ]);

        if($resp->getStatusCode() == 200 || $resp->getStatusCode() == 201){
            $json = json_decode($resp->getBody()->getContents());
            if($json->token){
                $this->token = $json->token;
            }
        }

        if( $this->token ){
            $resp = $http_client->request('POST', $this->pinch->base_url . $this->pinch->mode . '/payments/realtime', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $this->pinch->token
                ],
                'body' => "{
                    'fullName'       : '{$card_holder_name}',
                    'email'          : '{$email}',
                    'amount'         : {$amount},
                    'description'    : '{$description}',
                    'creditCardToken': '{$this->token}'
                }"
            ]);

            return $resp->getBody()->getContents();
        }

        return false;
    }

}