<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;
use PragmaRX\Google2FA\Support\Constants;

class LmsCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'parent_id'
    ];

    public function course()
    {
        return $this->belongsTo(LmsCourse::class, 'lms_course_categories');
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->lms_category_uuid = Str::uuid()->toString();
        });
    }

    public static function getIdByUuid($uuid)
    {
        return LmsCategory::where('lms_category_uuid', $uuid)->value('id');
    }


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
}
