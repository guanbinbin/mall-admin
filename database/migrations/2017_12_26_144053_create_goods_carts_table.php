<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_cart', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name',50)->comment('商品名称');
            $table->decimal('price',10,2)->comment('商品价格');
            $table->unsignedInteger('total')->comment('商品数量');
            $table->unsignedInteger('thumb_url')->comment('商品主图');
            $table->decimal('amount',10,2)->comment('商品单价');
            $table->decimal('totalAmount',10,2)->comment('总价');

            $table->unsignedInteger('status')->comment('1正常 0删除');
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
        Schema::dropIfExists('goods_cart');
    }
}
