<?php
namespace App\Repositories;

use App\Models\Ticket;
use App\Models\TicketType;

interface TicketRepositoryInterface{
    public function findById($id);
    public function getAllTicketType();
    public function deleteTicket(Ticket $ticket);
}
class TicketRepository implements TicketRepositoryInterface{
    public function findById($id){
        return Ticket::findOrFail($id);
    }
    public function getAllTicketType(){
        return TicketType::all();
    }
    public function deleteTicket(Ticket $ticket){
        return $ticket->delete();
    }
}

