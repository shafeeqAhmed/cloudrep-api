<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'icon',
        'image',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->service_uuid = Str::uuid()->toString();
        });
    }


    public static function getIdByUuid($uuid)
    {
        return Service::where('service_uuid', $uuid)->value('id');
    }
}
