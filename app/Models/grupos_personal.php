<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grupos_personal extends Model
{
    use HasFactory;

    protected $table = 'grupos_personal'; // Especifica la tabla exacta
    protected $fillable = ['id', 'nombre']; // Ajusta según tus columnas
}