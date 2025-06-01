<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemModule extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function menu()
    {
        return $this->hasOne(Menu::class);
    }

    public function system_module_category()
    {
        return $this->belongsTo(SystemModuleCategory::class);
    }
}
