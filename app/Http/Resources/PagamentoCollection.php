<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PagamentoCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id' => Crypt::encryptString($item->id),
                'inicio' => $item->inicio,
                'fim' => $item->fim,
                'deleted_at' => $item->deleted_at,
                'contact' => $item->contact
            ];
        });
    }

}
