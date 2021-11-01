<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// php artisan db:seed --class=UserSeeder
class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()
            ->times(20)
            ->create();
    }
}
