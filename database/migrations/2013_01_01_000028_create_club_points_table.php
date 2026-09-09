<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('club_points', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->double('points', 18, 2);
            $table->integer('order_id');
            $table->integer('convert_status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('club_points');
    }
};
