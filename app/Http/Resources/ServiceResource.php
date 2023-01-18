<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'uuid' => $this->resource->service_uuid,
            'name' => $this->resource->name,
            'type' => $this->resource->type,
            'icon' => $this->resource->icon,
            'image' => $this->resource->image,
            'is_selected' => false,
        ];
    }
}
