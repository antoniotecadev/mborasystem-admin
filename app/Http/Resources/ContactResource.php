<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'nif_bi' => $this->nif_bi,
            'email' => $this->email,
            'phone' => $this->phone,
            'alternative_phone' => $this->alternative_phone,
            'cantina' => $this->cantina,
            'municipality' => $this->municipality,
            'district' => $this->district,
            'street' => $this->street,
            'deleted_at' => $this->deleted_at,
            // 'organization_id' => $this->organization_id,
        ];
    }
}
