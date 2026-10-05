<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('cria os três usuários com as senhas definidas pelo seeder', function () {
    $this->seed();

    expect(User::count())->toBe(3);

    foreach (['usuario1', 'usuario2', 'usuario3'] as $numero) {
        $usuario = User::where('email', "{$numero}@example.com")->firstOrFail();

        expect(Hash::check('SenhaDev123!', $usuario->password))->toBeTrue();
    }
});
