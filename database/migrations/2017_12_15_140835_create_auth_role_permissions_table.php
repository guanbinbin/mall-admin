<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_role_permissions', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('role_id')->comment('角色编号');
            $table->unsignedInteger('permissions_id')->comment('权限编号');

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
        Schema::dropIfExists('auth_role_permissions');
    }
}
