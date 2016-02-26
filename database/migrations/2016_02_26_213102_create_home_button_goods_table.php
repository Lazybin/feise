<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeButtonGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_button_goods', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('home_button_id');
            $table->foreign('home_button_id')->references('id')->on('home_navigations')->onDelete('cascade');
            $table->unsignedInteger('goods_id');
            $table->foreign('goods_id')->references('id')->on('goods');
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
        Schema::drop('home_button_goods');
    }
}
