<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientProfileItemResource extends JsonResource
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
            'bussines_name' => $this->resource->bussines_name,
            'bussines_address' => $this->resource->bussines_address,
            'bussines_phone_no' => $this->resource->bussines_phone_no,
            'google_my_bussines' => $this->resource->google_my_bussines,
            'country' => $this->resource->country,
            'state' => $this->resource->state,
            'city' => $this->resource->city,
            'zipcode' => $this->resource->zipcode,
            'crunchbase' => $this->resource->crunchbase,
            'linkedin' => $this->resource->linkedin,
            'twitter' => $this->resource->twitter,
            'step' => $this->resource->step,
            'user_id' => $this->resource->user_id,
            'user' => new UserResource($this->resource->user),
            'created_at' => $this->created_at,
        ];
    }
}
