<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained()->onDelete('cascade'); // Relación con la herramienta
            $table->string('item');         // puede ser redundante, pero te permite mostrar sin join si lo deseas
            $table->integer('quantity');
            $table->dateTime('loan_date');
            $table->string('requester_name');
            $table->string('requester_id');
            $table->foreignId('delivered_by')->constrained('users')->onDelete('cascade');// se llena automáticamente con el usuario logueado
            $table->text('loan_status')->nullable(); // descripción del estado al prestar
            $table->integer('returned_quantity')->default(0); // cuántos se han devuelto
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
        Schema::dropIfExists('loans');
    }
}
