<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class ClientService extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'is_active'
    ];
    // public function services() {
    //     return $this->belongsToMany(Service::class, 'client_services','user_id','service_id');
    // }

    public function setIsActiveAttribute($value)
    {
        if ($value == true || $value == 'true') {
            $this->attributes['is_active'] = 1;
        }
        if ($value == false || $value == 'false') {
            $this->attributes['is_active'] = 0;
        }
    }

    public function getIsActiveAttribute($value)
    {
        if ($value == 1) {
            return true;
        }
        if ($value == 0) {
            return false;
        }
    }
    public static function getUserServices($userId)
    {
        return  self::join('services', 'services.id', '=', 'client_services.service_id')
            ->where('client_services.user_id', $userId)
            ->where('client_services.is_active', '=', '1')
            ->pluck('services.service_uuid');
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
