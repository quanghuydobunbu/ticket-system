<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    protected $bookingService;    
    public function __construct(BookingService $bookingService){
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        if(!HasPermission(Auth::user(), 'bookings.index')) {
            abort(403);
        }
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status')
        ];

        $bookings = $this->bookingService->getFilterBooking($filters, 10);
        $bookings->appends($request->query());
        // $bookings = $this->bookingService->getAllBooking();
        return view('admin.booking.index')->with('bookings', $bookings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = $this->bookingService->findById($id);
        if($booking){
            return view('admin.booking.detail')->with('booking', $booking);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = $this->bookingService->deleteBooking($id);
        if($booking){
            return redirect()->route('bookings.index');
        }
    }
}
