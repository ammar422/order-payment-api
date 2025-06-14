<?php

namespace Modules\Orders\Database\Factories;

use Modules\Users\Models\User;
use Modules\Orders\Models\Order;
use Modules\Orders\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Orders\Models\Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'status' => 'confirmed',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $items = OrderItem::factory()->count(3)->make();
            $total = 0;

            foreach ($items as $item) {
                $item->order_id = $order->id;
                $item->save();
                $total += $item->price * $item->quantity;
            }

            $order->update(['total_price' => $total]);
        });
    }
}
