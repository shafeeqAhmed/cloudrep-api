<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PerformanceReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

         $call_status=$this->call_status;
         $date=$this->Date;        
         $payout=$this->payout;
         $revenue=$this->revenue;
         $currency = $this->currency;
         $incomming= countCampaignReportingRecord($date,'failed',$request->user()->id);
         $connected= countCampaignReportingRecord($date,'completed',$request->user()->id);
         $converted= countCampaignReportingRecord($date,'converted',$request->user()->id);
         $not_answer= countCampaignReportingRecord($date,'no-answer',$request->user()->id);

           return [
          'revenue'=>$revenue,
          'status'=>$call_status,
          'date'=>$this->Day,
          'incomming'=>$incomming,
          'converted'=>$converted,
          'connected'=>$connected,
          'not_answer'=>$not_answer,
          'payout'=>$payout,
          'currency'=>$currency
        ];
    }
}