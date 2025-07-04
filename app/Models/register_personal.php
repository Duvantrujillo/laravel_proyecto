<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class register_personal extends Model
{
    use HasFactory;
    protected $table = 'register_personal';
    protected $fillable = ['id', 'nombre', 'numero_documento', 'numero_telefono', 'correo', 'grupo', 'fichas'];

  public function grupo()
{
    return $this->belongsTo(grupos_personal::class, 'grupo', 'id');
}


    public function ficha()
    {
        return $this->belongsTo(Ficha::class, 'fichas');
    }

    public function entradasSalidas()
{
    return $this->hasMany(entrada_salida_personal::class, 'nombre');
}

}
