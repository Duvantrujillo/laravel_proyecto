<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaterQualitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('water_qualities', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('sowing_id'); // Relaciona con la siembra
        $table->date('date');
        $table->time('time');

        // Campos numéricos que ahora aceptan NULL
        $table->decimal('ph', 5, 2)->nullable();
        $table->decimal('temperature', 5, 2)->nullable();
        $table->decimal('ammonia', 5, 2)->nullable();
        $table->decimal('turbidity', 5, 2)->nullable();
        $table->decimal('dissolved_oxygen', 5, 2)->nullable();
        $table->decimal('nitrites', 5, 2)->nullable();
        $table->decimal('nitrates', 5, 2)->nullable();

        $table->text('justification')->nullable();

        $table->unsignedBigInteger('user_id'); // Usuario responsable
        $table->timestamps();

        // Claves foráneas
        $table->foreign('sowing_id')->references('id')->on('sowing')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('water_qualities');
    }
}
