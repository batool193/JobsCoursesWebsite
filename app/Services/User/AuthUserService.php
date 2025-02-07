<?php

namespace App\Services\User;

use Illuminate\Auth\AuthenticationException;

class AuthUserService
{
    public function login(array $credentials): array
    {
        $token = auth('api')->attempt($credentials);
        if (!$token) {
            throw new AuthenticationException('Invalid credentials provided.');
        }
        $user = auth('api')->user();
        return [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'type' => $user->getRoleNames(),
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
        ];
    }
}
