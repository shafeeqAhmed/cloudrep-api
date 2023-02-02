<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignCustomResource extends JsonResource
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
            'type' => $this->resource->type,
            'cost_per_call' => $this->cost_per_call,
            'cost_per_call_duration' => $this->cost_per_call_duration,
            'payout_per_call' => $this->payout_per_call,
            'payout_per_call_duration' => $this->payout_per_call_duration,
        ];
    }
}
