<?php
namespace App\Repositories;

use App\Models\Booking;

interface BookingRepositoryInterface{
    public function getAllBooking();
    public function getBookingsWithFilters(array $filters = [], int $perPage = 10);
    public function findById($id);

    public function deleteBooking(Booking $booking);
}

class BookingRepository implements BookingRepositoryInterface{
    public function getAllBooking(){
        return Booking::all();
    }

    public function getBookingsWithFilters(array $filters = [], int $perPage = 10)
    {
        $query = Booking::with('event');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'LIKE', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }
    public function findById($id){
        return Booking::findOrFail($id);
    }
    public function deleteBooking(Booking $booking){
        return $booking->delete();
    }
}