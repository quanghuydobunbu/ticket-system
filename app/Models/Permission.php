<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function permissions(){
        return $this->hasMany(RolePermission::class, 'permission_id', 'id');
    }
}
