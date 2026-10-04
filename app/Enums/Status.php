<?php
namespace App\Enums;

enum Status: string
{
    case ABERTO = 'Aberto';
    case EM_ANDAMENTO = 'Em andamento';
    case CONCLUIDO = 'Concluído';
}