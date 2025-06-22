<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;


    public function farm()
    {
        return $this->belongsTo(Farm::class, 'farm_id');
    }

    public function threshold(){
        return $this->hasOne(Threshold::class);
    }

}
