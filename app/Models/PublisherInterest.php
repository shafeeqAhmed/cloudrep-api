<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class PublisherInterest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'is_active',
        'user_id',
        'dropdown_id',
    ];

    public function dropdown(){
        return $this->belongsTo(DropDown::class);
    }
    // public function dropdowns() {
    //     return $this->belongsToMany(DropDown::class, 'publisher_interests', 'dropdown_id', 'id');
    // }

    public function setIsActiveAttribute($value)
    {
        if($value == true || $value== 'true') {
            $this->attributes['is_active'] = 1;
        } if($value == false || $value== 'false') { 
            $this->attributes['is_active'] = 0;
        }
    }

    public function getIsActiveAttribute($value)
    {
        if($value == 1) {
            return true;
        } if($value == 0) {
            return false;
        }
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid) {
        return PublisherInterest::whereUuid($uuid)->value('id');
    }
}
