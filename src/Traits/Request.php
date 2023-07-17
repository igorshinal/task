<?php

namespace Traits;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


trait Request
{
    /**
     * @param string $url
     * @param string|null $token
     * @param string $method
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function request(string $url, ?string $token = null, string $method = 'GET'): array
    {
        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
        ];

        try {
            $response = $client->request($method, $url, [
                'headers' => $headers,
                'query' => [
                    'access_key' => $token ?? '',
                    'base' => 'EUR',
                ],
            ]);

            return [
                'statusCode' => $response->getStatusCode(),
                'body' => json_decode($response->getBody()->getContents(), true),
            ];

        } catch (Exception $e) {
            throw new Exception('Request has problem');
        }
    }
}



