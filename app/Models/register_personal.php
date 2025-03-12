<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class register_personal extends Model
{
    use HasFactory;
    protected $table = 'register_personal';
    protected $fillable = ['id', 'nombre', 'numero_documento', 'numero_telefono', 'correo','grupo'];

    public function grupos_personal()
    {
        return $this->belongsTo(grupos_personal::class);
    }
    
}