<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EquipaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id' => Crypt::encryptString($item->id),
                'codigo' => $item->codigo,
                'estado' => $item->estado,
                'deleted_at' => $item->deleted_at
            ];
        });
    }

}
