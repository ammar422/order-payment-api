<?php

namespace Modules\Users\Database\Seeders;

use Modules\Users\Models\User;
use Illuminate\Database\Seeder;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
    }
}
