<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemModuleCategory extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function systemModules()
    {
        return $this->hasMany(SystemModule::class);
    }

}
