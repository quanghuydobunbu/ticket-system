<?php
namespace App\Services;

use App\Models\Event;
use App\Repositories\HomeRepositoryInterface;

class HomeServices{
    protected $homeRepository;
    public function __construct(HomeRepositoryInterface $home_repository){
        $this->homeRepository = $home_repository;
    }
    public function getAllEvent(){
        return $this->homeRepository->getAllEvent();
    }
    public function searchEvent($title){  
        return $this->homeRepository->searchEvent($title);
    }
    public function geCategory(){
       return $this->homeRepository->getCategories();
    }
}