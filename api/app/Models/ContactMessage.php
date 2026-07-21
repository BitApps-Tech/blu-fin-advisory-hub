<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'read_at',
        'read_by',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user who read the message.
     */
    public function reader()
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead($userId = null)
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
            'read_by' => $userId ?? auth()->id(),
        ]);
    }
}

