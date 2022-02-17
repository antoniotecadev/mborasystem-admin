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
            'email' => $this->email,
            'telefone' => $this->telefone,
            'telefone_alternativo' => $this->telefone_alternativo,
            'municipio' => $this->municipio,
            'bairro' => $this->bairro,
            'rua' => $this->rua,
            'banco' => $this->banco,
            'estado' => $this->estado,
            'senha' => $this->senha,
            'deleted_at' => $this->deleted_at,
            'equipa_id' => $this->equipa_id
        ];
    }
}
