<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $table = 'tools';

    protected $fillable = ['amount','total_quantity', 'product', 'observation', 'image_path', 'extra_info','status'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
