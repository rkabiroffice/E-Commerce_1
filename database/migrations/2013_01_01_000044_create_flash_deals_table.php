<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('flash_deals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->nullable();
            $table->integer('start_date')->nullable();
            $table->integer('end_date')->nullable();
            $table->integer('status')->default(0);
            $table->integer('featured')->default(0);
            $table->string('background_color', 255)->nullable();
            $table->string('text_color', 255)->nullable();
            $table->string('banner', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flash_deals');
    }
};
