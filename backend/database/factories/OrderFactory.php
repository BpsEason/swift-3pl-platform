<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'platform_order_id' => 'P' . $this->faker->unique()->randomNumber(8),
            'status' => $this->faker->randomElement(['pending', 'processing', 'picking']),
            'shipping_address' => $this->faker->address,
            'tracking_number' => null,
        ];
    }
}
