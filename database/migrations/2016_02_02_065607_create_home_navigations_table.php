<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_navigations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');//主标题
            $table->string('subhead')->nullable();//副标题
            $table->tinyInteger('type');//0--->图片模式，1--->网页模式
            $table->string('path')->nullable();//图片路径
            $table->integer('sort');//排序
            $table->string('action');//动作
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
        Schema::drop('home_navigations');
    }
}
