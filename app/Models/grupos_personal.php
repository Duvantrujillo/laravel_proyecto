<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grupos_personal extends Model
{
    use HasFactory;

    protected $table = 'grupos_personal';  // Aquí se especifica la tabla 'grupos_personal'

    protected $fillable = ['nombre', 'numero_ficha'];  // Los campos que pueden ser llenados

   
}
