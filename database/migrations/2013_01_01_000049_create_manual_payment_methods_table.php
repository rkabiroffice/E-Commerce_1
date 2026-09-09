<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('manual_payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 255)->nullable();
            $table->string('heading', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('bank_info')->nullable();
            $table->string('photo', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('manual_payment_methods');
    }
};
