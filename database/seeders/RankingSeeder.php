<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\RankingLog;
use Faker\Factory;
use Illuminate\Database\Seeder;

class RankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=RankingSeeder
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 0; $i < 2000; $i++) {
            $book = Book::find($faker->numberBetween(1, 5000));
            if ($book) {
                $book->increment('view_counts');
                $book->logRanking();
            }
        }
    }
}
