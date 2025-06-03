<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'loan_id',
        'quantity_returned',
        'return_date',
        'return_status',
        'received_by',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
