<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictures', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('width')->nullable()->comment('图片宽度');
            $table->unsignedInteger('height')->nullable()->comment('图片高度');
            $table->string('md5', 50)->nullable()->comment('图片md5编码');
            $table->string('path', 255)->comment('图片地址');
            $table->unsignedInteger('type')->default(1)->comment('1本地图片 2 ALIOSS图片');

            $table->unsignedInteger('status')->default(1);
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
        Schema::dropIfExists('pictures');
    }
}
