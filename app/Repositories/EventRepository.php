<?php
namespace App\Repositories;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use App\Models\Venue;

interface EventRepositoryInterface{
    public function create(array $data);
    public function update(Event $event, array $data);
    public function findById($id);
    public function getAllEvent();
    public function getAllCategory();
    public function getAllOrganizer();
    public function getAllVenue();
    public function delete(Event $event);
    public function getAllWithFilters(array $filters = [], int $perPage = 4);

}
class EventRepository implements EventRepositoryInterface{
    public function create(array $data){
        return Event::create($data);
    }
    public function update(Event $event, array $data){
        $event->update($data);
        return $event->fresh();
    }
    public function findById($id){
        return Event::findOrFail($id);
    }
    public function getAllEvent(){
        return Event::all();
    }

    public function getAllCategory(){
        return Category::all();
    }
    public function getAllVenue(){
        return Venue::all();
    }
    public function getAllOrganizer(){
        return User::with(['role.role'])
            ->whereHas('role.role', function($query) {
                $query->where('name', 'Organizer');
            })->get();
    }

    public function delete(Event $event){
        return $event->delete();
    }

    public function getAllWithFilters(array $filters = [], int $perPage = 4)
    {
        $query = Event::with('category');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }
}