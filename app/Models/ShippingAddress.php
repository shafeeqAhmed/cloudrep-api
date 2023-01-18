<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\support\Str;

class ShippingAddress extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'order_id',
        'shipping_address',
        'first_name',
        'last_name',
        'shipping_notes',
        'is_saved'
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
        return ShippingAddress::where('uuid', $uuid)->value('id');
    }

    public static function storeShippingAddress(Request $request) {
        $data = $request->all();
        $data['is_saved'] = $request->has('is_saved') ? $request->boolean('is_saved') : false;
        $data['customer_id'] =  CustomerInformation::getIdByUuid(request('customer_uuid'));
        $data['order_id'] =  ProductOrder::getIdByUuid(request('order_uuid'));
        return self::create($data);
    }
}
