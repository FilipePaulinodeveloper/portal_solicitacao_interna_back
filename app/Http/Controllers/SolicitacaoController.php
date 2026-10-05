<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\AtualizarStatusSolicitacaoRequest;
use App\Http\Requests\IndexSolicitacaoRequest;
use App\Services\SolicitacaoService;
use Illuminate\Http\Request;

class SolicitacaoController extends BaseCRUDSimplesController
{
    public function __construct(SolicitacaoService $service)
    {
        parent::__construct($service);
    }

    public function index(Request $request)
    {
        $filters = $request->validate(
            (new IndexSolicitacaoRequest)->rules()
        );
            
        $dados = $this->service->index($filters);
        

        return response()->json([
            'dados' => $dados
        ], 200);
    }

    public function atualizarStatus(AtualizarStatusSolicitacaoRequest $request, string $id)
    {
        $solicitacao = $this->service->atualizarStatus(
            $id,
            $request->validated()
        );

        return response()->json([
            'mensagem' => 'Status da solicitação atualizado com sucesso.',
            'dados' => $solicitacao,
        ], 200);
    }
}
