<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Usuário Um',
                'email' => 'usuario1@example.com',
                'password' => 'SenhaDev123!',
            ],
            [
                'name' => 'Usuário Dois',
                'email' => 'usuario2@example.com',
                'password' => 'SenhaDev123!',
            ],
            [
                'name' => 'Usuário Três',
                'email' => 'usuario3@example.com',
                'password' => 'SenhaDev123!',
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::updateOrCreate(
                ['email' => $usuario['email']],
                [
                    'name' => $usuario['name'],
                    'password' => Hash::make($usuario['password']),
                ]
            );
        }
    }
}
