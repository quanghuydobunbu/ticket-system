<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = ['name', 'address', 'city', 'capacity', 'email', 'phone', 'is_active'];
    public function events(){
        return $this->hasMany(Event::class, 'venue_id', 'id');
    }
}
