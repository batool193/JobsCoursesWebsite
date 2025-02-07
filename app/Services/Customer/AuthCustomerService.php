<?php

namespace App\Services\Customer;

use App\Models\Customer;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthCustomerService
{
    public function register(array $data): array
    {
        try {
            $customer = Customer::create([
                'name' => $data["name"],
                'email' => $data["email"],
                'password' => Hash::make($data["password"]),
                'address' => $data["address"],
                'education' => $data["education"],
            ]);
            $token = JWTAuth::fromUser($customer);


            return [
                'customer' => $customer,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function login(array $credentials): array
    {
        $token = auth('customer')->attempt($credentials);
        if (!$token) {
            throw new AuthenticationException('Invalid credentials provided.');
        }

        $customer = auth('customer')->user();
        return [
            'customer' => $customer,
            'type' => 'customer',
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ];
    }
}
