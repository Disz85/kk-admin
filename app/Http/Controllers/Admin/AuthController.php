<?php

namespace App\Http\Controllers\Admin;

use App\DataObjects\Token;
use App\Models\User;
use App\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    /**
     * @param Request $request
     * @return UserResource
     */
    public function login(Request $request): UserResource
    {
        if ($user = Auth::user()) {
            if (! $user->can(config('auth.default_permission'))) {
                abort(401);
            }

            return new UserResource($user);
        }

        $token = new Token($request->bearerToken(), config('sso.key'));

        $profile = $token->profile();

        $user = User::firstOrNew([ 'sso_id' => $profile['sub'] ]);

        $user->fill([
            'firstname' => $profile['given_name'],
            'lastname' => $profile['family_name'],
            'email' => $profile['email'],
            'username' => $profile['email'],
            'sso_id' => $profile['sub'],
        ]);

        $user->save();

        if (! $user->can('manage-admin')) {
            abort(401);
        }

        Auth::login($user);

        return new UserResource($user);
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        if (Auth::check()) {
            Auth::logout();
        }
    }
}
