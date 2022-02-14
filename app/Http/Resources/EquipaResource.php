<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipaResource extends JsonResource
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
            'codigo' => $this->codigo,
            'estado' => $this->estado,
            'deleted_at' => $this->deleted_at,
            'agentes' => $this->agentes()->orderBy('id', 'desc')->limit(3)->get()->map(function ($item) {return ['id' => Crypt::encryptString($item->id), 'nome_completo' => $item->nome_completo, 'telefone' => $item->telefone, 'email' => $item->email, 'deleted_at' => $item->deleted_at]; }),
        ];
    }
}
