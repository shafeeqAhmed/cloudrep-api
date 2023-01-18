<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

use function PHPSTORM_META\map;

class PromoCode extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title',
        'type',
        'amount',
        'frequency',
        'is_applied',
        'start_date',
        'end_date'
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
        return PromoCode::where('uuid', $uuid)->value('id');
    }
}
