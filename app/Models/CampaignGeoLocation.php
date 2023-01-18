<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CampaignGeoLocation extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $fillable = [
        'country', 'state', 'address', 'city_town', 'zipcode', 'long', 'lat', 'campaign_id', 'address_type'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public static function getIdByUuid($uuid)
    {
        return CampaignGeoLocation::where('uuid', $uuid)->value('id');
    }
}
