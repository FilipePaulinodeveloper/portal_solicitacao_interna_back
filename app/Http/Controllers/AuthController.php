<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function login(LoginRequest $request)
    {
        
        return $this->authService->login($request->validated());
    }

    public function logout(Request $request)
    {      
        return $this->authService->logout($request);
    }
}
