<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('out_trade_no');//订单号
            $table->string('consignee');//收货人
            $table->string('shipping_address');//收货地址
            $table->string('mobile');//收货人电话
            $table->double('total_fee');//付款金额
            $table->tinyInteger('status');//订单状态 0---》待支付，1--》已支付，2--》取消
            $table->timestamp('payment_time');//支付时间

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
        Schema::drop('orders');
    }
}
