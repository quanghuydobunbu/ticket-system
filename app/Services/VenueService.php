<?php

namespace App\Services;

use App\Models\Venue;
use App\Repositories\VenueRepositoryInterface;
use Illuminate\Support\Facades\Request;

class VenueService{
    protected $venueRepository;
    public function __construct(VenueRepositoryInterface $venue_repository){
        $this->venueRepository = $venue_repository;
    }

    public function getFilterVenue(array $filters, int $perPage = 4){
        return $this->venueRepository->getAllWithFilters($filters, $perPage);
    }
    public function createVenue(array $data){
        $venueData = [
            'name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city'],
            'capacity' => $data['capacity'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ];
        if(isset($data['is_active'])){
            $venueData['is_active'] = $data['is_active'] == 1 ? 1 : 0; 
        }
        return $this->venueRepository->create($venueData);
    }

    public function deleteVenue($id){
        return $this->venueRepository->deleteVenue($id);
    }

    public function updateVenue(Venue $venue, array $data){
        $dataUpdate = [
            'name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city'],
            'capacity' => $data['capacity'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ];
        if(isset($data['is_active'])){
            $dataUpdate['is_active'] = $data['is_active'] == 1 ? 1 : 0; 
        }
        return $this->venueRepository->update($venue, $dataUpdate);
    }

    public function findById($id){
        return $this->venueRepository->findById($id);
    }
}