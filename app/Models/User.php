<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function waterQualities()
{
    return $this->hasMany(WaterQuality::class, 'user_id');
}

public function feedRecords()
{
    return $this->hasMany(FeedRecord::class, 'responsible_id');
}

public function loansDelivered()
{
    return $this->hasMany(Loan::class, 'delivered_by');
}
public function assignedSowings()
{
    return $this->belongsToMany(Sowing::class, 'assigned_sowings');
}

}
