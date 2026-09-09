<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('combined_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->text('shipping_address')->nullable();
            $table->double('grand_total', 20, 2)->default(0.00);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('combined_orders');
    }
};
