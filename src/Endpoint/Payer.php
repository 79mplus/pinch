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
        $resp = $this->http_client->request('POST', $this->api_url. '/payers', [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->pinch->token
            ],
            'body' => "{
                    'email'          : '{$email}',
                    'firstName'      : '{$first_name}',
                    'lastName'       : '{$last_name}'
                    'mobileNumber'    : '{$mobile}',
                }"
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
        $resp = $this->http_client->request('POST', $this->api_url. '/payers', [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->pinch->token
            ],
            'body' => "{
                    'id'             : '{$id}',
                    'email'          : '{$email}',
                    'firstName'      : '{$first_name}',
                    'lastName'       : '{$last_name}'
                    'mobileNumber'   : '{$mobile}',
                }"
        ]);

        return $resp->getBody()->getContents();
    }



}