<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'user_id',
        'event_id',
        'total_amount',
        'final_amount',
        'status',
        'attendee_info',
        'expires_at',
        'confirmed_at',
    ];
    protected $casts = [
        'attendee_info' => 'array',
        'expires_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
