<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignReporting;
use App\Models\TargetListing;
use App\Models\TwillioNumber;
use Carbon\Carbon;
use App\Models\User;

class Reporting
{
    private $payout;
    private $revenue;
    private $profit;
    public function __construct()
    {
        $this->payout = 0;
        $this->revenue = 0;
        $this->profit = 0;
    }
    public function storeCampaignResults()
    {

        $record = CampaignReporting::where('parent_call_sid', request('ParentCallSid'))->exists();

        if ($record) {
            return  $this->updateRecord();
        } else {
            return  $this->storeRecord();
        }

        // 'time_to_call' => $time_to_call,
        //     'time_to_connect' => $time_to_connect,
        //     'revenue' => $revenue,
        //     'payout' => $payout,
        //     'duration' => $duration,
        //     'recording' => $recording,
        //     'client_uuid' => $client_uuid,
        //     'hangup_reason' => 'Reason',
        //     'longitude' => $longitude,
        //     'latitude' => $latitude,


        // if (request()->has('ParentCallSid')) {
        //     dd('child record');
        // } else {
        //     dd('parent record');
        // }
        // $call_time_limit = 36;
        // $call_date = '2022-11-09';
        // $caller_id = 1;
        // $client_id = 4;
        // $publisher_id = 3;
        // $campaignId = 2;
        // $duration = '23:11:14';
        // $time_to_connect = '23:11:14';
        // $recording = 30;
        // $longitude = '89.99';
        // $latitude = '90.77';
        // $time_to_call = '23:11:14';
        // $client = user::find($client_id);
        // $client_uuid = $client->user_uuid;

        // $client_cost_per_call = $campaign->cost_per_call;
        // $client_per_call_duration = $campaign->client_per_call_duration;

        // // calculate revenue
        // $revenue = 0;
        // if ($call_time_limit >= $client_per_call_duration) {
        //     $client->withdraw($client_cost_per_call);
        //     $client_balance = $client->balance;
        //     $revenue = $client_cost_per_call;
        // }

        // // calculate payout
        // $payout_per_call = $campaign->payout_per_call;
        // $publisher_per_call_duration = $campaign->publisher_per_call_duration;
        // $payout = 0;
        // $publisher = user::find($publisher_id);
        // if ($call_time_limit >= $publisher_per_call_duration) {

        //     $publisher->deposit($payout_per_call);
        //     $publisher_balance = $publisher->balance;
        //     $payout = $payout_per_call;
        // }

        // $profit = $revenue - $payout;
        // $campaignReporting = (new static)->create([
        //     'campaign_id' => $campaignId,
        //     'call_date' => $call_date,
        //     'profit' => $profit,
        //     'campaign' =>  $campaign->name,
        //     'publisher' => $publisher->user_uuid,
        //     'caller_id' => $caller_id,
        //     'dialed' => '03216910563',
        //     'time_to_call' => $time_to_call,
        //     'time_to_connect' => $time_to_connect,
        //     'revenue' => $revenue,
        //     'payout' => $payout,
        //     'duration' => $duration,
        //     'recording' => $recording,
        //     'client_uuid' => $client_uuid,
        //     'hangup_reason' => 'Reason',
        //     'longitude' => $longitude,
        //     'latitude' => $latitude,

        // ]);

        // return 'success';
    }
    private function storeRecord()
    {
        $detail = TwillioNumber::getNumberDetails(request('ForwardedFrom'));
        $data['uuid'] = generateUuid();
        $data['parent_call_sid'] = request('ParentCallSid');
        $data['call_sid'] = request('CallSid');


        $data['call_date'] =  getStandarDateTime(request('Timestamp'));
        $data['client_id'] = $detail['client_id'];
        $data['campaign_id'] = $detail['campaign_id'];
        $data['publisher_id'] = $detail['publisher_id'];
        // caller id
        $data['caller_id'] = request('From');
        // dialed
        $data['dialed'] = request('ForwardedFrom');
        $data['initiated_at'] = getStandarDateTime(request('Timestamp'));
        $data['call_status'] = request('CallStatus');
        $data['caller_country'] = request('CallerCountry');

        CampaignReporting::create($data);
    }
    private function updateRecord()
    {
        $record = CampaignReporting::where('parent_call_sid', request('ParentCallSid'))->first();
        $data['call_status'] = request('CallStatus');

        if (request('CallStatus') == 'ringing') {
            $data['ringing_at'] = getStandarDateTime(request('Timestamp'));
        }
        if (request('CallStatus') == 'answered' || request('CallStatus') == 'in-progress') {
            $data['answered_at'] = getStandarDateTime(request('Timestamp'));
        }


        if (request('CallStatus') == 'completed') {
            $data['target_id'] = TargetListing::where('destination', request('To'))->value('id');

            // $data['time_to_call'] = '';
            $data['duplicate'] = CampaignReporting::whereBetween('created_at', [now()->subMinutes(1440), now()])
                ->where('caller_id', request('Caller'))->where('dialed', request('ForwardedFrom'))->exists();
            $this->AddTransaction($record);
            // $data['hangup'] = '';
            $startDateTime = Carbon::parse($record->initiated_at);
            $endDateTime = Carbon::parse($record->answered_at);
            $data['time_to_connect'] = $startDateTime->diff($endDateTime)->format('%H:%I:%S');
            $data['revenue'] = $this->revenue;
            $data['payout'] = $this->payout;
            $data['profit'] = $this->profit;
            $data['duration'] = request('CallDuration');
            $data['recording'] = request('RecordingUrl');
            $data['completed_at'] = getStandarDateTime(request('Timestamp'));
        }

        $record->update($data);
    }
    private function AddTransaction($reportingRecord)
    {
        $campaign = Campaign::find($reportingRecord->campaign_id);
        $client = User::find($reportingRecord->client_id);
        $publisher = User::find($reportingRecord->publisher_id);




        $client_cost_per_call = $campaign->cost_per_call;
        $client_per_call_duration = $campaign->client_per_call_duration;
        $callDuration = request('CallDuration');
        // calculate revenue
        if ($callDuration >= $client_per_call_duration) {
            $client->withdraw($client_cost_per_call, ['description' => 'cost for call', 'campaign_id' => $reportingRecord->campaign_id]);
            $client_balance = $client->balance;
            $this->revenue = $client_cost_per_call;
        }

        // calculate payout
        $payout_per_call = $campaign->payout_per_call;
        $publisher_per_call_duration = $campaign->publisher_per_call_duration;
        if ($callDuration >= $publisher_per_call_duration) {

            $publisher->deposit($payout_per_call, ['description' => 'earn from call', 'campaign_id' => $reportingRecord->campaign_id]);
            $publisher_balance = $publisher->balance;
            $this->payout = $payout_per_call;
        }

        $this->profit = $this->revenue - $this->payout;
    }
}
