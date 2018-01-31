<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', '20')->comment('权限名称');
            $table->unsignedInteger('parent_id')->comment('父级权限编号');
            $table->string('icon', 50)->nullable()->comment('权限图标');
            $table->string('route', 255)->comment('权限route');

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
        Schema::dropIfExists('auth_permissions');
    }
}
