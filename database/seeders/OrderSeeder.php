<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

// php artisan db:seed --class=OrderSeeder
class OrderSeeder extends Seeder
{
    public function run()
    {
        Order::factory()
            ->times(20)
            ->create();
    }
}
