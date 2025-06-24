<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
    'tool_id',
    'item',
    'quantity',
    'loan_date',
    'requester_name',
    'requester_id',
    'delivered_by',
    'loan_status',
    'returned_quantity',
];


    public function tool()
{
    return $this->belongsTo(Tool::class);
}

public function returns()
{
    return $this->hasMany(ReturnModel::class);
}

}
