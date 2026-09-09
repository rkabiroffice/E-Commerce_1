<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carriers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->integer('logo')->nullable();
            $table->string('transit_time', 255);
            $table->boolean('free_shipping')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carriers');
    }
};
