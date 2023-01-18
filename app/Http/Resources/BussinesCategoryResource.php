<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BussinesCategoryResource extends JsonResource
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
            'user_id' => $this->resource->user_id,
            'verticals' => CompanyVertialResource::collection($this->verticals),
            'created_at' => $this->created_at,
        ];
    }
}
