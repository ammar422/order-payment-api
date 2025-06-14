<?php

namespace Modules\Payments\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payments\Database\Factories\PaymentFactory;
// use Modules\Payments\Database\Factories\PaymentFactory;

class Payment extends Model
{
    use HasFactory , HasUuids , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): PaymentFactory
    {
        return PaymentFactory::new();
    }
}
