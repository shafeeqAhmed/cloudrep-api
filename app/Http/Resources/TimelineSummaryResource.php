<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class TimelineSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return $request->user_uuid;
        $dateRange = $request->dateRange;
        $customFilter = $request->customFilters;
        $user = User::where('user_uuid', $request->user_uuid)->first();
        $date=$this->created_at;
        $dialed = $this->dialed;
        $duplicated = $this->duplicated;
        $name = $this->name;
        $payout = $this->payout;
        $revenue = $this->revenue;
        $profit = $this->profit;
        // $connected= countCampaignReportingRecord($date,'completed');
        if ($this->publisher_id != null) {
            $incomming = countCallStatus('failed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $connected = countCallStatus('completed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $converted = countCallStatus('converted', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $duplicate = countDuplicateCallsRecord($this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
        } else if ($this->client_id != null) {
            $incomming = countCallStatus('failed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $connected = countCallStatus('completed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $converted = countCallStatus('converted', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $duplicate = countDuplicateCallsRecord($this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            // $converted = countConvertedCallsRecord($this->publisher_id, $this->campaign_id,$this->target_id,$request->user()->id);
        } else if ($this->campaign_id != null) {
            $incomming = countCallStatus('failed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $connected = countCallStatus('completed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $converted = countCallStatus('converted', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $duplicate = countDuplicateCallsRecord($this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            // $converted = countConvertedCallsRecord($this->publisher_id, $this->campaign_id,$this->target_id,$request->user()->id);
        } else if ($this->target_id != null) {
            $incomming = countCallStatus('failed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $connected = countCallStatus('completed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $converted = countCallStatus('converted', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $duplicate = countDuplicateCallsRecord($this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
        } else if ($dialed != null) {
            $incomming = countCallStatus('failed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $connected = countCallStatus('completed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $converted = countCallStatus('converted', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $duplicate = countDuplicateCallsRecord($this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
        } else if ($duplicated !== null) {
            $incomming = countCallStatus('failed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $connected = countCallStatus('completed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $converted = countCallStatus('converted', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $duplicate = countDuplicateCallsRecord($this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
        } else if ($date !== null) {
            $incomming = countCallStatus('failed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $connected = countCallStatus('completed', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $converted = countCallStatus('converted', $this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
            $duplicate = countDuplicateCallsRecord($this->publisher_id, $this->client_id, $this->campaign_id, $this->target_id, $request->user()->id, $dialed, $duplicated, $date, $user, $dateRange, $customFilter);
        }

        return [
            'date' => $date != null ? $date->translatedFormat('F j') : null,
            'duplicated' => $duplicated == 1 ? 'yes' : 'no',
            'dialed' => $dialed,
            'name' => $name,
            'incomming' => $incomming,
            'connected' => $connected,
            'converted' => $converted,
            'payout' => $payout,
            'revenue' => $revenue,
            'profit' => $profit,
            'duplicate' => $duplicate,
            'currency' => $this->currency
        ];
    }
}
