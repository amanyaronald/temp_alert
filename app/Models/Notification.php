<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    
    /*protected $fillable = [
        'user_id',
		'room_id',
		'message',
		'notification_type',
		'sent_at',
		'status'
    ]; */

    protected $casts = [
        'sent_at' => 'datetime'
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
