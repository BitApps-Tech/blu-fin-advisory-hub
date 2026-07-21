<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Specialty extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'image_id',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($specialty) {
            if (empty($specialty->slug)) {
                $specialty->slug = Str::slug($specialty->title);
            }
        });

        static::updating(function ($specialty) {
            if ($specialty->isDirty('title') && empty($specialty->slug)) {
                $specialty->slug = Str::slug($specialty->title);
            }
        });
    }

    /**
     * Get the image for the specialty.
     */
    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
}

