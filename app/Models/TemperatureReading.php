<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemperatureReading extends Model
{
    use HasFactory;

    
    /*protected $fillable = [
        'sensor_id',
		'temperature_value',
		'recorded_at'
    ]; */

    protected $casts = [
        'temperature_value' => 'float',
		'recorded_at' => 'datetime'
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }

}
