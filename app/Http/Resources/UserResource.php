<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'uuid' => $this->resource->user_uuid,
            'name' => $this->resource->name,
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'username' => $this->resource->first_name . $this->resource->last_name,
            'email' => $this->resource->email,
            'phone_no' => $this->resource->phone_no,
            'role' => $this->resource->role,
            'profile_photo' => url($this->resource->profile_photo_path),
            'created_at' => $this->created_at,
        ];
    }
}
