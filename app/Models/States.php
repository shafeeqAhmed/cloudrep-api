<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
    ];

    public static function getCodeByName($name)
    {
        return self::where('name', $name)->value('code');
    }
}
