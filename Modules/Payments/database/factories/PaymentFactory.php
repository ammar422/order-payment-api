<?php

namespace Modules\Payments\Database\Factories;

use Illuminate\Support\Str;
use Modules\Orders\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Payments\Models\Payment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->first()->id,
            'gatway' => fake()->randomElement(['stripe', 'paypal']),
            'status' => 'pending',
            'payment_url' => fake()->url(),
            'transaction_details' => [
                'mock_transaction_id' => Str::uuid(),
            ],
        ];
    }
}
