<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\support\Str;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'card_number',
        'expiry_date',
        'cvv',
        'card_notes',
        'is_saved',
        'order_id'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid)
    {
        return Payment::where('uuid', $uuid)->value('id');
    }

    public static function storePayment(Request $request) {
        $data = $request->all();
        $data['is_saved'] = $request->has('is_saved') ? $request->boolean('is_saved') : false;
        $data['order_id'] =  ProductOrder::getIdByUuid(request('order_uuid'));
        return self::create($data);
    }
}
