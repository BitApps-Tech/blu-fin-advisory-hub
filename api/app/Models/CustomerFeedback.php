<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    use HasFactory;

    protected $table = 'customer_feedback';

    protected $fillable = [
        'phone',
        'visit_date',
        'customer_order',
        'food_taste',
        'food_presentation',
        'food_freshness',
        'food_portion_size',
        'service_friendliness',
        'service_speed',
        'service_accuracy',
        'service_attentiveness',
        'environment_cleanliness',
        'environment_ambiance',
        'environment_comfort',
        'comments',
        'is_read',
        'read_at',
        'read_by',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function reader()
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    public function markAsRead(?int $userId = null): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
            'read_by' => $userId ?? auth()->id(),
        ]);
    }

    public function averageRating(): float
    {
        $scores = [
            $this->food_taste,
            $this->food_presentation,
            $this->food_freshness,
            $this->food_portion_size,
            $this->service_friendliness,
            $this->service_speed,
            $this->service_accuracy,
            $this->service_attentiveness,
            $this->environment_cleanliness,
            $this->environment_ambiance,
            $this->environment_comfort,
        ];

        return round(array_sum($scores) / count($scores), 2);
    }

    public function foodQualityAverage(): float
    {
        return round(
            ($this->food_taste + $this->food_presentation + $this->food_freshness + $this->food_portion_size) / 4,
            2
        );
    }

    public function serviceAverage(): float
    {
        return round(
            ($this->service_friendliness + $this->service_speed + $this->service_accuracy + $this->service_attentiveness) / 4,
            2
        );
    }

    public function environmentAverage(): float
    {
        return round(
            ($this->environment_cleanliness + $this->environment_ambiance + $this->environment_comfort) / 3,
            2
        );
    }
}
