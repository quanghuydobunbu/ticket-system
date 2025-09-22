<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'description', 'premissions'];
    public function user(){
        return $this->belongsTo(UserRole::class, 'user_id', 'id');
    }
}
