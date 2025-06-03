<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoPond extends Model
{
    use HasFactory;
    
    // Cambié el nombre de la tabla a 'ponds' para que coincida con el nombre en la base de datos
    protected $table = 'geoponds';  
    
    protected $fillable = [
        'name',
    ];
}
