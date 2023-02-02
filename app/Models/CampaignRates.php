<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Campaign;
use App\Models\User;
use App\Models\TwillioNumber;
use App\Models\TargetListing;
use Illuminate\Support\Str;

class CampaignRates extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'target_id', 'number_id', 'currency', 'type', 'cost_per_call', 'cost_per_call_duration', 'payout_per_call', 'payout_per_call_duration', 'campaign_id', 'publisher_id', 'client_id'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public static function getByUuid($uuid)
    {
        return self::whereUuid($uuid)->value('id');
    }

    public static function getCampignTargetRates($request)
    {
        $campaign_id = Campaign::getIdByUuid($request->campaign_uuid);
        $target_id = TargetListing::getIdByUuid($request->target_uuid);
        return  self::where([['campaign_id', $campaign_id], ['target_id', $target_id]])->first();
    }

    public static function getCampignPublisherPayoutRates($request)
    {
        $campaign_id = Campaign::getIdByUuid($request->campaign_uuid);
        $publisher_id = User::getIdByUuid($request->publisher_uuid);
        return  self::where([['campaign_id', $campaign_id], ['publisher_id', $publisher_id]])->first();
    }


    public static function saveCustomCampaignRates($request)
    {

        $campaign_id = Campaign::getIdByUuid($request->campaign_uuid);
        $client_id = User::getIdByUuid($request->client_uuid) ?? 0;
        $target_id = TargetListing::getIdByUuid($request->target_uuid) ?? 0;

        // return response()->json($target_id);

        $rates = CampaignRates::updateOrCreate([
            'campaign_id' => $campaign_id,
            'target_id' => $target_id
        ], [
            'target_id' => $target_id,
            'campaign_id' => $campaign_id,
            'type' => $request->type,
            'cost_per_call' => $request->cost_per_call,
            'cost_per_call_duration' => $request->cost_per_call_duration,
            'client_id' => $client_id
        ]);

        return $rates;
    }

    public static function saveCustomCampaignPublisherPayoutRates($request)
    {

        $campaign_id = Campaign::getIdByUuid($request->campaign_uuid);
        $publisher_id = User::getIdByUuid($request->publisher_uuid) ?? 0;
        $client_id = User::getIdByUuid($request->client_uuid) ?? 0;
        $number_id = TwillioNumber::where('number', $request->number)->first()->id;

        $rates = CampaignRates::updateOrCreate([
            'campaign_id' => $campaign_id,
            'publisher_id' => $publisher_id
        ], [

            'campaign_id' => $campaign_id,
            'number_id' => $number_id,
            'type' => $request->type,
            'payout_per_call' => $request->payout_per_call,
            'payout_per_call_duration' => $request->payout_per_call_duration,
            'publisher_id' => $publisher_id,
            'client_id' => $client_id
        ]);

        return $rates;
    }
}
