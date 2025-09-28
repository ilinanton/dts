<?php

declare(strict_types=1);

namespace App\Infrastructure\Weeek;

use App\Domain\Weeek\Common\Repository\WeeekApiClientInterface;
use Exception;
use GuzzleHttp\Client;

final readonly class WeeekApiClient implements WeeekApiClientInterface
{
    private Client $client;

    public function __construct(string $baseUri, string $token)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'verify' => false,
            'http_errors' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
            'connect_timeout' => 10,
            'timeout' => 30,
        ]);
    }

    public function get(string $uri): array
    {
        sleep(1);
        $response = $this->client->get($uri);
        $body = (string)$response->getBody();
        if (200 !== $response->getStatusCode()) {
            throw new Exception('Weeek api error: ' . $body);
        }
        return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getWorkspaceMembers(array $params = []): array
    {
        $uri = 'public/v1/ws/members';
        return $this->get($uri);
    }
}
