<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payku_payments', function (Blueprint $table) {
            $table->string('transaction_id', 191);
            $table->date('start');
            $table->date('end');
            $table->string('media', 191);
            $table->string('transaction_key', 255)->nullable();
            $table->string('payment_key', 255)->nullable();
            $table->string('verification_key', 191);
            $table->string('authorization_code', 191);
            $table->integer('last_4_digits')->unsigned()->nullable();
            $table->string('installments', 191)->nullable();
            $table->string('card_type', 191)->nullable();
            $table->string('additional_parameters', 191)->nullable();
            $table->string('currency', 191);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique(['transaction_id'], 'payku_payments_transaction_id_unique');
            $table->foreign(['transaction_id'])->references(['id'])->on('payku_transactions');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payku_payments');
    }
};
