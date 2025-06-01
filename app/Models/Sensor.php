<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    
    /*protected $fillable = [
        'room_id',
		'sensor_name',
		'sensor_type',
		'installation_date',
		'status'
    ]; */

    protected $casts = [
        'installation_date' => 'date'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

}
