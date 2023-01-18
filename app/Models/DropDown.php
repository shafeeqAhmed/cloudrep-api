<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;


class DropDown extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'dropdowns';

    protected $fillable = [
        'label',
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

    public static function getIdByUuid($uuid) {
        return DropDown::whereUuid($uuid)->value('id');
    }
}
