<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvalableTimes extends Model
{
    protected $fillable = ['available_id', 'time'];

    public function available()
    {
        return $this->belongsTo(DoctorAvailable::class, 'available_id');
    }
}
