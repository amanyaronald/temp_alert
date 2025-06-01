<?php

namespace Sensy\UpdatePatches\Models;

use Illuminate\Database\Eloquent\Model;

class Patch extends Model
{
    protected $table = 'patches';

    protected $fillable = [
        'name',
        'author',
        'task_list',
        'status',
        'created_at',
        'applied_at',
        'user_id'
    ];

    #default value for updated at
    protected $attributes = [
        'status' => 'pending',
    ];

    public function tasks(){
        return $this->hasMany(PatchTask::class,);
    }

}
