<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvertsTable extends Migration
{
    public function up()
    {
        Schema::create('converts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('currency_from');
            $table->string('currency_to');
            $table->double('value', 255, 10);
            $table->double('converted_value', 255, 10);
            $table->string('rate');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('converts');
    }
}
