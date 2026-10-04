<?php

namespace App\Http\Controllers;
use App\Services\UserService;

class UserController extends BaseCRUDSimplesController
{
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }
}
