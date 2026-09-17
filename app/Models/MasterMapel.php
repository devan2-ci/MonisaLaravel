<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMapel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'master_mapels';
    protected $guarded = [];

    public function schoolMapels()
    {
        return $this->hasMany(SchoolMapel::class, 'master_mapel_id');
    }
}
