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
}
