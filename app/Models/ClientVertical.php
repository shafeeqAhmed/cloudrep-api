<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyVertical;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class ClientVertical extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'is_active'
    ];
    public static function getIdByUuid($uuid) {
        return CompanyVertical::where('uuid',$uuid)->value('id');

    }
    public static function updateRecord($col,$val,$data) {
        return self::where($col,$val)->update($data);

    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
