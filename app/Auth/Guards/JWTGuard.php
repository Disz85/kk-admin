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
        $user = User::findOrNew([ 'sso_id' => $auth['sub'] ]);
        $user->save();

        $user = $this->provider->retrieveByCredentials([ 'sso_id' => $this->token->id() ]);

        return $this->user = $user;
    }

    public function validate(array $credentials = []): bool
    {
        return false;
    }
}
