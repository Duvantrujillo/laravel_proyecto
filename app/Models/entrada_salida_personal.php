<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class entrada_salida_personal extends Model
{
    use HasFactory;
    protected  $table = 'entrada_salida_personal';
    protected  $fillable = ['fecha_hora_ingreso','fecha_hora_salida','visito_ultimas_48h','nombre'];

    public function register_personal()
    {
        return $this->belongsTo(register_personal::class,'nombre','id');
    }
}
