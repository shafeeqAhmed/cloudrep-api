<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class PreRegistration extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'phone_no',
        'email',
        'business_scale_type'
    ];


    // public function dropdown() {
    //     return $this->hasOne(LmsQuestion::class,'quiz_id','id');
    // }

    public static function updateRecord($col, $val, $data)
    {
        return self::where($col, $val)->update($data);
    }
    public static function getRecord($col, $val)
    {
        return self::where($col, $val)->first();
    }
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid)
    {
        return PreRegistration::where('uuid', $uuid)->value('id');
    }
    public function businessCategory() {
        return $this->belongsTo(BussinesCategory::class,'business_category','id');
    }
}
