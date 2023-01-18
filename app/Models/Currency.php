<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Currency extends Model
{
    use HasFactory;
    protected $table = 'currencies';

    protected $fillable = [
        'currency_code',
        'currency_name',
    ];
    public static function getCurrencies(Request $request)
    {
        $currency = self::selectRaw('currency_code,CONCAT(currency_code," (",currency_name,")") AS label')->get();
        return $currency;
    }
}
