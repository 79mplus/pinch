<?php

namespace mplus\Pinch\Endpoint;

use GuzzleHttp\Client as HttpClient;

class Payer extends Endpoint
{
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
     * @param $email
     * @param $first_name
     * @param string $last_name
     * @param string $mobile
     * @return mixed
     */
    public function add($email, $first_name, $last_name = '', $mobile = ''){
        $_body = array(
            'email'        => $email,
            'firstName'    => $first_name,
            'lastName'     => $last_name,
            'mobileNumber' => $mobile
        );
        $_body_json              = json_encode( (object) $_body );

        $resp = $this->http_client->request('POST', $this->api_url. '/payers', [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->pinch->token
            ],
            'body' => $_body_json
        ]);

        return $resp->getBody()->getContents();
    }

    /**
     * @param $id
     * @param $email
     * @param $first_name
     * @param string $last_name
     * @param string $mobile
     * @return mixed
     */

    public function update($id, $email, $first_name, $last_name = '', $mobile = ''){
        $_body = array(
            'id'           => $id,
            'email'        => $email,
            'firstName'    => $first_name,
            'lastName'     => $last_name,
            'mobileNumber' => $mobile
        );
        $_body_json              = json_encode( (object) $_body );

        $resp = $this->http_client->request('POST', $this->api_url. '/payers', [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->pinch->token
            ],
            'body' => $_body_json
        ]);

        return $resp->getBody()->getContents();
    }

    /**
     * @param  string $payer_id
     * @param  string $source_type
     * @param  string $credit_card_token
     * @param  string $bank_account_name
     * @param  string $bank_account_bsb
     * @param  string $bank_account_number
     * @return mixed
     */
    public function save_payment_source( $payer_id, $source_type, $credit_card_token, $bank_account_name = NULL, $bank_account_bsb  = NULL, $bank_account_number = NULL ) {
        $_body = array(
            'SourceType'        => $source_type,
            'BankAccountName'   => $bank_account_name,
            'BankAccountBsb'    => $bank_account_bsb,
            'BankAccountNumber' => $bank_account_number,
            'CreditCardToken'   => $credit_card_token
        );
        $_body_json              = json_encode( (object) $_body );

        $resp = $this->http_client->request('POST', $this->api_url. '/payers/' . $payer_id . '/sources', [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->pinch->token
            ],
            'body' => $_body_json
        ]);

        return $resp->getBody()->getContents();
    }

}