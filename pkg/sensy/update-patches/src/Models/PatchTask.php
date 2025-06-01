<?php

namespace Sensy\UpdatePatches\Models;

use Illuminate\Database\Eloquent\Model;

class PatchTask extends Model
{
    protected $table = 'patch_tasks';

    protected $fillable = [
        'function',
        'description',
        'status',
        'created_at',
        'applied_at',
        'user_id'
    ];

    public function patch(){
        return $this->belongsTo(Patch::class,'patch_id','id');
    }

}
