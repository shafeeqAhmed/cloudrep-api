<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class LmsQuize extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'percentage',
        'is_active',
        'lesson_id',
        'duration'
    ];
    protected $casts = [
        'duration' => 'date:mm:ss'
    ];

    // public function getDurationAttribute($date)
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('hh:mm');
    // }

    public function questions() {
        return $this->hasMany(LmsQuestion::class,'quiz_id','id');
    }
    // public function lesson() {
    //     return $this->hasOne(LmsLesson::class,'id','lesson_id');
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
            $model->lms_quiz_uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid) {
        return LmsQuize::where('lms_quiz_uuid',$uuid)->value('id');
    }

}
