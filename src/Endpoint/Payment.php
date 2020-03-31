<?php

namespace mplus\Pinch\Endpoint;

use GuzzleHttp\Client as HttpClient;

class Payment extends Endpoint
{
    private $token;
    private $api_url;
    private $http_client;

    /**
     * @param $pinch
     */

    public function __construct($pinch){
        parent::__construct($pinch);
        $this->api_url = $this->pinch->base_url . $this->pinch->mode;
        $this->http_client =  new HttpClient();
    }

    /**
     * @param $publishable_key
     * @param $card_no
     * @param $cvc
     * @param $expiry_month
     * @param $expiry_year
     * @param $card_holder_name
     * @param $email
     * @param $amount
     * @param string $description
     * @return bool
     */
    public function execute($publishable_key, $card_no, $cvc, $expiry_month, $expiry_year, $card_holder_name, $email, $amount, $description = ''){
        /*getting credit card token*/
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

        $resp = $this->http_client->request('POST', $this->api_url . '/tokens', [
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
            $resp = $this->http_client->request('POST', $this->api_url. '/payments/realtime', [
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

    /**
     * @param $payer_id
     * @param $transaction_date
     * @param $amount
     * @param string $description
     * @return mixed
     */
    public function schedule($payer_id, $transaction_date, $amount, $description = ''){
        $resp = $this->http_client->request('POST', $this->api_url. '/payments', [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->pinch->token
            ],
            'body' => "{
                    'payerId'       : '{$payer_id}',
                    'amount'         : {$amount},
                    'transactionDate': '{$transaction_date}'
                    'description'    : '{$description}',
                }"
        ]);

        return $resp->getBody()->getContents();
    }

}