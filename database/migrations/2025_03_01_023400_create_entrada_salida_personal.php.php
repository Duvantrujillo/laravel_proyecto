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
            $table->timestamp('fecha_hora_salida')->nullable();
            $table->boolean('visito_ultimas_48h')->default(0); // 1 para "Sí", 0 para "No"
            $table->foreignId('nombre')
                ->constrained('register_personal')
                ->onDelete('cascade');
            $table->foreignId('grupo')
                ->constrained('grupos_personal')
                ->onDelete('cascade');
            $table->foreignId('ficha')
                ->constrained('fichas')
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