<?php

use Illuminate\Database\Migrations\Migration;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class CreateViewsGuestHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     */
    public function up()
    {
        $query = DB::table('histories')->select([
            'major_id',
            'minor_id',
            'class',
            'type',
        ])
            ->selectRaw('COUNT(*) as count')
            ->where('user_vip', -1)
            ->where('major_id', '!=', 0)
            ->groupBy([
                'major_id',
                'minor_id',
                'class',
                'type',
            ]);

        Schema::createOrReplaceView('views_guest_histories', $query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropViewIfExists('views_guest_histories');
    }
}
