<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertLog extends Model
{
    use HasFactory;

    
    /*protected $fillable = [
        'room_id',
		'sensor_id',
		'temperature_value',
		'alert_type',
		'triggered_at',
		'resolved_at',
		'status'
    ]; */

    protected $casts = [
        'temperature_value' => 'float',
		'triggered_at' => 'datetime',
		'resolved_at' => 'datetime'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

	public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }

}
