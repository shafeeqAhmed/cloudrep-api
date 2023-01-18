<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseOrderResource extends JsonResource
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
            'course_price' => $this->course_price,
            'course_quantity' => $this->course_quantity,
            'price_after_copon' => $this->price_after_copen,
            'user' => new UserResource($this->user),
            'course' => new LmsCourceResource($this->course),
            'campaign' => !empty($this->campaign) ? new CampaignResource($this->campaign) : [],
            'copon' => !empty($this->copon) ? new PromoCodeResource($this->copon) : [],
        ];
    }
}
