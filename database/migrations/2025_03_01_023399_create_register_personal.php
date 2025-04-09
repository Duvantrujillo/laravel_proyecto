<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterPersonal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_personal', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('numero_documento')->unique();
            $table->string('numero_telefono');
            $table->string('correo');
            $table->timestamps();
            $table->foreignId('grupo')
            ->constrained('grupos_personal')
            ->onDelete('cascade');
            $table->foreignId('fichas')
                  ->constrained('fichas')
                  ->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('register_personal');
    }
}
