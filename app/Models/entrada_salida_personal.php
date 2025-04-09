<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class entrada_salida_personal extends Model
{
    use HasFactory;

    protected $table = 'entrada_salida_personal';
    protected $fillable = [
        'fecha_hora_ingreso',
        'fecha_hora_salida',
        'visito_ultimas_48h',
        'nombre', // Este campo es un ID
        'grupo',  // Este campo es un ID
        'ficha',  // Este campo es un ID
    ];

    // Definir casts para fechas
    protected $casts = [
        'fecha_hora_ingreso' => 'datetime',
        'fecha_hora_salida' => 'datetime',
    ];

    // Relación con el modelo register_personal (nombre)
    public function nombreRelacion()
    {
        return $this->belongsTo(register_personal::class, 'nombre');
    }

    // Relación con el modelo grupos_personal (grupo)
    public function grupoRelacion()
    {
        return $this->belongsTo(grupos_personal::class, 'grupo');
    }

    // Relación con el modelo Ficha (ficha)
    public function fichaRelacion()
    {
        return $this->belongsTo(Ficha::class, 'ficha');
    }

    public function getVisitoUltimas48hTextoAttribute()
    {
        return $this->visito_ultimas_48h == 1 ? 'Sí' : 'No';
    }
}