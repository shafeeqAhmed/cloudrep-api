<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublisherInterestResource extends JsonResource
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
            'name' => $this->resource->name,
            'is_active' => $this->resource->is_active,
            'user_id' => $this->resource->user_id,
            'dropdown' => new DropDownResource($this->resource->dropdown),
            'created_at' => $this->created_at,
        ];
    }
}
