<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TwilioNumberResource extends JsonResource
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
            'uuid' => $this->number_sid,
            'number_sid' => $this->number_sid,
            'number' => $this->number,
            'country' => $this->country,
            'name' => $this->name,
            'allocated' => $this->allocated,
            'renews' => $this->renews,
            'last_call_date' => $this->last_call_date,
            'campaign' => $this->campaign,
            'number_pool' => $this->number_pool,
            'publisher' => $this->publisher,
            'status' => $this->status == 1 ? true : false,
            'tags' =>  TwilioNumberTagResource::collection($this->tags),


        ];
    }
}
