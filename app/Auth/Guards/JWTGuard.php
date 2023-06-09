<?php

namespace App\Auth\Guards;

use App\DataObjects\Token;
use App\Models\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class JWTGuard implements Guard
{
    use GuardHelpers;

    private Token $token;

    public function __construct(UserProvider $provider, string $bearer, string $key)
    {
        $this->token = new Token($bearer, $key);
        $this->provider = $provider;
    }

    public function user(): Authenticatable
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        if ($user = $this->provider->retrieveByCredentials([ 'sso_id' => $this->token->id() ])) {
            return $this->user = $user;
        }

        $auth = $this->token->profile();
        User::firstOrCreate(
            [ 'sso_id' => $auth['sub'] ],
            [
            'sso_id' => $auth['sub'],
            'username' => $auth['family_name'].' '.$auth['given_name'],
            'firstname' => $auth['given_name'],
            'lastname' => $auth['family_name'],
            'email' => $auth['email'],
        ]
        );

        $user = $this->provider->retrieveByCredentials([ 'sso_id' => $this->token->id() ]);

        return $this->user = $user;
    }

    /**
     * @param array<string, mixed> $credentials
     * @return bool
     */
    public function validate(array $credentials = []): bool
    {
        return false;
    }
}
