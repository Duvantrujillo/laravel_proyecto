<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $table = 'tools';

    protected $fillable = ['amount', 'product', 'observation'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
