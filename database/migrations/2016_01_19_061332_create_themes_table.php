<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->increments('id');
            $table->engine = 'InnoDB';
            $table->unsignedInteger('category_id');//所属分类
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('title')->nullable();
            $table->string('cover')->nullable();
            $table->string('head_image')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('type');//0--->普通模式，1-->图文结合
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
        Schema::drop('themes');
    }
}
