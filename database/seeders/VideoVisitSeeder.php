<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VideoVisit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=VideoVisitSeeder
     *
     * @return void
     */
    public function run()
    {
        DB::table('video_visits')->truncate();

        $users = User::select('id')->limit(1000)->get()->pluck('id');

        $insert = [];
        foreach ($users as $user_id) {
            $insert[] = [
                'video_id' => rand(1 , 4),
                'user_id' => $user_id,
            ];
        }

        VideoVisit::insert($insert);
    }
}
