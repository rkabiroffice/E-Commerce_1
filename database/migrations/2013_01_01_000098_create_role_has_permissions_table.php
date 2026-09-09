<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->bigInteger('permission_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();
            $table->primary(['permission_id', 'role_id']);
            $table->index(['role_id'], 'role_has_permissions_role_id_foreign');
            $table->foreign(['permission_id'])->references(['id'])->on('permissions')->onDelete('cascade');
            $table->foreign(['role_id'])->references(['id'])->on('roles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_has_permissions');
    }
};
