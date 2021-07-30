<?php

use Illuminate\Database\Migrations\Migration;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class CreateViewsRankingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = DB::table('ranks')
                ->selectRaw('book_id , sum(hits) as hits , date , YEAR(date) AS year , MONTH(date) AS month ,WEEK(date , 1) AS week')
                ->where('book_id', '>', '0')
                ->whereRaw('YEAR(date) = YEAR(CURTIME())')
                ->groupByRaw('week, book_id')
                ->orderByDesc('week')
                ->orderByDesc('hits');
                
        Schema::createOrReplaceView('views_ranking', $query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('views_ranking');
    }
}
