<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $casts = [
        'status' => \App\Enums\EventStatus::class,
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime', 
        'registration_end' => 'datetime',
        'is_featured' => 'boolean',
        'is_free' => 'boolean',
    ];
    protected $fillable = [
        'organizer_id', 'category_id', 'venue_id', 'title', 'slug', 
        'description', 'start_datetime', 'end_datetime', 'registration_end', 
        'max_attendees', 'current_attendees', 'featured_image', 'status', 
        'is_featured', 'is_free'
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'organizer_id', 'id');
    }

    public function venue(){
        return $this->belongsTo(Venue::class, 'venue_id', 'id');
    }

    public function ticketTypes(){
        return $this->hasMany(TicketType::class, 'event_id', 'id');
    }
}
