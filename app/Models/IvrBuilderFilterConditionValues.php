<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\support\Str;
use Illuminate\Http\Request;

class IvrBuilderFilterConditionValues extends Model
{
    use HasFactory;
    protected $fillable = ['tag_operator_value', 'filter_condition_id'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = str::uuid()->toString();
        });
    }
}
