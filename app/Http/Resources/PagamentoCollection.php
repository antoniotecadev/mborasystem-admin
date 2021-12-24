<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PagamentoCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map->only(
            'id', 'pacote', 'inicio', 'fim', 'deleted_at', 'contact'
        );
    }

}
