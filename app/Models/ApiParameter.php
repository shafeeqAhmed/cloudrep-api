<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class ApiParameter extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'api_endpoint_id',
        'name',
        'data_type',
        'description',
        'example_data'
    ];

    // public function apiEndpoint()
    // {
    //     return $this->belongsTo(ApiEndpoint::class, 'api_endpoint_id', 'id');
    // }

    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public static function getIdByUuid($uuid) {
        return ApiParameter::whereUuid($uuid)->value('id');
    }
}
