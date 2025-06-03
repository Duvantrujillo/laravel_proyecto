<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pond_unit_code extends Model
{
    use HasFactory;
    protected $table = 'pond_unit_codes';
    protected $fillable =['identificador','pond_id'
    ];

    public function pond(){
     return $this->belongsTo(GeoPond::class);
}




public function lastSowing()
{
    // Relación correcta usando identifier_id como clave foránea
    return $this->hasOne(Sowing::class, 'identifier_id')->latestOfMany();
}

public function sowings()
{
    // Relación correcta usando identifier_id como clave foránea
    return $this->hasMany(Sowing::class, 'identifier_id');
}

}
