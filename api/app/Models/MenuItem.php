<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'is_special',
        'is_active',
        'image_id',
        'order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_special' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->name);
            }
        });

        static::updating(function ($item) {
            if ($item->isDirty('name') && empty($item->slug)) {
                $item->slug = Str::slug($item->name);
            }
        });
    }

    /**
     * Get the category that owns the menu item.
     */
    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    /**
     * Get the image for the menu item.
     */
    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    /**
     * Get the order items for this menu item.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'menu_item_id');
    }
}

