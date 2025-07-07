<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sowing extends Model
{
    use HasFactory;

    protected $table = 'sowing'; // Especifica explícitamente el nombre de la tabla

    protected $fillable = [
        'sowing_date',          // fecha_siembra
        'sowing_completion',    // fecha final de siembra
        'initial_biomass',        // biomasa_inicial
        'species_id',             // especie_id
        'type_id',                // tipo_id
        'initial_feeding_frequency', // frecuencia_alimento_inicial
        'fish_count',             // numero_peces
        'origin',                 // origen
        'area',                   // area
        'initial_weight',         // peso_inicial
        'total_weight',           // peso_total
        'initial_density',        // densidad_inicial
        'pond_id',                // estanque_id
        'identifier_id',          // identificador_id
        'state',
    ];
    public function species()
{
    return $this->belongsTo(Species::class, 'species_id');
}

public function type()
{
    return $this->belongsTo(Type::class, 'type_id'); // Aquí se usa 'type_id' como clave foránea
}
public function pond()
{
    return $this->belongsTo(GeoPond::class, 'pond_id');
}

public function dietMonitorings()
{
    return $this->hasMany(DietMonitoring::class);
}
public function identifier()
{
    return $this->belongsTo(pond_unit_code::class, 'identifier_id');
}
public function mortalities()
{
    return $this->hasMany(Mortality::class);
}
public function feedRecords()
{
    return $this->hasMany(FeedRecord::class);
}
  public function lastMonitoring()
    {
        return $this->hasOne(DietMonitoring::class)->latestOfMany();
    }
      public function waterQualities()
    {
        return $this->hasMany(WaterQuality::class, 'sowing_id');
    }
    public function assignedUsers()
{
    return $this->belongsToMany(User::class, 'assigned_sowings');
}

}
