<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class LmsQuestionOption extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'is_active',
        'is_true',
        'question_id'
    ];

    public function question(){
        return $this->belongsTo(LmsQuestion::class);
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

    public function setIsTrueAttribute($value)
    {
        if($value == true || $value== 'true') {
            $this->attributes['is_true'] = 1;
        } if($value == false || $value== 'false') {
            $this->attributes['is_true'] = 0;
        }
    }

    public function getIsTrueAttribute($value)
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
            $model->lms_question_options_uuid = Str::uuid()->toString();
        });
    }

    public static function getIdByUuid($uuid) {
        return LmsQuestionOption::where('lms_question_options_uuid',$uuid)->value('id');
    }
}
