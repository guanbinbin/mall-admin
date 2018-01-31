<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_sn', 25)->comment('订单编号');
            $table->string('pay_sn', 25)->comment('支付单号');
            $table->unsignedInteger('buy_id')->comment('买家编号');
            $table->string('buy_name', 20)->comment('买家姓名');
            $table->integer('create_time')->comment('订单创建时间');
            $table->integer('pay_time')->nullable()->comment('订单支付时间');
            $table->integer('fin_time')->nullable()->comment('订单完成时间');
            $table->unsignedInteger('pay_code')->nullable()->comment('支付编码 1微信支付');
            $table->decimal('goods_amount', 10, 2)->comment('商品总价');
            $table->decimal('order_amount', 10, 2)->comment('订单总价');
            $table->decimal('pay_money', 10, 2)->default(0)->comment('实际支付金额');
            $table->unsignedInteger('order_status')->default(10)
                ->comment('订单状态：0(已取消)10(默认):未付款;20:已付款;30:已发货;40:已收货;50:订单完成');

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
        Schema::dropIfExists('order');
    }
}
