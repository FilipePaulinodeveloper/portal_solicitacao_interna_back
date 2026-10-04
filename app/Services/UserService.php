<?php

namespace App\Services;

use App\Models\User;


class UserService extends BaseCRUDSimplesService
{
    public function __construct(User $model)
    {
        
        parent::__construct($model);
    }    

    public function store(array $dados)
    { 
        $dados['password'] = bcrypt($dados['password']);
        return parent::store($dados);
    }
  
}