<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\pond_unit_code;

class Mortality extends Model
{
    use HasFactory;

    protected $fillable = [
        'datetime',
        'amount',
        'fish_balance',
        'observation',
        'pond_code_id',
        'user_id',
        'sowing_id',
        'cycle',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pondUnitCode()
    {
        return $this->belongsTo(pond_unit_code::class, 'pond_code_id');
    }
    public function sowing()
{
    return $this->belongsTo(Sowing::class);
}

}
