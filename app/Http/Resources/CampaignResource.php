<?php

namespace App\Http\Resources;

use App\Models\CampaignLms;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
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
            'step' => $this->resource->step,
            'user_uuid' => !empty($this->resource->user) ? $this->resource->user->user_uuid : null,
            'service_uuid' => !empty($this->resource->service) ? $this->resource->service->service_uuid : null,
            'name' => $this->resource->name,
            'campaign_name' => $this->resource->campaign_name,
            'phone_no' => $this->resource->phone_no,
            'title' => $this->resource->title,
            'email' => $this->resource->email,
            'address' => $this->resource->address,
            'country' => $this->resource->country,
            'state' => $this->resource->state,
            'city' => $this->resource->city,
            'zipcode' => $this->resource->zipcode,
            'is_published' => $this->resource->is_published,
            'service_id' => $this->resource->service_id,
            'category_uuid' => !empty($this->resource->category) ? $this->resource->category->uuid : null,
            'vertical_uuid' => !empty($this->resource->vertical) ? $this->resource->vertical->uuid : null,
            'language' => $this->resource->language,
            'currency' => $this->resource->currency,
            'timeZone' => $this->resource->time_zone,
            'start_date' => $this->resource->start_date,
            'start_time' => $this->resource->start_time,
            'end_date' => $this->resource->end_date,
            'end_time' => $this->resource->end_time,
            'description' => $this->resource->descripiton,
            'website_url' => $this->resource->website_url,
            'deeplink' => $this->resource->deeplink,
            'blog_url' => $this->resource->blog_url,
            'facebook_url' => $this->resource->facebook_url,
            'twitter_url' => $this->resource->twitter_url,
            'linkedin_url' => $this->resource->linkedin_url,
            'cost_per_call' => $this->resource->cost_per_call,
            'client_duration_type' => $this->resource->client_duration_type,
            'client_per_call_duration' => $this->resource->client_per_call_duration,
            'payout_per_call' => $this->resource->payout_per_call,
            'publisher_duration_type' => $this->resource->publisher_duration_type,
            'publisher_per_call_duration' => $this->resource->publisher_per_call_duration,
            'ear_time' => $this->resource->ear_time,
            'campaign_rate' => $this->resource->campaign_rate,
            'routing' => $this->resource->routing,
            'addressType' => $this->resource->addressType,
            'ivr_id' => $this->resource->ivr_id,
            'client_image' => $this->resource->client_image,
            'is_agent_include' => $this->resource->is_agent_include,
            'agent_image' => $this->resource->agent_image,
            'publisher_image' => $this->resource->publisher_image,
            'created_at' => $this->created_at,
            'agent_payout_setting' => new CampaignAgentPayoutSettingResource($this->resource->agentPayoutSetting),
            'publisher_payout_setting' => new CampaignPublisherPayoutSettingResource($this->resource->publisherPayoutSetting),
            'campaign_location' => CampaignGeoLocationResource::collection($this->resource->campaignLocations),
            'categories' => new BussinesCategoryResource($this->category),
            'campaign_lms' => $this->resource->campaignLms,
        ];
    }
}
