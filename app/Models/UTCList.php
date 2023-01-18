<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\support\Str;

class UTCList extends Model
{
    use HasFactory;

    protected $table = 'utc_lists';
    protected $fillable = [
        'name',
    ];

    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid)
    {
        return UTCList::whereUuid($uuid)->value('id');
    }

    public static function getUtcList(Request $request) {
            $utcList = UTCList::pluck('name');
            return $utcList;
    }
}
