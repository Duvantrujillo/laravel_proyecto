<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
    'full_name',
    'observation_id',
    'item',
    'quantity',
    'loan_date',
    'requester_name',
    'requester_id',
    'delivered_by',
    'loan_status',
    'returned_quantity',
];


    public function observation()
{
    return $this->belongsTo(Observation::class);
}

public function returns()
{
    return $this->hasMany(ReturnModel::class);
}

}
