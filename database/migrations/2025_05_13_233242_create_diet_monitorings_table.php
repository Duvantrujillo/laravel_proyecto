<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDietMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diet_monitorings', function (Blueprint $table) {
             $table->id();
            $table->date('sampling_date');
            $table->decimal('average_weight', 8, 2);
            $table->decimal('fish_balance', 10, 2);
            $table->decimal('biomass_percentage', 5, 2);
            $table->decimal('biomass', 10, 2);
            $table->decimal('daily_feed', 10, 2);
            $table->integer('ration_number');
            $table->decimal('ration', 10, 2);
            $table->decimal('weight_gain', 8, 2);
            $table->decimal('cumulative_mortality', 8, 2);
            $table->string('feed_type');
            $table->foreignId('sowing_id')->constrained('sowing')->onDelete('cascade');
            $table->integer('cycle')->default(1);
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
        Schema::dropIfExists('diet_monitorings');
    }
}
