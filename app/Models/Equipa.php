<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipa extends Model
{
    use SoftDeletes, HasFactory;

    public function agentes()
    {
        return $this->hasMany(Agente::class);
    }

    public function getNameAttribute()
    {
        return $this->codigo;
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('codigo');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('codigo', 'like', '%'.$search.'%');
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
