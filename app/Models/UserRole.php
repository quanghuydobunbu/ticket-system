<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    public $timestamps = false; 
    protected $fillable = ['user_id', 'role_id', 'granted_at'];
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

}
