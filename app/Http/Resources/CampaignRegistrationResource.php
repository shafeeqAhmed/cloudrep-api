<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignRegistrationResource extends JsonResource
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
            'username' => $this->resource->username,
            'email' => $this->resource->email,
            'title' => $this->resource->title,
            'profile_picture' => $this->resource->profile_picture,
            'address' => $this->resource->address,
            'status' => $this->resource->status,
            'working_state' => $this->resource->working_state,
            'working_hours' => $this->resource->working_hours == 1 ? true : false,
            'open_time' => $this->resource->open_time,
            'close_time' => $this->resource->close_time,
            'time_zone' => $this->resource->time_zone,
            'user_id' => $this->resource->user_id,
            'created_at' => $this->resource->created_at
        ];
    }
}
