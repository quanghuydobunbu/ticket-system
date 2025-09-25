<?php
namespace App\Services;

use App\Repositories\TicketTypeRepositoryInterface;

class TicketTypeService{
    public $ticketTypeRepository;
    public function __construct(TicketTypeRepositoryInterface $ticketTypeRepository){
        $this->ticketTypeRepository = $ticketTypeRepository;
    }
    public function createTicketType(array $data){
        $data_ticket_type = [
            'event_id' => $data['event_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity_total' => $data['quantity_total'],
            'quantity_sold' => $data['quantity_sold'],
            'max_per_order' => $data['max_per_order'],
            'sale_end' => $data['sale_end'],
            'is_active' => $data['is_active'],
        ];
        return $this->ticketTypeRepository->create($data_ticket_type);
    }
    public function updateTicketType($id, array $data){
        $ticket_type = $this->ticketTypeRepository->findById($id);
        $data_update = [
            'event_id' => $data['event_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity_total' => $data['quantity_total'],
            'quantity_sold' => $data['quantity_sold'],
            'max_per_order' => $data['max_per_order'],
            'sale_end' => $data['sale_end'],
            // 'is_active' => $data['is_active'],
        ];
       if(isset($data['is_active'])) {
            $data_update['is_active'] = ($data['is_active'] == 1) ? 1 : 0;
        }
        return $this->ticketTypeRepository->update($ticket_type,$data_update);
    }
    public function deleteTicketType($id){
        $ticket_type = $this->ticketTypeRepository->findById($id);
        return $this->ticketTypeRepository->delete($ticket_type);
    }
    public function getEvent(){
        return $this->ticketTypeRepository->getEvent();
    }

    public function getAllFillter(array $filters, int $perPage = 6){
        return $this->ticketTypeRepository->getAllWithFilters($filters, $perPage);
    }
}