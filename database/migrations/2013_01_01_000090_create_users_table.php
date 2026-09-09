<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('referred_by')->nullable();
            $table->string('provider', 255)->nullable();
            $table->string('provider_id', 50)->nullable();
            $table->text('refresh_token')->nullable();
            $table->longText('access_token')->nullable();
            $table->string('user_type', 20)->default('customer');
            $table->string('name', 191);
            $table->string('email', 191)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->text('verification_code')->nullable();
            $table->text('new_email_verificiation_code')->nullable();
            $table->string('password', 191)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('device_token', 255)->nullable();
            $table->string('avatar', 256)->nullable();
            $table->string('avatar_original', 256)->nullable();
            $table->string('address', 300)->nullable();
            $table->string('country', 30)->nullable();
            $table->string('state', 30)->nullable();
            $table->string('city', 30)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->double('balance', 20, 2)->default(0.00);
            $table->tinyInteger('banned')->default(0);
            $table->string('referral_code', 255)->nullable();
            $table->integer('customer_package_id')->nullable();
            $table->integer('remaining_uploads')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique(['email'], 'users_email_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
