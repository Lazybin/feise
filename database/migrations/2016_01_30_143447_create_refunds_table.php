<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->string('refund_reason');//退款原因
            $table->float('refund_amount');//退款金额
            $table->string('refund_explain');//退款说明
            $table->string('pic1')->nullbale();//图片1
            $table->string('pic2')->nullbale();//图片2
            $table->string('pic3')->nullbale();//图片3
            $table->tinyInteger('status');//货物状态
            $table->tinyInteger('type');//1--->待发货，申请退款 2---->已发货，退货退款 3--->已发货，仅退款
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
        Schema::drop('refunds');
    }
}
