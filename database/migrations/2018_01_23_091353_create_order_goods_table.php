<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->comment('订单编号');
            $table->unsignedInteger('goods_id')->comment('商品编号');
            $table->string('goods_name', 50)->comment('商品名称');
            $table->decimal('goods_price', 10, 2)->comment('商品卖价');
            $table->unsignedInteger('goods_num')->comment('商品数量');
            $table->unsignedInteger('goods_image')->comment('商品图片');
            $table->string('logistics_no', 50)->nullable()->comment('物流单号');
            $table->unsignedInteger('is_comment')->default(0)
                ->comment('是否评论 0未评论 1已评论');

            $table->unsignedInteger('status')->default(1)->comment('状态 1正常 0删除');
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
        Schema::dropIfExists('order_goods');
    }
}
