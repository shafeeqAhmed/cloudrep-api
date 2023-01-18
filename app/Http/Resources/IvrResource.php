<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IvrResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'contact_no' => $this->contact_no,
            'is_active' => $this->is_active,
        ];
    }
}
