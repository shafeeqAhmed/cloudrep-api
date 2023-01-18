<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignPublisherPayoutSettingResource extends JsonResource
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
            'payout_type' => $this->resource->payout_type,
            'payout_on' => $this->resource->payout_on,
            'length' => $this->resource->length,
            'payout_amount' => $this->resource->payout_amount,
            'revshare_payout_limits' => $this->resource->revshare_payout_limits == 1 ? true : false,
            'min' => $this->resource->min,
            'max' => $this->resource->max,
            'duplicate_payouts' => $this->resource->duplicate_payouts,
            'days' => $this->resource->days,
            'hours' => $this->resource->hours,
            'payout_hours' => $this->resource->payout_hours == 1 ? true : false,
            'open_time' => $this->resource->open_time,
            'close_time' => $this->resource->close_time,
            'start_break_time' => $this->resource->start_break_time,
            'break_duration' => $this->resource->break_duration,
            'time_zone' => $this->resource->time_zone,
            'limit_payout' => $this->resource->limit_payout == 1 ? true : false,
            'global_cap' => $this->resource->global_cap == 1 ? true : false,
            'global_payout_cap' => $this->resource->global_payout_cap == 1 ? true : false,
            'monthly_cap' => $this->resource->monthly_cap == 1 ? true : false,
            'monthly_payout_cap' => $this->resource->monthly_payout_cap == 1 ? true : false,
            'daily_cap' => $this->resource->daily_cap == 1 ? true : false,
            'daily_payout_cap' => $this->resource->daily_payout_cap == 1 ? true : false,
            'hourly_cap' => $this->resource->hourly_cap == 1 ? true : false,
            'hourly_payout_cap' => $this->resource->hourly_payout_cap == 1 ? true : false,
            'concurrency_cap' => $this->resource->concurrency_cap == 1 ? true : false,
            'user_id' => $this->resource->user_id,
            'campaign_id' => $this->resource->campaign_id,
            'created_at' => $this->resource->created_at
        ];
    }
}
