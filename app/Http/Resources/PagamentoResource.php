<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PagamentoResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pacote' => $this->pacote,
            'tipo_pagamento' => $this->tipo_pagamento,
            'inicio' => $this->inicio,
            'fim' => $this->fim,
            'deleted_at' => $this->deleted_at,
            'contact_id' => $this->contact_id
        ];
    }
}
