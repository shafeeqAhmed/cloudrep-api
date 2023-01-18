<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TimeZone extends Model
{
    use HasFactory;
    protected $table = 'time_zones';
    protected $fillable = [
        'country_code', 'timezone', 'gmt_offset', 'dst_offset', 'raw_offset',
    ];

    public static function getTimeZone(Request $request)
    {
        $utcList = self::get(['timezone', 'dst_offset']);
        return $utcList;
    }
}
