<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\support\Str;

class Tags extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'type'
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
        return self::where('uuid', $uuid)->value('id');
    }
}
