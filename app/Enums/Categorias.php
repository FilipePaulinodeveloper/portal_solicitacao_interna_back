<?php
namespace App\Enums;

enum Categorias: string
{
    case TI = 'TI';
    case RH = 'RH';
    case COMPRAS = 'Compras';
    case FINANCEIRO = 'Financeiro';
    case INFRAESTRUTURA = 'Infraestrutura';
}