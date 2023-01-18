<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class CampaignService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'service_id',
    ];

    public function setIsActiveAttribute($value)
    {
        if ($value == true || $value == 'true') {
            $this->attributes['is_active'] = 1;
        }
        if ($value == false || $value == 'false') {
            $this->attributes['is_active'] = 0;
        }
    }

    public function getIsActiveAttribute($value)
    {
        if ($value == 1) {
            return true;
        }
        if ($value == 0) {
            return false;
        }
    }
    public static function getUserService($userId)
    {
        return self::join('services', 'services.id', '=', 'campaign_services.service_id')
            ->where('campaign_services.user_id', $userId)
            ->where('campaign_services.is_active', '=', '1')
            ->pluck('services.service_uuid');
    }
    public static function updateRecord($col, $val, $data)
    {
        return self::where($col, $val)->update($data);
    }
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid)
    {
        return self::where('uuid', $uuid)->value('id');
    }
}
