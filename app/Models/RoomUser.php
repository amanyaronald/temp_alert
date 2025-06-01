<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomUser extends Model
{
    use HasFactory;

    protected $table = 'room_user';

    /*protected $fillable = [
        'user_id',
		'room_id',
		'access_level'
    ]; */

    protected $casts = [
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

	public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

}
