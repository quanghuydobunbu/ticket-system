<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy tất cả events với relationships cần thiết
        $events = Event::with([
            'category',
            'venue', 
        ])
        ->where('status', 'published') // chỉ lấy events đang active
        ->orderBy('start_datetime', 'asc')
        ->get();

        return view('page.home', compact('events'));
    }

    public function event(){
        $events = Event::with([
            'category',
            'venue', 
        ])
        ->where('status', 'published') // chỉ lấy events đang active
        ->orderBy('start_datetime', 'asc')
        ->paginate(10);

        return view('page.event', compact('events'));
    }

    // API endpoint để lấy chi tiết event (optional - cho AJAX calls)
    public function getEventDetails($id)
    {
        $event = Event::with([
            'category',
            'venue',
            'ticketTypes' => function($query) {
                $query->orderBy('price', 'asc');
            },
            'organizer'
        ])->findOrFail($id);

        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'featured_image' => $event->featured_image,
            'venue' => $event->venue->name ?? '',
            'venue_address' => $event->venue->address ?? '',
            'category' => $event->category->name ?? '',
            'start_datetime' => $event->start_datetime,
            'end_datetime' => $event->end_datetime,
            'registration_end' => $event->registration_end,
            'max_attendees' => $event->max_attendees,
            'current_attendees' => $event->current_attendees,
            'is_free' => $event->is_free,
            'is_featured' => $event->is_featured,
            'organizer' => $event->organizer->name ?? '',
            'tickets' => $event->ticketTypes->map(function($ticket) {
                return [
                    'id' => $ticket->id,
                    'type' => $ticket->name ?? $ticket->type,
                    'price' => $ticket->price,
                    'available' => $ticket->quantity_available ?? $ticket->available_quantity ?? 100,
                    'description' => $ticket->description ?? ''
                ];
            })
        ]);
    }
}
