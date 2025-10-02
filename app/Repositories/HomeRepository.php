<?php
namespace App\Repositories;

use App\Models\Category;
use App\Models\Event;

interface HomeRepositoryInterface{
    public function getAllEvent();
    public function searchEvent($title);

    public function getCategories();
}

class HomeRepository implements HomeRepositoryInterface{
    public function getAllEvent(){
        return Event::with(['category', 'venue'])
            ->where('status', 'published')
            ->orderBy('start_datetime', 'asc')
            ->get();
    }

    public function searchEvent($title){
        return Event::with(['category', 'venue'])
            ->where('status', 'published')
            ->where('title', 'LIKE', '%' . $title . '%')
            ->orderBy('start_datetime', 'asc')
            ->paginate(10);
    }

    public function getCategories(){
        return Category::where('is_active', '=', 1)->get();
    }
}