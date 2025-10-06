<?php
namespace App\Services;

use App\Models\Ticket;
use App\Repositories\TicketRepositoryInterface;

class TicketService{
    protected $ticketRepository;
    public function __construct(TicketRepositoryInterface $ticketRepository){
        $this->ticketRepository = $ticketRepository;
    }
    public function findById($id){
        return $this->ticketRepository->findById($id);
    }
    public function getTicketType(){
        return $this->ticketRepository->getAllTicketType();
    }
    public function deleteTicket($id){
        $ticket = $this->ticketRepository->findById($id);
        return $this->ticketRepository->deleteTicket($ticket);
    }
}