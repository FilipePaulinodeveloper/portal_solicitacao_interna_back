<?php

namespace App\Models;

use App\Enums\Categorias;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;


class Solicitacao extends Model
{
    
    use HasFactory, Notifiable, HasUuids;

    protected $table = 'solicitacoes';

    public $incrementing = false;
    protected $keyType = 'string';   


    protected $fillable = [
        'titulo',
        'descricao',
        'categoria',
        'status',
        'usuario_id',
    ];

    protected function casts(): array
    {
        return [
            'categoria' => Categorias::class,
            'status' => Status::class,
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }


    public function scopePorCategoria( $query, string $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopePorStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePorTitulo($query, string $titulo)
    {
        return $query->where('titulo', 'like', "%{$titulo}%");
    }

    public function scopeCriadasAPartirDe($query, string $data)
    {
        return $query->whereDate('created_at', '>=', $data);
    }

    public function scopeCriadasAte($query, string $data)
    {
        return $query->whereDate('created_at', '<=', $data);
    }

    public static function obterResumoDashboard(): array
    {
        $resumo = static::query()
            ->selectRaw(
                'COUNT(*) AS total_solicitacoes,
                COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) AS solicitacoes_abertas,
                COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) AS solicitacoes_em_atendimento,
                COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) AS solicitacoes_concluidas',
                [
                    Status::ABERTO->value,
                    Status::EM_ANDAMENTO->value,
                    Status::CONCLUIDO->value,
                ]
            )
            ->first();

        return array_map('intval', $resumo->getAttributes());
    }

}
