<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;
    protected $table = 'menus';

    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(Menu::class,'parent_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'menu_roles');
    }
}
