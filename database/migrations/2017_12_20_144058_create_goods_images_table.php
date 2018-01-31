<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_images', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('goods_id')->comment('商品编号');
            $table->unsignedInteger('picture_id')->comment('图片编号');
            $table->unsignedInteger('sort')->comment('图片排序号码');
            $table->unsignedInteger('status')->default(1)->comment('1正常 0删除');

            $table->timestampsTime();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_images');
    }
}
