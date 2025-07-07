<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedSowing extends Model
{
    use HasFactory;

    protected $table = 'assigned_sowings';

    protected $fillable = ['user_id', 'sowing_id'];

    // ✅ Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Relación con el modelo Sowing
    public function sowing()
    {
        return $this->belongsTo(Sowing::class);
    }
}
