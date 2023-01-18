<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignReportingResource extends JsonResource
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
            'call_date' => $this->call_date,
            'profit' => $this->profit,
            'campaign' => $this->campaign,
            'publisher' => $this->publisher,
            'caller_id' => $this->caller_id,
            'dialed' => $this->dialed,
            'time_to_call' => $this->time_to_call,
            'duplicate' => $this->duplicate == 1 ? true : false,
            'hangup' => $this->hangup,
            'time_to_connect' => $this->time_to_connect,
            'target' => $this->target,
            'revenue' => $this->revenue,
            'payout' => $this->payout,
            'duration' => $this->duration,
            'recording' => $this->recording,
            // 'campaign' => new CampaignResource($this->campaign),
            'created_at' => $this->created_at,
        ];
    }
}
