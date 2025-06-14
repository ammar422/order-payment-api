<?php

namespace Modules\Payments\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Orders\Models\Order;
use Modules\Payments\Models\Payment;

class PaymentsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $confirmedOrders = Order::where('status', 'confirmed')->get();

        foreach ($confirmedOrders as $order) {
            Payment::factory()->create([
                'order_id' => $order->id,
                'gatway' => 'paypal'
            ]);
        }
    }
}
