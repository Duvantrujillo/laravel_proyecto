<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MortalitiesTableSeeder extends Seeder
{
    public function run()
    {
        $mortalities = [
            3, 2, 4, 1, 5, 3, 4, 2, 3, 1, 4, 5, 2, 3  // Total: 47
        ];

        $now = Carbon::now();

        foreach ($mortalities as $index => $amount) {
            DB::table('mortalities')->insert([
                'datetime' => $now->copy()->subDays(14 - $index),
                'amount' => $amount,
                'fish_balance' => 1000 - array_sum(array_slice($mortalities, 0, $index + 1)),
                'observation' => 'Mortalidad registrada automáticamente',
                'pond_code_id' => 1,
                'user_id' => 1,
                'sowing_id' => 1,
                'cycle' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
