<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AgenteCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id' => Crypt::encryptString($item->id),
                'nome_completo' => $item->inicio,
                'telefone' => $item->fim,
                'deleted_at' => $item->deleted_at,
                'equipa' => $item->equipa
            ];
        });
    }

}
