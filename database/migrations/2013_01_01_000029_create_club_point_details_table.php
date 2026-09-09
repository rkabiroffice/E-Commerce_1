<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('club_point_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('club_point_id');
            $table->integer('product_id');
            $table->integer('product_qty');
            $table->double('point', 8, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('club_point_details');
    }
};
