<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyVertialResource extends JsonResource
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
            'icon' => $this->resource->icon,
            'business_category_id' => $this->resource->bussines_category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'bussines_category' => new BussinesCategoryResource($this->resource->bussines_category),
        ];
    }
}
