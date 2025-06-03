<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDietFollowUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('diet_follow_ups', function (Blueprint $table) {
        $table->id();
        $table->date('sample_date');
        $table->decimal('biomass', 10, 2);
        $table->decimal('average_weight', 8, 2);
        $table->integer('accumulated_mortality')->default(0);
        $table->integer('fish_balance');
        $table->decimal('biomass_percentage', 5, 2);
        $table->decimal('daily_food', 10, 2);
        $table->decimal('ration', 10, 2);
        $table->integer('number_rations');
        $table->decimal('weight_gain', 8, 2)->default(0);
        $table->string('food_type');
        $table->foreignId('sowing_id')->constrained('sowing');
        $table->foreignId('previous_follow_up_id')->nullable()->constrained('diet_follow_ups');
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
        Schema::dropIfExists('diet_follow_ups');
    }
}
