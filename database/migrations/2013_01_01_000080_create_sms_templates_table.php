<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identifier', 100);
            $table->longText('sms_body');
            $table->string('template_id', 100)->nullable();
            $table->boolean('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_templates');
    }
};
