<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('affiliate_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 255)->nullable();
            $table->longText('details')->nullable();
            $table->double('percentage')->default(0);
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_options');
    }
};
