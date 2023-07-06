<?php

namespace App\Services;

use GuzzleHttp\Client;

class ZoomApi
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.zoom.us/v2/',
            'verify' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->generateToken(),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    protected function generateToken()
    {
        $apiKey = "gu2N9CqIQ7CzDCpMljmS4w";
        $apiSecret = "97fr0x56Rum7ix2rZ3Go7g";

      

        $payload = [
            'iss' => $apiKey,
            'exp' => strtotime('+1 minute'),
        ];

        return \Firebase\JWT\JWT::encode($payload, $apiSecret, 'HS256');
    }

    public function createMeeting($data)
    {   
        $response = $this->client->post('users/me/meetings', [
            'json' => $data,
        ]);

        return json_decode($response->getBody(), true);
    }
}
