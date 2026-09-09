<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carrier_range_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('carrier_id');
            $table->integer('carrier_range_id');
            $table->integer('zone_id');
            $table->double('price', 8, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrier_range_prices');
    }
};
