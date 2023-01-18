<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class LmsResult extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $fillable = [
        'quiz_id',
        'question_id',
        'option_id',
        'user_id',
        'time_spend'
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function quiz() {
        return $this->hasOne(LmsQuize::class,'id','quiz_id');
    }

    public function questions() {
        return $this->hasMany(LmsQuestion::class,'id','question_id');
    }

    public function questionOptions() {
        return $this->hasMany(LmsQuestionOption::class,'id','option_id');
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid) {
        return LmsQuize::where('lms_quiz_uuid',$uuid)->value('id');
    }
}
