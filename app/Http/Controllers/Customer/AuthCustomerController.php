<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Customer\AuthCustomerService;
use App\Http\Requests\Customer\Auth\LoginCustomerRequest;
use App\Http\Requests\Customer\Auth\RegisterCustomerRequest;

class AuthCustomerController extends Controller
{
    protected AuthCustomerService $authcustomerservice;

    public function __construct(AuthCustomerService $authcustomerservice)
    {
        $this->authcustomerservice = $authcustomerservice;
    }

    public function register(RegisterCustomerRequest $request): JsonResponse
    {
        $data = $this->authcustomerservice->register($request->validated());
        return self::success($data, 'Registered successfully!', 201);
    }

    public function login(LoginCustomerRequest $request): JsonResponse
    {
        $response = $this->authcustomerservice->login($request->validated());
        return self::success($response, 'Logged in successfully', 200);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        return self::success(null, 'Logged out successfully');
    }

    public function refresh(): JsonResponse
    {
        return self::success([
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
