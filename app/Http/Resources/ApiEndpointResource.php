<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiEndpointResource extends JsonResource
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
        'type' => $this->type,
        'url' => $this->url,
        'title' => $this->title,
        'description' => $this->description,
        'apiParameter' => ApiParameterResource::collection($this->apiParameter),
        'apiResponse' => ApiResponseResource::collection($this->apiResponse),
        'created_at' => $this->created_at
       ];
    }
}
