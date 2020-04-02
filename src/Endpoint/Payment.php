<?php

namespace mplus\Pinch\Endpoint;

use GuzzleHttp\Client as HttpClient;

class Payment extends Endpoint
{
    private $token = NULL;
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
        /*set credit card token*/
        $this->credit_card_token($publishable_key, $card_no, $cvc, $expiry_month, $expiry_year, $card_holder_name);

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

    /**
     * @param $payer_id
     * @param $credit_card_token
     * @param $source_id
     * @param $amount
     * @param $description
     * @return mixed
     */
    public function realtime( $payer_id, $credit_card_token = '', $source_id = '', $amount, $description = ''){
        /*Execute real-time payment*/
        $_body = array(
            'payerId'     => $payer_id,
            'amount'      => $amount,
            'description' => $description,
        );

        if( $credit_card_token !== '' ){
            $_body['creditCardToken'] = $credit_card_token;
        }

        if( $source_id !== '' ){
            $_body['sourceId'] = $source_id;
        }

        $_body_json     = json_encode( (object) $_body );

        $resp = $this->http_client->request('POST', $this->api_url. '/payments/realtime', [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->pinch->token
            ],
            'body' => $_body_json
        ]);

        return $resp->getBody()->getContents();
    }

    public function credit_card_token( $publishable_key, $card_no, $cvc, $expiry_month, $expiry_year, $card_holder_name ){
        /*getting credit card token*/
        $_body = array(
            'PublishableKey' => $publishable_key,
            'cardNumber'     => $card_no,
            'cvc'            => $cvc,
            'expiryMonth'    => $expiry_month,
            'expiryYear'     => $expiry_year,
            'cardHolderName' => $card_holder_name
        );

        $_body_json     = json_encode( (object) $_body );

        $resp = $this->http_client->request('POST', $this->api_url . '/tokens', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $_body_json
        ]);

        if($resp->getStatusCode() == 200 || $resp->getStatusCode() == 201){
            $json = json_decode($resp->getBody()->getContents());
            if($json->token){
                $this->token = $json->token;
            }
        }

        return $this->token;
    }

}