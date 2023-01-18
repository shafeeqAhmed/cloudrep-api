<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\support\Str;

class ProductOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'category',
        'product',
        'product_specification',
        'brand',
        'capacity',
        'color',
        'product_price',
        'delivery',
        'delivery_date',
        'delivery_start_time',
        'delivery_end_time',
        'pickup',
        'pickup_date',
        'pickup_start_time',
        'pickup_end_time',
        'order_notes'
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
        return ProductOrder::where('uuid', $uuid)->value('id');
    }

    public function shipping(){
        return $this->hasOne(ShippingAddress::class,'order_id','id');
    }

    public function payment(){
        return $this->hasOne(Payment::class,'order_id','id');
    }

    public static function storeProductOrder(Request $request) {
        $data = $request->all();
        $data['customer_id'] =  CustomerInformation::getIdByUuid(request('customer_uuid'));
        $data['product_specification'] = request('product_specification') ?  request()->boolean('product_specification') : false;
        $data['delivery'] = request('delivery') ?  request()->boolean('delivery') : false;
        $data['pickup'] = request('pickup') ?  request()->boolean('pickup') : false;
        return self::create($data);
    }
}
