<?php

namespace Modules\Payments\Models;

use Modules\Orders\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Payments\Database\Factories\PaymentFactory;
// use Modules\Payments\Database\Factories\PaymentFactory;

class Payment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'status',
        'method',
        'payment_url',
        'transaction_details',
        'payment_token',
        'gatway',
        'amount',
    ];

    protected $casts = [
        'transaction_details' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    protected static function newFactory(): PaymentFactory
    {
        return PaymentFactory::new();
    }
}
