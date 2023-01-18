<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientServiceResource extends JsonResource
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
            'service_id' => $this->resource->service_id,
            // 'services' => ClientServiceResource::collection($this->resource->services),
            'created_at' => $this->created_at,
        ];
    }
}
