<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_address', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sex')->default(1)->comment('1男 0女');
            $table->string('name','20')->comment('收货人姓名');
            $table->string('phone','20')->comment('收货人电话');
            $table->string('address',255)->comment('收货地址');
            $table->unsignedInteger('default')->default(0)->comment('1默认 0非默认');

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
        Schema::dropIfExists('users_address');
    }
}
