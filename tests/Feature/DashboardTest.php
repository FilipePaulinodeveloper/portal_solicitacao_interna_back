<?php

use App\Enums\Categorias;
use App\Enums\Status;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function criarSolicitacaoParaDashboard(User $usuario, Status $status): Solicitacao
{
    return Solicitacao::create([
        'titulo' => 'Solicitação de teste',
        'descricao' => 'Descrição de teste',
        'categoria' => Categorias::TI,
        'status' => $status,
        'usuario_id' => $usuario->id,
    ]);
}

test('retorna as quantidades de solicitações por status no dashboard', function () {
    $usuario = User::factory()->create();

    criarSolicitacaoParaDashboard($usuario, Status::ABERTO);
    criarSolicitacaoParaDashboard($usuario, Status::ABERTO);
    criarSolicitacaoParaDashboard($usuario, Status::EM_ANDAMENTO);
    criarSolicitacaoParaDashboard($usuario, Status::CONCLUIDO);

    $this->actingAs($usuario, 'sanctum')
        ->getJson('/api/dashboard')
        ->assertOk()
        ->assertExactJson([
            'dados' => [
                'total_solicitacoes' => 4,
                'solicitacoes_abertas' => 2,
                'solicitacoes_em_atendimento' => 1,
                'solicitacoes_concluidas' => 1,
            ],
        ]);
});

test('retorna zero para todos os indicadores quando não há solicitações', function () {
    $usuario = User::factory()->create();

    $this->actingAs($usuario, 'sanctum')
        ->getJson('/api/dashboard')
        ->assertOk()
        ->assertExactJson([
            'dados' => [
                'total_solicitacoes' => 0,
                'solicitacoes_abertas' => 0,
                'solicitacoes_em_atendimento' => 0,
                'solicitacoes_concluidas' => 0,
            ],
        ]);
});

test('exige autenticação para acessar o dashboard', function () {
    $this->getJson('/api/dashboard')->assertUnauthorized();
});
