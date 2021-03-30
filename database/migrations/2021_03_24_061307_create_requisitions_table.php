<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_location')->comment('寄貨地點');
            $table->string('shipping_method')->comment('運輸方式');
            $table->string('shipper_name')->comment('寄件人姓名');
            $table->string('shipper_phone')->comment('寄件人電話');
            $table->string('receiver_name')->comment('收件人姓名');
            $table->string('receiver_phone')->comment('收件人電話');
            $table->string('receiver_address')->comment('收件人地址');
            $table->string('shipping_list')->comment('寄貨清單');
            $table->string('total_value')->comment('總價值');
            $table->string('weight')->comment('重量(g)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisitions');
    }
}
