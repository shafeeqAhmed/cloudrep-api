<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class LmsQuestion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'is_active',
        'quiz_id',
        'lesson_id'
    ];

    public function quiz(){
        return $this->belongsTo(LmsQuize::class);
    }

    public function questionOptions() {
        return $this->hasMany(LmsQuestionOption::class,'question_id','id');
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
            $model->lms_question_uuid = Str::uuid()->toString();
        });
    }

    public static function getIdByUuid($uuid) {
        return LmsQuestion::where('lms_question_uuid',$uuid)->value('id');
    }
}
