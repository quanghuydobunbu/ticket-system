<?php
namespace App\Repositories;

use App\Models\Venue;
use GuzzleHttp\Promise\Create;

interface VenueRepositoryInterface{
    public function create(array $data);
    public function update(Venue $venue, array $data);
    public function deleteVenue($id);
    public function findById($id);
    public function getAllWithFilters(array $filters = [], int $perPage = 6);
}

class VenueRepository implements VenueRepositoryInterface{
    public function update(Venue $venue, array $data){
        $venue->update($data);
        return $venue->fresh();
    }
    public function deleteVenue($id){
        $venue = $this->findById($id);
        return $venue->delete();
    }
    public function findById($id){
        return Venue::findOrFail($id);
    }

    public function getAllWithFilters(array $filters = [], int $perPage = 6){
        $query = Venue::with('events');
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('is_active', $filters['status']);
        }
        return $query->paginate($perPage);
    }

    public function create(array $data){
        return Venue::create($data);
    }
}