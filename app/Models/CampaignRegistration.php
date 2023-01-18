<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class CampaignRegistration extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'username','email','title','profile_picture','address',
        'status','working_state','working_hours','open_time','close_time','user_id'
    ];

    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public static function getIdByUuid($uuid) {
        return CampaignRegistration::where('id',$uuid)->value('id');
    }
}
