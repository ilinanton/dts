<?php

namespace App\Infrastructure\GitLab;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class ApiClient implements ApiClientInterface
{
    private Client $client;
    public function __construct(string $baseUri, string $privateToken)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'verify' => false,
            'http_errors' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'PRIVATE-TOKEN' => $privateToken,
            ],
            'connect_timeout' => 10,
            'timeout' => 30,
        ]);
    }

    public function get(string $uri): ResponseInterface
    {
        return $this->client->get($uri);
    }
}
