<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_name',
        'phone',
        'email',
        'order_type',
        'status',
        'scheduled_at',
        'note',
        'total',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'scheduled_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->code)) {
                $order->code = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Get the order items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Calculate and update the total.
     */
    public function calculateTotal()
    {
        $this->total = $this->items()->sum('total');
        $this->save();
    }
}

