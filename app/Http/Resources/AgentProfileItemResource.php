<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgentProfileItemResource extends JsonResource
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
            'user_id' => $this->resource->user_id,
            'location' => $this->resource->location,
            'device' => $this->resource->device,
            'step' => $this->resource->step,
            // 'created_at' => $this->created_at,
        ];
    }
}
