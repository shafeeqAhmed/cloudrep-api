<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class CampaignAgentPayoutSetting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'per_call_earning', 'commission', 'commission_type', 'payout_amount', 'revshare_payout_limits', 'min', 'max', 'duplicate_payouts', 'days',
        'hours', 'payout_hours', 'open_time', 'close_time', 'start_break_time', 'break_duration', 'time_zone', 'limit_payout',
        'global_cap', 'golbal_payout_cap', 'monthly_cap', 'monthly_payout_cap', 'daily_cap',
        'daily_payout_cap', 'hourly_cap', 'hourly_payout_cap', 'concurrency_cap', 'tips', 'bounties_condition', 'bounties_operator', 'bounties_value', 'bonus_type', 'bonus_value', 'user_id', 'campaign_id'
    ];

    protected $casts = [
        'revshare_payout_limits' => 'boolean'
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
        return CampaignAgentPayoutSetting::whereUuid($uuid)->value('id');
    }
}
