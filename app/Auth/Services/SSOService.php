<?php

namespace App\Auth\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class SSOService
{
    protected Client $client;
    protected AccessTokenService $accessTokenService;

    public function __construct(Client $client, AccessTokenService $accessTokenService)
    {
        $this->client = $client;
        $this->accessTokenService = $accessTokenService;
    }

    /**
     * @param array<string, mixed> $attributes
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function addUser(array $attributes): ResponseInterface
    {
        return $this->postAuthenticatedRequest('users', $attributes);
    }

    /**
     * @param string $resource
     * @param array<string, mixed> $data
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private function postAuthenticatedRequest(string $resource, array $data): ResponseInterface
    {
        return $this->sendAuthenticatedRequest('POST', '/' . $resource, $data);
    }

    /**
     * @param string $method
     * @param string $resource
     * @param array<string, mixed>|null $data
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private function sendAuthenticatedRequest(string $method, string $resource, ?array $data = null): ResponseInterface
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessTokenService->getToken(),
                'Content-Type' => 'application/json',
            ],
        ];

        if (($method === 'POST' || $method === 'PUT') && $data) {
            $options['json'] = $data;
        }

        return $this->client->request($method, config('sso.realm-url') . $resource, $options);
    }
}
