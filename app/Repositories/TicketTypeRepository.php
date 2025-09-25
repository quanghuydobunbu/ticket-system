<?php
namespace App\Repositories;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;

interface TicketTypeRepositoryInterface{
    public function create(array $data);
    public function update(TicketType $ticketType, array $data);
    public function delete(TicketType $ticket_type);
    public function getEvent();
    public function getAllWithFilters(array $filters = [], int $perPage = 6);
    public function findById($id);
}

class TicketTypeRepository implements TicketTypeRepositoryInterface{
    public function create(array $data){
        return TicketType::create($data);
    }
    public function update(TicketType $ticketType, array $data){
        $ticketType->update($data);
        return $ticketType->fresh();
    }
    public function delete(TicketType $ticket_type){
        return $ticket_type->delete();
    }
    public function getEvent(){
        return Event::all();
    }

    public function getAllWithFilters(array $filters = [], int $perPage = 6){
        $query = TicketType::with('event');
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('is_active', $filters['status']);
        }
        return $query->paginate($perPage);
    }

    public function findById($id){
        return TicketType::findOrFail($id);
    }
}