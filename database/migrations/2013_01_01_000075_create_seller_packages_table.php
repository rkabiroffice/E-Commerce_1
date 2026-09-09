<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seller_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->double('amount', 11, 2)->default(0.00);
            $table->integer('product_upload_limit')->default(0);
            $table->string('logo', 255)->nullable();
            $table->integer('duration')->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seller_packages');
    }
};
