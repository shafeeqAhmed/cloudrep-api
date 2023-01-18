<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignFilterReportResource extends JsonResource
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
            'filter_report_name' => $this->filter_report_name,
            'filter_user_uuid' => $this->filter_user_uuid,
            'filter_time_zone' => $this->filter_time_zone,
            'filter_date_range' => unserialize($this->filter_date_range),
            'custom_filters' => $this->custom_filters
        ];
    }
}
