<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('affiliate_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('guest_id')->nullable();
            $table->integer('referred_by_user');
            $table->double('amount', 20, 2);
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('order_detail_id')->nullable();
            $table->string('affiliate_type', 255);
            $table->tinyInteger('status')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_logs');
    }
};
