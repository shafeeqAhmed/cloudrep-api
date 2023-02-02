<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Campaign;
use App\Models\User;
use App\Models\TwillioNumber;
use App\Models\TargetListing;

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

    public static function getUserServices($userId)
    {
        return  self::join('services', 'services.id', '=', 'client_services.service_id')
            ->where('client_services.user_id', $userId)
            ->where('client_services.is_active', '=', '1')
            ->pluck('services.service_uuid');
    }


    public static function saveCustomCampaignRates($request)
    {

        $campaign_id = Campaign::getIdByUuid($request->campaign_uuid);
        $client_id = User::getIdByUuid($request->client_uuid) ?? 0;
        $target_id = TargetListing::getIdByUuid($request->target_uuid) ?? 0;

        $rates = CampaignRates::updateOrCreate([
            'campaign_id' => $campaign_id,
            'target_id ' => $target_id
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
}
