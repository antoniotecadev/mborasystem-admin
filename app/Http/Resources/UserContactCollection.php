<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserContactCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map->only('id', 'first_name', 'last_name', 'empresa', 'phone');
    }
}
