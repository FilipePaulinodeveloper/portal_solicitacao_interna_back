<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthService 
{   

    private function verificarCredenciais(array $credentials, ?User $user): bool
    {      
        return $user && Hash::check($credentials['password'], $user->password);
    }

    private function encontrarUsuarioPorEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    private function createToken(User $user): string
    {
        return $user->createToken('api-token')->plainTextToken;
    }

    public function login(array $credentials)
    {
        $user = $this->encontrarUsuarioPorEmail($credentials['email']);

        if (!$this->verificarCredenciais($credentials, $user)) {
            return response()->json([
                'mensagem' => 'Email ou senha inválidos.',
            ], 401);
        }
        
        $token = $this->createToken($user);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'mensagem' => 'Logout realizado com sucesso.'
        ]);
    }

}