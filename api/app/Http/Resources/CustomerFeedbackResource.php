<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerFeedbackResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'visit_date' => $this->visit_date?->format('Y-m-d'),
            'customer_order' => $this->customer_order,
            'food_taste' => $this->food_taste,
            'food_presentation' => $this->food_presentation,
            'food_freshness' => $this->food_freshness,
            'food_portion_size' => $this->food_portion_size,
            'service_friendliness' => $this->service_friendliness,
            'service_speed' => $this->service_speed,
            'service_accuracy' => $this->service_accuracy,
            'service_attentiveness' => $this->service_attentiveness,
            'environment_cleanliness' => $this->environment_cleanliness,
            'environment_ambiance' => $this->environment_ambiance,
            'environment_comfort' => $this->environment_comfort,
            'comments' => $this->comments,
            'average_rating' => $this->averageRating(),
            'food_average' => $this->foodQualityAverage(),
            'service_average' => $this->serviceAverage(),
            'environment_average' => $this->environmentAverage(),
            'is_read' => $this->is_read,
            'read_at' => $this->read_at?->toIso8601String(),
            'reader' => $this->whenLoaded('reader', function () {
                return [
                    'id' => $this->reader->id,
                    'name' => $this->reader->name,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
