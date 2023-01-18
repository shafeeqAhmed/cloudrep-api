<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublisherProfileItemResource extends JsonResource
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
            'company_website' => $this->resource->company_website,
            'belongs_to' => $this->resource->belongs_to,
            'user_id' => $this->resource->user_id,
            'step' => $this->resource->step,
            'dropdown' => new DropDownResource($this->resource->dropdown),
            'created_at' => $this->created_at,
        ];
    }
}
