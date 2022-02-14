<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agente extends Model
{
    use SoftDeletes, HasFactory;

    protected $guarded = [];

    public function equipa()
    {
        return $this->belongsTo(Equipa::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('nome_completo', 'like', '%'.$search.'%')
                    ->orWhere('telefone', 'like', '%'.$search.'%')
                    ->orWhere('municipio', 'like', '%'.$search.'%')
                    ->orWhereHas('equipa', function ($query) use ($search) {
                        $query->where('codigo', 'like', '%'.$search.'%');
                    });
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
