<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('user_id');
            $table->string('subject', 255);
            $table->longText('details')->nullable();
            $table->longText('files')->nullable();
            $table->string('status', 10)->default('pending');
            $table->integer('viewed')->default(0);
            $table->integer('client_viewed')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
