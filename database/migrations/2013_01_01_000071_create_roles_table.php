<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name', 191);
            $table->string('guard_name', 191);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique(['name', 'guard_name'], 'roles_name_guard_name_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
