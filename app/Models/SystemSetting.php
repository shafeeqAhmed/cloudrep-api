<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\support\Str;

class SystemSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'value'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->setting_uuid = Str::uuid()->toString();
        });
    }
   
}
