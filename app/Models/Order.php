<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['user_id', 'order_number', 'payment_method', 'payment_status', 'upi_reference', 'razorpay_order_id', 'razorpay_payment_id', 'order_status', 'total_amount', 'customer_name', 'customer_email', 'customer_phone', 'city', 'area', 'nearby_landmark', 'address'];

    protected function casts(): array
    {
        return ['total_amount' => 'decimal:2'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
