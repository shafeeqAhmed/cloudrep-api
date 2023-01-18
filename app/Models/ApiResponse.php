<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class ApiResponse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'api_endpoint_id',
        'code',
        'description',
        'example_value'
    ];

    protected $casts = [
        'example_value' => 'array'
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
        return ApiResponse::whereUuid($uuid)->value('id');
    }
}
