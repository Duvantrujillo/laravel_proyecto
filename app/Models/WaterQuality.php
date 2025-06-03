<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterQuality extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'ph',
        'temperature',
        'ammonia',
        'turbidity',
        'dissolved_oxygen',
        'nitrites',
        'nitrates',
        'responsible',
        'sowing_id',
        'user_id'
    ];

    public function sowing()
    {
        return $this->belongsTo(Sowing::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
