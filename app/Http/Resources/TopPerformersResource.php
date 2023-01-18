<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopPerformersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $name = $this->name;
        $payout=$this->payout;
        $revenue=$this->revenue;
        $profit = $this->profit;
        $currency = $this->currency;
        if($this->publisher_id != null) {
            $calls = countTotalCallsRecord($this->publisher_id,$this->client_id,$this->campaign_id,$this->target_id,$request->user()->id);
            $converted = countConvertedCallsRecord($this->publisher_id,$this->client_id,$this->campaign_id,$this->target_id,$request->user()->id);
        } else if($this->campaign_id != null) {
            $calls = countTotalCallsRecord($this->publisher_id,$this->client_id,$this->campaign_id,$this->target_id,$request->user()->id);
            $converted = countConvertedCallsRecord($this->publisher_id,$this->client_id,$this->campaign_id,$this->target_id,$request->user()->id);
        } else if($this->target_id != null) {
            $calls = countTotalCallsRecord($this->publisher_id,$this->client_id,$this->campaign_id,$this->target_id,$request->user()->id);
            $converted = countConvertedCallsRecord($this->publisher_id,$this->client_id,$this->campaign_id,$this->target_id,$request->user()->id);
        }
     

        return [
            'name' => $name,
            'converted' => $converted,
            'calls' => $calls,
            'payout'=>$payout,
            'revenue'=>$revenue,
            'profit'=>$profit,
            'currency'=>$currency
        ];
    }
}
