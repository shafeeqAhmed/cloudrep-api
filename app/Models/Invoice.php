<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'invoice_number',
        'date',
        'terms',
        'due_date',
        'description',
        'rate',
        'quantity',
        'amount',
        'tax',
        'discount',
        'additional_detail',
        'note',
        'user_id',
        'campaign_id',
        'order_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function order(){
        return $this->belongsTo(CourseOrder::class);
    }


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
        static::creating(function ($model) {
            $count = Invoice::count();
            $count++;
            $model->invoice_number = 'INV' . str_pad($count, 4, '0', STR_PAD_LEFT);
            $model->due_date = now();
        });
    }

    public static function getByUuid($uuid)
    {
        return Invoice::whereUuid($uuid)->value('id');
    }
}
