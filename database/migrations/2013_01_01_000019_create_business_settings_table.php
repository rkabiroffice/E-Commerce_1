<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('business_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 30);
            $table->longText('value')->nullable();
            $table->string('lang', 30)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_settings');
    }
};
