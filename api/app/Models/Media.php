<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'disk',
        'path',
        'mime',
        'size',
        'width',
        'height',
        'title',
        'description',
        'alt',
        'created_by',
    ];

    protected $casts = [
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    /**
     * Get the user that created the media.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the full URL for the media.
     */
    public function getUrlAttribute()
    {
        if (!$this->path) {
            return null;
        }

        return Storage::disk($this->disk ?: 'public')->url($this->path);
    }

    /**
     * Get the thumbnail URL if it exists.
     */
    public function getThumbUrlAttribute()
    {
        if (!$this->path) {
            return $this->url;
        }
        
        $pathInfo = pathinfo($this->path);
        
        // Check if all required path components exist
        if (!isset($pathInfo['dirname']) || !isset($pathInfo['filename']) || !isset($pathInfo['extension'])) {
            return $this->url;
        }
        
        $thumbPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
        
        if (file_exists(storage_path('app/public/' . $thumbPath))) {
            return Storage::disk($this->disk ?: 'public')->url($thumbPath);
        }
        
        return $this->url;
    }
}

