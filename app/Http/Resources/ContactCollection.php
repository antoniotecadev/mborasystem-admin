<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactCollection extends ResourceCollection
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
        //     'id', 'name', 'email', 'cantina', 'phone', 'deleted_at'
        // );
        // return $this->collection->map->only(
        //     'id', 'name', 'email', 'phone', 'deleted_at', 'organization'
        // );

        return $this->collection->map(function ($item) {
            return [
                'id' => Crypt::encryptString($item->id),
                'name' => $item->name,
                'email' => $item->email,
                'cantina' => $item->cantina,
                'phone' => $item->phone,
                'deleted_at' => $item->deleted_at
            ];
        });
    }

}
