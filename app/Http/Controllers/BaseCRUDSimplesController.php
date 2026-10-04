<?php

namespace App\Http\Controllers;

use App\Services\BaseCRUDSimplesService;
use App\Services\BaseSimpleCRUDService;
use Error;
use Faker\Core\Uuid;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Traits\ErrorHandlerTrait;
use Illuminate\Http\Request;

Abstract class BaseCRUDSimplesController extends Controller
{
    
    use AuthorizesRequests, ValidatesRequests;

    protected BaseCRUDSimplesService $service;

    public function __construct(BaseCRUDSimplesService $service)
    {
        $this->service = $service;
    }

    /**
     * Resolve o FormRequest específico baseado no nome da controller
     * Exemplo: UserController -> UserRequest
     */

    protected function obterRequisicaoFormulario(): ?FormRequest
    {
        $nomeController = class_basename(static::class);

        $nomeRequisicao = str_replace(
            'Controller',
            'Request',
            $nomeController
        );

        $classeRequisicao = "App\\Http\\Requests\\{$nomeRequisicao}";

        if (!class_exists($classeRequisicao)) {
            throw new \Exception(
                "FormRequest {$classeRequisicao} não encontrado para "
                . class_basename(static::class)
            );
        }

        return app()->make($classeRequisicao);
    }

    /**
     * Gera uma mensagem de sucesso personalizada baseada no modelo e ação
     */
    protected function obterMensagemSucesso(string $acao = 'criado'): string
    {
        $nomeController = str_replace('Controller', '', class_basename(static::class));

        $acoes = [
            'criado' => 'criado com sucesso',
            'atualizado' => 'atualizado com sucesso',
            'excluido' => 'excluído com sucesso',
            'recuperado' => 'recuperado com sucesso',
            'listado' => 'listado com sucesso',
        ];

        $textoAcao = $acoes[$acao] ?? $acao;

        return "{$nomeController} {$textoAcao}";
    }

    public function index(Request $request)
    {
        $dados = $this->service->index($request->query());
        return response()->json([            
            'dados' => $dados
        ], 200);
    }

    public function store()
    {
        $request = $this->obterRequisicaoFormulario();
        
        $dados = $this->service->store($request->validated());
        
        return response()->json([
            'mensagem' => $this->obterMensagemSucesso('criado'),
            'dados' => $dados
        ], 201);
    }

    public function show(string $id)
    { 
        $dado = $this->service->show($id);
        return response()->json([            
            'dados' => $dado
        ], 200);
    }

    public function update(string $id)
    {      
            $request = $this->obterRequisicaoFormulario();                        
            $dados = $this->service->update($id, $request->validated()); 
            return response()->json([
                'mensagem' => $this->obterMensagemSucesso('atualizado'),
                'dados' => $dados
            ], 200);
    }

    public function destroy(string $id)
    {
         $this->service->destroy($id);
        return response()->json([
            'mensagem' => $this->obterMensagemSucesso('excluido'),            
        ], 200);
    }
}
