<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_code', 'booking_id', 'ticket_type_id', 'attendee_name', 'qr_code', 'status', 'checked_in_at'
    ];

    public function ticket_types(){
        return $this->hasMany(TicketType::class, 'ticket_type_id', 'id');
    }
}
