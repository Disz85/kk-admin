<?php

namespace App\Auth\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class AccessTokenService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws GuzzleException
     */
    public function getToken(): string
    {
        return $this->getAuthorizationToken()['access_token'];
    }

    /**
     * @return array<string, string>
     * @throws GuzzleException
     */
    public function getAuthorizationToken(): array
    {
        if (Cache::has('keycloak-admin-credentials')) {
            return Cache::get('keycloak-admin-credentials');
        }

        $response = $this->client->post(config('sso.token-url'), $this->getOptions());

        $credentials = json_decode($response->getBody()->getContents(), true);

        return tap($credentials, function ($credentials) {
            Cache::put('keycloak-admin-credentials', $credentials, $credentials['expires_in']);
        });
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getOptions(): array
    {
        return [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => config('sso.client-id'),
                'client_secret' => config('sso.client-secret'),
            ],
        ];
    }
}
