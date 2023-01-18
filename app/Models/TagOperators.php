<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\support\Str;


class TagOperators extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'value', 'tag_id '
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
        return TagOperators::where('uuid', $uuid)->value('id');
    }
}
