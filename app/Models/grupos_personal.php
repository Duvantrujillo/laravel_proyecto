<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grupos_personal extends Model
{
    use HasFactory;
    protected $table = 'grupos_personal'; // Tabla específica
    protected $fillable = ['nombre']; // Solo los campos editable

      public function fichas()
    {
        return $this->hasMany(Ficha::class, 'grupo_id');
    }
}