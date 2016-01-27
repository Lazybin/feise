<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityClassificationGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_classification_goods', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('activity_classification_id');
            $table->foreign('activity_classification_id')->references('id')->on('activity_classifications')->onDelete('cascade');
            $table->unsignedInteger('goods_id');
            $table->foreign('goods_id')->references('id')->on('goods')->onDelete('cascade');
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
        Schema::drop('activity_classification_goods');
    }
}
