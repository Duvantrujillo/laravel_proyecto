<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Species extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function types()
    {
        return $this->hasMany(Type::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::upper($value); // Converts to uppercase
    }
}