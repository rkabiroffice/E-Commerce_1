<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payku_transactions', function (Blueprint $table) {
            $table->string('id', 191);
            $table->string('status', 191)->nullable();
            $table->string('order', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('subject', 191)->nullable();
            $table->text('url')->nullable();
            $table->integer('amount')->unsigned()->nullable();
            $table->dateTime('notified_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique(['id'], 'payku_transactions_id_unique');
            $table->unique(['order'], 'payku_transactions_order_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payku_transactions');
    }
};
