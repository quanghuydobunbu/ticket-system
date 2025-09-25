<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $fillable = ['event_id', 'name', 'price', 'quantity_total', 'quantity_sold', 'max_per_order', 'sale_end', 'is_active'];
    public function tickets(){
        return $this->hasMany(Ticket::class, 'ticket_type_id', 'id');
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
