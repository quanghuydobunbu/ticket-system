<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Event;
use App\Models\Ticket;
use App\Services\HomeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $homeService;
    public function __construct(HomeServices $homeServices){
        $this->homeService = $homeServices;
    }
    public function index()
    {
        $events = $this->homeService->getAllEvent();
        return view('page.home', compact('events'));
    }

    public function cart()
    {
        $events = $this->homeService->getAllEvent();
        return view('page.cart', compact('events'));
    }

    // public function event(){
    //     $events = $this->homeService->getAllEvent();
    //     return view('page.event', compact('events'));
    // }

    public function search_event(Request $request){
        $title = $request->query('search-event');
        $categories = $this->homeService->geCategory();
        $events = $this->homeService->searchEvent($title);
        return view('page.event', compact('events', 'categories'));
    }

    public function my_ticket(Request $request){
        $filter = $request->get('filter', 'all');
        
        // Lấy tất cả bookings của user hiện tại với các quan hệ cần thiết
        $query = Booking::with([
            'event:id,title,start_datetime,featured_image',
            'bookingDetails.ticketType:id,name,price',
            'tickets'
        ])
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'desc');

        // Áp dụng filter
        if ($filter === 'upcoming') {
            $query->where('status', '!=', 'cancelled')
                  ->whereHas('event', function($q) {
                      $q->where('start_date', '>=', now()->toDateString());
                  });
        } elseif ($filter === 'past') {
            $query->whereHas('event', function($q) {
                $q->where('start_date', '<', now()->toDateString());
            })->orWhere('status', 'confirmed')
              ->whereHas('tickets', function($q) {
                  $q->where('status', 'used');
              });
        } elseif ($filter === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $bookings = $query->get();

        // Format dữ liệu để truyền vào view
        $tickets = $bookings->map(function($booking) {
            // Safely get first booking detail
            $firstDetail = $booking->bookingDetails->first();
            $ticketType = $firstDetail ? $firstDetail->ticketType : null;
            $totalQuantity = $booking->bookingDetails ? $booking->bookingDetails->sum('quantity') : 0;
            
            // Safely get first ticket
            $firstTicket = $booking->tickets->first();
            
            return [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'eventTitle' => $booking->event ? $booking->event->title : 'N/A',
                'ticketType' => $ticketType ? $ticketType->name : 'N/A',
                'ticketCode' => $firstTicket ? $firstTicket->ticket_code : $booking->booking_code,
                'date' => $booking->event ? $booking->event->start_datetime : null,
                'time' => $booking->event ? $booking->event->start_datetime : null,
                'location' => $booking->event->venue ? $booking->event->venue->name : 'N/A',
                'quantity' => $totalQuantity,
                'price' => $booking->final_amount ?? 0,
                'status' => $this->mapTicketStatus($booking),
                'customerName' => Auth::user()->name ?? 'N/A',
                'image' => ($booking->event && $booking->event->featured_image) ? $booking->event->featured_image : 'default.jpg',
                'attendee_info' => $booking->attendee_info ? json_decode($booking->attendee_info, true) : null,
                'tickets' => $booking->tickets ? $booking->tickets->map(function($ticket) {
                    return [
                        'ticket_code' => $ticket->ticket_code ?? '',
                        'attendee_name' => $ticket->attendee_name ?? '',
                        'qr_code' => $ticket->qr_code ?? '',
                        'status' => $ticket->status ?? 'active',
                        'checked_in_at' => $ticket->checked_in_at ?? null,
                    ];
                }) : collect()
            ];
        });

        return view('page.ticket', compact('tickets', 'filter'));
    }
    public function show($id)
    {
        try {
            $booking = Booking::with([
                'event:id,title,start_datetime,featured_image',
                'event.venue:id,name',
                'bookingDetails.ticketType:id,name,price',
                'tickets'
            ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

            $firstDetail = optional($booking->bookingDetails)->first();
            $ticketType = optional($firstDetail)->ticketType;
            $totalQuantity = optional($booking->bookingDetails)->sum('quantity') ?? 0;

            // Format individual tickets with their QR codes
            $ticketsArray = [];
            if ($booking->tickets) {
                foreach ($booking->tickets as $ticket) {
                    $ticketsArray[] = [
                        'id' => $ticket->id,
                        'ticket_code' => $ticket->ticket_code,
                        'attendee_name' => $ticket->attendee_name ?? 'Chưa có tên',
                        'qr_code' => $ticket->qr_code,
                        'status' => $ticket->status,
                        'checked_in_at' => $ticket->checked_in_at,
                    ];
                }
            }

            // Parse datetime
            $startDatetime = optional($booking->event)->start_datetime;
            $date = $startDatetime ? date('Y-m-d', strtotime($startDatetime)) : null;
            $time = $startDatetime ? date('H:i', strtotime($startDatetime)) : null;

            $response = [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'eventTitle' => optional($booking->event)->title ?? 'N/A',
                'ticketType' => optional($ticketType)->name ?? 'N/A',
                'date' => $date,
                'time' => $time,
                'location' => optional($booking->event->venue)->name ?? 'N/A',
                'quantity' => $totalQuantity,
                'price' => $booking->final_amount ?? 0,
                'status' => $this->mapTicketStatus($booking),
                'customerName' => optional(Auth::user())->name ?? 'N/A',
                'image' => optional($booking->event)->featured_image ?? 'default.jpg',
                'attendee_info' => $booking->attendee_info ? json_decode($booking->attendee_info, true) : null,
                'tickets' => $ticketsArray // Mảng các vé riêng lẻ
            ];

            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('Error in MyTicketsController@show: ' . $e->getMessage());
            return response()->json(['error' => 'Ticket not found'], 404);
        }
    }

    private function mapTicketStatus($booking)
    {
        if (!$booking) {
            return 'pending';
        }

        if ($booking->status === 'cancelled') {
            return 'cancelled';
        }
        
        if ($booking->status === 'confirmed') {
            if ($booking->tickets && $booking->tickets->count() > 0) {
                $hasUsedTicket = $booking->tickets->where('status', 'used')->count() > 0;
                if ($hasUsedTicket) {
                    return 'used';
                }
            }
            return 'confirmed';
        }
        
        return 'pending';
    }

    public function download($id)
    {
        return response()->json([
            'message' => 'Download feature coming soon'
        ]);
    }

    // public function print($id)
    // {
    //     try {
    //         $booking = Booking::with([
    //             'event',
    //             'bookingDetails.ticketType',
    //             'tickets'
    //         ])
    //         ->where('user_id', Auth::id())
    //         ->findOrFail($id);

    //         $firstDetail = optional($booking->bookingDetails)->first();
    //         $ticketType = optional($firstDetail)->ticketType;
    //         $totalQuantity = optional($booking->bookingDetails)->sum('quantity') ?? 0;
    //         $firstTicket = optional($booking->tickets)->first();

    //         $ticket = [
    //             'id' => $booking->id,
    //             'booking_code' => $booking->booking_code,
    //             'eventTitle' => optional($booking->event)->title ?? 'N/A',
    //             'ticketType' => optional($ticketType)->name ?? 'N/A',
    //             'ticketCode' => optional($firstTicket)->ticket_code ?? $booking->booking_code,
    //             'date' => optional($booking->event)->start_date,
    //             'time' => optional($booking->event)->start_time,
    //             'location' => optional($booking->event)->location ?? 'N/A',
    //             'quantity' => $totalQuantity,
    //             'price' => $booking->final_amount ?? 0,
    //             'status' => $this->mapTicketStatus($booking),
    //             'customerName' => optional(Auth::user())->name ?? 'N/A',
    //             'tickets' => $booking->tickets ?? collect()
    //         ];

    //         return view('tickets.print', compact('ticket'));
            
    //     } catch (\Exception $e) {
    //         Log::error('Error in MyTicketsController@print: ' . $e->getMessage());
    //         abort(404);
    //     }
    // }
}
