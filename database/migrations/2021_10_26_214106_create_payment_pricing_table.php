<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPricingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_pricing', function (Blueprint $table) {
            $table->integer('payment_id');
            $table->integer('pricing_id')->index('pricing_id');
            $table->unique(['payment_id', 'pricing_id'], 'payment_id_pricing_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_pricing');
    }
}
