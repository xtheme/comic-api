<?php

use Illuminate\Database\Migrations\Migration;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class CreateViewsOrdersCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = DB::table('orders')->select('user_id')->selectRaw('COUNT(*) as count')->groupBy('user_id');
        Schema::createOrReplaceView('views_orders_counts', $query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropViewIfExists('views_orders_counts');
    }
}
