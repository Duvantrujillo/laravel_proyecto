<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'feeding_date',
        'r1',
        'r2',
        'r3',
        'r4',
        'r5',
        'daily_ration',
        'crude_protein',
        'justification',
        'diet_monitoring_id',
        'responsible_id',
    ];

    protected $casts = [
        'feeding_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con DietMonitoring.
     */
    public function dietMonitoring()
    {
        return $this->belongsTo(DietMonitoring::class);
    }

    /**
     * Relación con el usuario responsable.
     */
    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
}
