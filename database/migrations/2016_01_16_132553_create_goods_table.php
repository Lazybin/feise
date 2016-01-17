<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');//商品名称
            $table->integer('price');//价格
            $table->integer('original price')->nullable();//原价
            $table->tinyInteger('use_coupon');//是否启用礼券额外抵用
            $table->integer('coupon_amount')->nullable();//礼券抵用金额
            $table->tinyInteger('express_way');//0:免邮，1:普通快递，2:EMS快递，3:新疆、青海、西藏等地区费用
            $table->integer('express_fee')->nullable();//快递费用
            $table->tinyInteger('returned_goods');//是否支持七天无理由退货 0：不支持，1：支持
            $table->text('description')->nullable();//商品描述
            $table->text('detailed_introduction')->nullable();//详细描述（富文本框）
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
        Schema::drop('goods');
    }
}
