<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'presenter',
        'description',
        'activity',
        'image_id',
        'location',
        'event_date',
        'status',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically update status when model is retrieved
        static::retrieved(function ($event) {
            $event->updateStatusBasedOnDate();
        });
    }

    /**
     * Update event status based on event_date and current time.
     * Also sets is_active to false if event has passed.
     */
    public function updateStatusBasedOnDate()
    {
        $now = Carbon::now();
        $eventDate = Carbon::parse($this->event_date);
        
        // Don't update if event is cancelled
        if ($this->status === 'cancelled') {
            return;
        }
        
        // If event date has passed (event_date is in the past)
        if ($now->isAfter($eventDate)) {
            // Event has passed - set to completed and inactive
            if ($this->status !== 'completed') {
                $this->status = 'completed';
            }
            if ($this->is_active) {
                $this->is_active = false;
            }
        } 
        // If event is happening today (same day as event_date)
        elseif ($now->isSameDay($eventDate)) {
            // Event is ongoing today
            if ($this->status === 'upcoming' || $this->status === 'completed') {
                $this->status = 'ongoing';
            }
            // Ensure it's active if it's ongoing
            if (!$this->is_active) {
                $this->is_active = true;
            }
        }
        // If event is in the future
        else {
            // Event is upcoming
            if ($this->status === 'completed' || $this->status === 'ongoing') {
                $this->status = 'upcoming';
            }
            // Ensure it's active if it's upcoming
            if (!$this->is_active) {
                $this->is_active = true;
            }
        }
        
        // Save changes if any were made
        if ($this->isDirty()) {
            $this->saveQuietly(); // Use saveQuietly to avoid triggering events again
        }
    }

    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
}
