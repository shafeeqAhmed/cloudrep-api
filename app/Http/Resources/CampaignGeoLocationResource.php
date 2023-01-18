<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignGeoLocationResource extends JsonResource
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
            'address_type' => $this->resource->address_type,
            'country' => $this->resource->country,
            'state' => $this->resource->state,
            'city_town' => $this->resource->city_town,
            'zipcode' => $this->resource->zipcode,
            'long' => $this->resource->long,
            'lat' => $this->resource->lat,
            'address' => $this->resource->address,
            'file_name' => $this->resource->file_name,
            'file_url' => $this->resource->file_url,
            'created_at' => $this->resource->created_at
        ];
    }
}
