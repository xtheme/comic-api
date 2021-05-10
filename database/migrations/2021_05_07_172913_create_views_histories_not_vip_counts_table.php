<?php

use Illuminate\Database\Migrations\Migration;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class CreateViewsHistoriesNotVipCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = DB::table('histories')->select(['minor_id' , 'class'])->selectRaw('COUNT(*) as count')->where([
            ['user_vip' , -1],
            ['type' , 'play']
        ])->groupBy(['minor_id' , 'class']);
        Schema::createOrReplaceView('views_histories_not_vip_counts', $query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropViewIfExists('views_histories_not_vip_counts');
    }
}
