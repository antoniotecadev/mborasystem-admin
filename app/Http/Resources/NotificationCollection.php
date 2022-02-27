<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id' => Crypt::encryptString($item->id),
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'imei' => $item->imei,
                'codigo_equipa' => $item->codigo_equipa,
                'read_contact' => $item->read_contact,
                'created_at' => $item->created_at->format('Y-m-d H:m')
            ];
        });
    }

}
