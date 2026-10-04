<?php

namespace App\Services;

use App\Models\Solicitacao;

class DashboardService
{
    public function obterResumo(): array
    {
        return Solicitacao::obterResumoDashboard();
    }
}
