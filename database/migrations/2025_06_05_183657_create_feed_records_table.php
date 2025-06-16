<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_records', function (Blueprint $table) {
            $table->id();
            $table->date('feeding_date');
            $table->decimal('r1', 10, 2)->nullable();
            $table->decimal('r2', 10, 2)->nullable();
            $table->decimal('r3', 10, 2)->nullable();
            $table->decimal('r4', 10, 2)->nullable();
            $table->decimal('r5', 10, 2)->nullable();
            $table->decimal('daily_ration', 10, 2);
            $table->decimal('crude_protein', 10, 2);
            $table->text('justification')->nullable();
            $table->foreignId('diet_monitoring_id')
                  ->constrained('diet_monitorings')
                  ->onDelete('cascade');
            $table->foreignId('responsible_id')
                  ->constrained('users')
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
        Schema::dropIfExists('feed_records');
    }
}
