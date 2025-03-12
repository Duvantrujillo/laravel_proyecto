<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradaSalidaPersonal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrada_salida_personal', function (Blueprint $table) {
            $table->id();
            
            $table->timestamp('fecha_hora_ingreso');
            $table->timestamp('fecha_hora_salida');
            $table->enum('visito_ultimas_48h', ['Sí', 'No'])->default('No');  // Usando 'Sí' y 'No'
            $table->foreignId('nombre')
            ->constrained('register_personal')
            ->onDelete('cascade');
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
        Schema::dropIfExists('entrada_salida_personal');
    }
}
