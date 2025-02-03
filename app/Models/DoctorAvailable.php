<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorAvailable extends BaseModel
{

    protected $fillable = [
        'doctor_id',
        'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class)->where('role', 'doctor');
    }

    public function availableTimes()
    {
        return $this->hasMany(AvalableTimes::class, 'available_id');
    }
}
