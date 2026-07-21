<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryItem extends Model
{
    use HasFactory, SoftDeletes;

    public const CATEGORY_EVENTS = 'events';
    public const CATEGORY_AGRO = 'agro';

    public const CATEGORIES = [
        self::CATEGORY_EVENTS,
        self::CATEGORY_AGRO,
    ];

    protected $fillable = [
        'title',
        'caption',
        'category',
        'image_id',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the image for the gallery item.
     */
    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
}

