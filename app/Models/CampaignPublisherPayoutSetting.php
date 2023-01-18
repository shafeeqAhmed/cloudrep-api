<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class CampaignPublisherPayoutSetting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'payout_type', 'payout_on', 'length', 'payout_amount', 'revshare_payout_limits', 'min', 'max', 'duplicate_payouts', 'days',
        'hours', 'payout_hours', 'open_time', 'close_time', 'start_break_time', 'break_duration', 'time_zone', 'limit_payout',
        'global_cap', 'global_payout_cap', 'monthly_cap', 'monthly_payout_cap', 'daily_cap',
        'daily_payout_cap', 'hourly_cap', 'hourly_payout_cap', 'concurrency_cap', 'user_id', 'campaign_id'
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
        return CampaignPublisherPayoutSetting::whereUuid($uuid)->value('id');
    }
}
