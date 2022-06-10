<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class agenteResource extends JsonResource
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
            'nome_completo' => $this->nome_completo,
            'bi' => $this->bi,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'telefone_alternativo' => $this->telefone_alternativo,
            'municipio' => $this->municipio,
            'bairro' => $this->bairro,
            'rua' => $this->rua,
            'banco' => $this->banco,
            'estado' => $this->estado,
            'motivo_elimina' => $this->motivo_elimina,
            'created_at' => $this->created_at->format('Y-m-d H:m'),
            'deleted_at' => $this->deleted_at,
            'equipa_id' => $this->equipa_id
        ];
    }
}
