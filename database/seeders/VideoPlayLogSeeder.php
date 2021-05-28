<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VideoPlayLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoPlayLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=VideoPlayLogSeeder
     *
     * @return void
     */
    public function run()
    {
        DB::table('video_play_logs')->truncate();

        $users = User::select('id')->limit(1000)->get()->pluck('id');

        $faker = \Faker\Factory::create();

        $insert = [];

        foreach ($users as $user_id) {
            $insert[] = [
                'video_id' => rand(1 , 4),
                'user_id' => $user_id,
                'vip' => $faker->randomElement(['1' , '-1']),
            ];
        }

        VideoPlayLog::create($insert);
    }
}
