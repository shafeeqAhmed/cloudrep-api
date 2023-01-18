<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class ApiEndpoint extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'api_list_id',
        'type',
        'url',
        'title',
        'description'
    ];

    // public function apiList()
    // {
    //     return $this->belongsTo(ApiList::class, 'api_list_id', 'id');
    // }

    public function apiParameter()
    {
        return $this->hasMany(ApiParameter::class, 'api_endpoint_id','id');
    }
    public function apiResponse()
    {
        return $this->hasMany(ApiResponse::class, 'api_endpoint_id','id');
    }

    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public static function getIdByUuid($uuid) {
        return ApiEndpoint::whereUuid($uuid)->value('id');
    }
}
