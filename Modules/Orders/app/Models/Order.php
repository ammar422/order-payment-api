<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Orders\Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Payments\Models\Payment;

// use Modules\Orders\Database\Factories\OrderFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'status',
        'total_price'
    ];


    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }
}
