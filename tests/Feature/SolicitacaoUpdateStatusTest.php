<?php

use App\Enums\Categorias;
use App\Enums\Status;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function criarSolicitacaoParaTesteDeEdicao(User $usuario, Status $status): Solicitacao
{
    return Solicitacao::create([
        'titulo' => 'Solicitação original',
        'descricao' => 'Descrição original',
        'categoria' => Categorias::TI,
        'status' => $status,
        'usuario_id' => $usuario->id,
    ]);
}

test('permite atualizar uma solicitação aberta', function () {
    $usuario = User::factory()->create();
    $solicitacao = criarSolicitacaoParaTesteDeEdicao($usuario, Status::ABERTO);

    $this->actingAs($usuario, 'sanctum')
        ->patchJson("/api/solicitacoes/{$solicitacao->id}", [
            'titulo' => 'Solicitação atualizada',
        ])
        ->assertOk()
        ->assertJsonPath('dados.titulo', 'Solicitação atualizada');
});

test('bloqueia a atualização de solicitações que não estão abertas', function () {
    $usuario = User::factory()->create();

    foreach ([Status::EM_ANDAMENTO, Status::CONCLUIDO] as $status) {
        $solicitacao = criarSolicitacaoParaTesteDeEdicao($usuario, $status);

        $this->actingAs($usuario, 'sanctum')
            ->patchJson("/api/solicitacoes/{$solicitacao->id}", [
                'titulo' => 'Não deve ser atualizado',
                'status' => Status::ABERTO->value,
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('solicitacoes', [
            'id' => $solicitacao->id,
            'titulo' => 'Solicitação original',
            'status' => $status->value,
        ]);
    }
});

test('permite excluir uma solicitação aberta', function () {
    $usuario = User::factory()->create();
    $solicitacao = criarSolicitacaoParaTesteDeEdicao($usuario, Status::ABERTO);

    $this->actingAs($usuario, 'sanctum')
        ->deleteJson("/api/solicitacoes/{$solicitacao->id}")
        ->assertOk();

    $this->assertDatabaseMissing('solicitacoes', [
        'id' => $solicitacao->id,
    ]);
});

test('bloqueia a exclusão de solicitações que não estão abertas', function () {
    $usuario = User::factory()->create();

    foreach ([Status::EM_ANDAMENTO, Status::CONCLUIDO] as $status) {
        $solicitacao = criarSolicitacaoParaTesteDeEdicao($usuario, $status);

        $this->actingAs($usuario, 'sanctum')
            ->deleteJson("/api/solicitacoes/{$solicitacao->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('solicitacoes', [
            'id' => $solicitacao->id,
            'status' => $status->value,
        ]);
    }
});
