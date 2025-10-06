<?php
namespace App\Services;

use App\Models\Booking;
use App\Repositories\BookingRepositoryInterface;

class BookingService{
    protected $bookingRepository;
    public function __construct(BookingRepositoryInterface $bookingRepository){
        $this->bookingRepository = $bookingRepository;
    }
    public function getAllBooking(){
        return $this->bookingRepository->getAllBooking();
    }
    public function getFilterBooking(array $filters, int $perPage = 10){
        return $this->bookingRepository->getBookingsWithFilters($filters, $perPage);
    }
    public function findById($id){
        return $this->bookingRepository->findById($id);
    }
    public function deleteBooking($id){
        $booking = $this->bookingRepository->findById($id);
        return $this->bookingRepository->deleteBooking($booking);
    }
}