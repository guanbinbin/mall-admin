<?php

use Illuminate\Support\Facades\Schema;
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
            $table->increments('id');

            $table->string('name', 50)->comment('商品名称');
            $table->unsignedInteger('image')->comment('商品主图');
            $table->decimal('price', 10, 2)->default(0)->comment('商品价格');
            $table->text('detail')->nullable()->comment('商品详情');
            $table->unsignedInteger('goods_status')->default(10)
                ->comment('10下架 20上架');

            $table->unsignedInteger('status')->default(1)->comment('0删除 1正常');
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
        Schema::dropIfExists('goods');
    }
}
