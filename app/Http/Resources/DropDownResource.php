<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DropDownResource extends JsonResource
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
            'uuid' => $this->resource->uuid,
            'label' => $this->resource->label,
            'value' => $this->resource->value,
            'type' => $this->resource->type,
            'created_at' => $this->created_at,
        ];
    }
}
