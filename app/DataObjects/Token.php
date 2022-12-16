<?php

namespace App\DataObjects;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

class Token
{
    private stdClass $token;

    public function __construct(string $token, string $key)
    {
        $this->token = JWT::decode($token, $this->formatKey($key));
    }

    public function id()
    {
        return $this->token->sub;
    }

    public function profile(): array
    {
        return [
            'sub' => $this->token->sub,
            'given_name' => $this->token->given_name,
            'family_name' => $this->token->family_name,
            'email' => $this->token->email,
        ];
    }

    private function formatKey(string $key): Key
    {
        return new Key(
            "-----BEGIN PUBLIC KEY-----\n" . wordwrap($key, 64, "\n", true) . "\n-----END PUBLIC KEY-----",
            'RS384'
        );
    }
}
