<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('fichas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre')->unique(); // Asegura que el nombre de la ficha sea único
        $table->unsignedBigInteger('grupo_id');
        $table->foreign('grupo_id')->references('id')->on('grupos_personal')->onDelete('cascade');
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
        Schema::dropIfExists('fichas');
    }
}
