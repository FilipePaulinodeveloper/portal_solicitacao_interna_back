<?php

namespace App\Services;

use Error;
use Faker\Core\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

abstract class BaseCRUDSimplesService
{
    

    protected Model $model;
    protected string $cacheKey;

    public function __construct(Model $model)
    {
        $this->model = $model;       
    }

    public function index(?array $filters = null)
    {                           
        return $this->model->paginate(15);              
    }

    public function store(array $dados)
    { 

        $dados = $this->model->create($dados);          
        
        return $dados;  

    }

    public function show(string $id)
    {       
        return $this->model->findOrFail($id);
    }

    public function update(string $id, array $dados)
    {       
        
        $registro = $this->model->findOrFail($id);                  
        $registro->update($dados);    
            
        return $registro;
         
    }

    public function destroy(string $id)
    {       
        $registro = $this->model->findOrFail($id);          
        $registro->delete();          
         
    }
}