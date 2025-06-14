<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Database\Seeders\UsersDatabaseSeeder;
use Modules\Orders\Database\Seeders\OrdersDatabaseSeeder;
use Modules\Payments\Database\Seeders\PaymentsDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersDatabaseSeeder::class);
        $this->call(OrdersDatabaseSeeder::class);
        $this->call(PaymentsDatabaseSeeder::class);
    }
}
