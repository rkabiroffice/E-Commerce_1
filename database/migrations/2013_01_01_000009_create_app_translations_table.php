<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('app_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lang', 10)->nullable();
            $table->string('lang_key', 255)->nullable();
            $table->string('lang_value', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_translations');
    }
};
