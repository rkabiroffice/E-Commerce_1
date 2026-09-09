<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('affiliate_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('affiliate_user_id');
            $table->integer('no_of_click')->default(0);
            $table->integer('no_of_order_item')->default(0);
            $table->integer('no_of_delivered')->default(0);
            $table->integer('no_of_cancel')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_stats');
    }
};
