<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\support\Str;

class CustomerInformation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'customer_informations';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'customer_type',
        'customer_notes'
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
        return CustomerInformation::where('uuid', $uuid)->value('id');
    }

    public function productOrders(){
        return $this->hasMany(ProductOrder::class,'customer_id','id');
    }

    public static function getCustomerInfo(Request $request)
    {
        $customerInfo = CustomerInformation::when($request->q, function ($query, $q) {
            return $query->where('email', 'LIKE', "%{$q}%")
            ->orWhere('phone', 'LIKE', "%{$q}%");
        })
        ->when($request->soryBy, function ($query, $sortBy) {
            return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
        })
        ->when($request->page, function ($query, $page) {
            return $query->offset($page - 1);
        })
        ->orderBy('id', 'DESC')->paginate($request->perPage);

        return $customerInfo;
    }

    public static function getCustomerInfoByUuid($col, $val)
    {
        $record = CustomerInformation::where($col, $val)->first();
        return $record;
    }

    public static function storeCustomerInfo(Request $request) {
        $data = $request->all();
        return self::create($data);
    }
}
