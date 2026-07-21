<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'code' => 'ORD-' . strtoupper(Str::random(8)),
            'customer_name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'order_type' => $this->faker->randomElement(['pickup', 'delivery', 'dinein']),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'preparing', 'ready', 'completed']),
            'scheduled_at' => $this->faker->optional()->dateTimeBetween('now', '+7 days'),
            'note' => $this->faker->optional()->sentence(),
            'total' => $this->faker->randomFloat(2, 10, 200),
        ];
    }
}











