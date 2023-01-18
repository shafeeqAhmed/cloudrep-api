<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LmsCategoryResource extends JsonResource
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
            'uuid' => $this->resource->lms_category_uuid,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'is_active' => $this->resource->is_active,
            'parent_id' => $this->resource->parent_id,
            'created_at' => $this->created_at,
        ];
    }
}
