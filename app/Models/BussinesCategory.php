<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class BussinesCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public function verticals() {
        return $this->hasMany(CompanyVertical::class,'bussines_category_id','id');
    }

    public static function getIdByUuid($uuid) {
        return BussinesCategory::whereUuid($uuid)->value('id');
    }
}
