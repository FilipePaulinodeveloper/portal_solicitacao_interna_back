<?php

namespace App\Services;

use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SolicitacaoService extends BaseCRUDSimplesService
{
    public function __construct(Solicitacao $model)
    {
        
        parent::__construct($model);
    }    

    public function index(?array $filters = null)
    {
        
        $query = $this->model->query();
        
        $query
            ->when(
                $filters['data_inicio'] ?? null,
                fn ($query, $data) => $query->criadasAPartirDe($data)
            )
            ->when(
                $filters['data_fim'] ?? null,
                fn ($query, $data) => $query->criadasAte($data)
            )
            ->when(
                $filters['categoria'] ?? null,
                fn ($query, $categoria) => $query->porCategoria($categoria)
            )
            ->when(
                $filters['status'] ?? null,
                fn ($query, $status) => $query->porStatus($status)
            )
            ->when(
                $filters['titulo'] ?? null,
                fn ($query, $titulo) => $query->porTitulo($titulo)
            );

        $query->orderBy('created_at', 'desc');
        $query->with('usuario:id,name,email');
        return $query->paginate(15);
    }

     public function store(array $dados)
    { 
        $dados['usuario_id'] =  Auth::id();
        return parent::store($dados);
    }
    
  
}