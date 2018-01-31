<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title', 50)->comment('轮播标题');
            $table->unsignedInteger('type')->default(1)->comment('1商品 2帖子 3通知');
            $table->unsignedInteger('ex_id')->comment('外部配对链接');
            $table->unsignedInteger('image')->comment('图片');

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
        Schema::dropIfExists('banners');
    }
}
