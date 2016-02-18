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
            $table->unsignedInteger('category_id');//所属分类
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('price');//价格
            $table->integer('original_price')->nullable();//原价
            $table->tinyInteger('use_coupon');//是否启用礼券额外抵用
            $table->integer('coupon_amount')->nullable();//礼券抵用金额
            $table->string('evaluation_person_image')->nullable();//评测师头像
            $table->text('evaluation_content')->nullable();
            $table->tinyInteger('express_way');//0:免邮，1:普通快递，2:EMS快递，3:新疆、青海、西藏等地区
            $table->integer('express_fee')->nullable();//快递费用
            $table->tinyInteger('returned_goods');//是否支持七天无理由退货 0：不支持，1：支持
            $table->text('description')->nullable();//商品描述
            $table->text('detailed_introduction')->nullable();//详细描述（富文本框）
            $table->integer('num');//库存
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
