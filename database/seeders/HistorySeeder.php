<?php

namespace Database\Seeders;

use App\Models\History;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=HistorySeeder
     *
     * @return void
     */
    public function run()
    {
        //DB::table('histories')->truncate();

        $faker = \Faker\Factory::create();

        for ($user_id = 1; $user_id < 10000; $user_id++) {

            $type = $faker->randomElement(['visit' , 'play']);

            History::insert([
                'major_id' => 1,
                'minor_id' => ($type == 'visit') ? 0 : rand(1 , 6),
                'user_vip' => $faker->randomElement(['1' , '-1']),
                'user_id' => $user_id,
                'type' => $type,
                'class' => $faker->randomElement(['video' , 'comic']),
                'created_at' =>date('Y-m-d H:i:s'),
            ]);
        }

    }
}
