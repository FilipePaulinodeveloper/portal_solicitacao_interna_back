<?php

use App\Enums\Categorias;
use App\Enums\Status;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function criarSolicitacaoParaAtualizacaoStatus(User $usuario): Solicitacao
{
    return Solicitacao::create([
        'titulo' => 'Solicitação original',
        'descricao' => 'Descrição original',
        'categoria' => Categorias::TI,
        'status' => Status::ABERTO,
        'usuario_id' => $usuario->id,
    ]);
}

test('atualiza somente o status da solicitação', function () {
    $usuario = User::factory()->create();
    $solicitacao = criarSolicitacaoParaAtualizacaoStatus($usuario);

    $response = $this->actingAs($usuario, 'sanctum')
        ->patchJson("/api/solicitacoes/{$solicitacao->id}/status", [
            'status' => Status::EM_ANDAMENTO->value,
            'titulo' => 'Título que não deve ser alterado',
        ]);

    $response
        ->assertOk()
        ->assertJsonPath('dados.status', Status::EM_ANDAMENTO->value)
        ->assertJsonPath('dados.titulo', 'Solicitação original');

    $this->assertDatabaseHas('solicitacoes', [
        'id' => $solicitacao->id,
        'status' => Status::EM_ANDAMENTO->value,
        'titulo' => 'Solicitação original',
    ]);
});

test('rejeita status ausente ou inválido', function () {
    $usuario = User::factory()->create();
    $solicitacao = criarSolicitacaoParaAtualizacaoStatus($usuario);

    $this->actingAs($usuario, 'sanctum')
        ->patchJson("/api/solicitacoes/{$solicitacao->id}/status", [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('status', 'erros');

    $this->actingAs($usuario, 'sanctum')
        ->patchJson("/api/solicitacoes/{$solicitacao->id}/status", [
            'status' => 'Inválido',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('status', 'erros');

    $this->assertDatabaseHas('solicitacoes', [
        'id' => $solicitacao->id,
        'status' => Status::ABERTO->value,
    ]);
});
