<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserApplicationResource extends JsonResource
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
        'user_id' => $this->user_id,
        'fav_menu' => json_decode($this->fav_menu),
        'is_dark_mode' => $this->is_dark_mode
       ];
    }
}
