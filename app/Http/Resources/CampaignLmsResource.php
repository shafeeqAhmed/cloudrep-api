<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignLmsResource extends JsonResource
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
            'type' => $this->type,
            'campaign' => new CampaignResource($this->campaign),
             'category_id' => $this->category_id,
             'course' => new LmsCourceResource($this->course),
            'is_active' => $this->is_active == 1 ? true : false,
            'created_at' => $this->created_at,

        ];
    }
}
