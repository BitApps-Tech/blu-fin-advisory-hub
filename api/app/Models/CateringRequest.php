<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'event_type',
        'event_date',
        'event_location',
        'guest_count',
        'menu_preferences',
        'special_requirements',
        'estimated_budget',
        'quoted_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'estimated_budget' => 'decimal:2',
        'quoted_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->request_code)) {
                $model->request_code = 'CAT-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
