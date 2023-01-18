<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class LmsLesson extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'course_id'
    ];

    public function course() {
        return $this->belongsTo(LmsCourse::class);
    }

    public function quizes() {
        return $this->hasMany(LmsQuize::class,'lesson_id','id');
    }

    public function videos(){
        return $this->hasMany(LmsLessonVideo::class,'lesson_id','id');    
    }

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
            $model->lms_lesson_uuid = Str::uuid()->toString();
        });
    }

    public static function getIdByUuid($uuid) {
        return LmsLesson::where('lms_lesson_uuid',$uuid)->value('id');
    }
}
