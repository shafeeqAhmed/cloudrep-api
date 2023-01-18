<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class CampaignVertical extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid) {
        return CampaignVertical::where('uuid',$uuid)->value('id');
    }
}
