<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Type extends Model
{
    use HasFactory;

    protected $fillable = ['species_id', 'name'];

    public function species()
    {
        return $this->belongsTo(Species::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::upper($value); // Converts to uppercase
    }
}