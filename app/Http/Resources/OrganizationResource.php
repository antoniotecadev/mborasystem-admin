<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'name' => $this->name,
            'municipality' => $this->municipality,
            'district' => $this->district,
            'street' => $this->street,
            'deleted_at' => $this->deleted_at,
            'contacts' => $this->contacts()->orderByName()->get()->map->only('id', 'name', 'email', 'phone'),
        ];
    }
}

