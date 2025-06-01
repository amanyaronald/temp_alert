<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    use HasFactory;

    
    /*protected $fillable = [
        'room_id',
		'min_temperature',
		'max_temperature'
    ]; */

    protected $casts = [
        'min_temperature' => 'float',
		'max_temperature' => 'float'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

}
