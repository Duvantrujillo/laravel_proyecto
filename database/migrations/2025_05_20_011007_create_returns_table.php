<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade'); // préstamo al que pertenece
            $table->dateTime('return_date');
            $table->integer('quantity_returned');
            $table->text('return_status')->nullable(); // estado en que se devuelve
            $table->text('imge_path')->nullable();
             $table->foreignId('received_by')->constrained('users')->onDelete('cascade'); // se llena automáticamente con el usuario logueado
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
        Schema::dropIfExists('returns');
    }
}
