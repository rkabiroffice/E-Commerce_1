<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_queries', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->integer('customer_id');
            $table->integer('seller_id');
            $table->integer('product_id');
            $table->longText('question');
            $table->longText('reply')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_queries');
    }
};
