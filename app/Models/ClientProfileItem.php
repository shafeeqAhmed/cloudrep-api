<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class ClientProfileItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'bussines_name',
        'bussines_address',
        'bussines_phone_no',
        'google_my_bussines',
        'country',
        'state',
        'city',
        'zipcode',
        'crunchbase',
        'linkedin',
        'twitter',
        'step'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public static function updateRecord($col,$val, $data){
        self::where($col,$val)->update($data);
    }
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
