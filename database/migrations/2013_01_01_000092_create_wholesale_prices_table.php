<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wholesale_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_stock_id');
            $table->integer('min_qty')->default(0);
            $table->integer('max_qty')->default(0);
            $table->double('price', 20, 2)->default(0.00);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesale_prices');
    }
};
