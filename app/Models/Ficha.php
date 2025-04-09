<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'grupo_id'];

    // Relación: Una ficha pertenece a un grupo
    public function grupo()
    {
        return $this->belongsTo(grupos_personal::class);
    }
}