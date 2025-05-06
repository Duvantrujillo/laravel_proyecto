<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMortalityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mortalities', function (Blueprint $table) {
            $table->id();
            $table->dateTime('datetime');
            $table->integer('amount');
            $table->integer('fish_balance');
            $table->text('observation')->nullable();
            $table->foreignId('pond_code_id')->constrained('pond_unit_codes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mortality');
    }
}
