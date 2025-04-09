<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePondUnitCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pond_unit_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pond_id')->constrained('GeoPonds')->onDelete('cascade');
            $table->integer('identificador');
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
        Schema::dropIfExists('pond_unit_codes');
    }
}
