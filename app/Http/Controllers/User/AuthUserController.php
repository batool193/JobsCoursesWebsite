<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\User\AuthUserService;
use App\Http\Requests\User\Auth\LoginUserRequest;


class AuthUserController extends Controller
{
    protected AuthUserService $authService;

    public function __construct(AuthUserService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $response = $this->authService->login($request->validated());
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
