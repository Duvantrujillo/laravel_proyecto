<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSowingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sowing', function (Blueprint $table) {
            $table->id();
            $table->date('sowing_date'); // fecha_siembra
            $table-> date('sowing_completion')->nullable();// fecha final de siembra
            $table->decimal('initial_biomass', 8, 2); // biomasa_inicial
            $table->unsignedBigInteger('species_id'); // especie_id (FK species)
            $table->unsignedBigInteger('type_id'); // tipo_id (FK tipo de especie)
            $table->integer('initial_feeding_frequency'); // frecuencia_alimento_inicial
            $table->integer('fish_count'); // numero_peces
            $table->string('origin'); // origen
            $table->decimal('area', 8, 2); // area (igual en inglés)
            $table->decimal('initial_weight', 8, 2); // peso_inicial
            $table->decimal('total_weight', 10, 2); // peso_total (Automatic)
            $table->decimal('initial_density', 8, 2); // densidad_inicial (Automatic)
            $table->unsignedBigInteger('pond_id'); // estanque_id (FK pond)
            $table->unsignedBigInteger('identifier_id'); // identificador_id (FK identificador)
            $table->string('state')->default('inicializada');
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
        Schema::dropIfExists('sowing');
    }
}

