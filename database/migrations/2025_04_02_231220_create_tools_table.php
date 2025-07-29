<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->integer('total_quantity');
            $table->string('product');
            $table->text('observation');
            $table->string('image_path')->nullable();
            $table->text('extra_info')->nullable();
            $table->enum('status', ['enabled', 'disabled'])->default('enabled'); // status with limited options
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
        Schema::dropIfExists('tools');
    }
}
