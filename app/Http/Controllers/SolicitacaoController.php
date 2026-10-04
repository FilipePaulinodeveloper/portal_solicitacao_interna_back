<?php

namespace App\Http\Controllers;

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


}
