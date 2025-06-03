<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietMonitoring extends Model
{
    use HasFactory;
protected $table = 'diet_monitorings';
    protected $fillable = [
        'sampling_date',
        'average_weight',
        'fish_balance',
        'biomass_percentage',
        'biomass',
        'daily_feed',
        'ration_number',
        'ration',
        'weight_gain',
        'cumulative_mortality',
        'feed_type',
        'sowing_id',
    ];

    /**
     * Relación: El monitoreo de dieta pertenece a una siembra (sowing).
     */
    public function sowing()
    {
        return $this->belongsTo(Sowing::class);
    }
}
