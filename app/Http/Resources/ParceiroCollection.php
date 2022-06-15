<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ParceiroCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return $this->collection->map->only(
        //     'id', 'name', 'email', 'cantina','phone', 'estado', 'deleted_at''
        // );

        return $this->collection->map(function ($item) {
            return [
                'id' => $item->id,
                'idcrypt' => Crypt::encryptString($item->id),
                'name' => $item->name,
                'email' => $item->email,
                'cantina' => $item->cantina,
                'phone' => $item->phone,
                'estado' => $item->estado,
                'imei' => $item->imei,
                'codigo_equipa' => $item->codigo_equipa,
                'read_contact' => $item->read_contact,
                'created_at' => $item->created_at->format('Y-m-d H:m'),
                'deleted_at' => $item->deleted_at
            ];
        });
    }

}
