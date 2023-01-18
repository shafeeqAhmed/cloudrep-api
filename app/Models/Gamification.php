<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

use function PHPSTORM_META\map;

class Gamification extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'point',
        'reason',
        'isTransfer'
    ];
    
    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public function users() {
        return $this->hasMany(User::class, 'user_id', 'id');
    }


}
