<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;


    /*protected $fillable = [
        'name',
		'location',
		'owner_user_id'
    ]; */

    protected $casts = [

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function rooms()
    {
        return $this->HasMany(RoomUser::class);
    }
}
