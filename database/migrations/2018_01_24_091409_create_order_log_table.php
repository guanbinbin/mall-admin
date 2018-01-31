<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->comment('订单编号');
            $table->integer('action_time')->comment('操作时间');
            $table->unsignedInteger('type')->default(1)->comment('操作分类 1用户 2管理员');
            $table->unsignedInteger('u_id')->default(0)->comment('操作者 0系统操作');
            $table->string('msg')->comment('操作描述');

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
        Schema::dropIfExists('order_log');
    }
}
