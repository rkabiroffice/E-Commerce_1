<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id');
            $table->integer('user_id');
            $table->longText('reply');
            $table->longText('files')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_replies');
    }
};
