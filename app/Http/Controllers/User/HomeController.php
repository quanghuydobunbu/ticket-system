<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Services\HomeServices;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $homeService;
    public function __construct(HomeServices $homeServices){
        $this->homeService = $homeServices;
    }
    public function index()
    {
        $events = $this->homeService->getAllEvent();
        return view('page.home', compact('events'));
    }

    public function cart()
    {
        $events = $this->homeService->getAllEvent();
        return view('page.cart', compact('events'));
    }

    // public function event(){
    //     $events = $this->homeService->getAllEvent();
    //     return view('page.event', compact('events'));
    // }

    public function search_event(Request $request){
        $title = $request->query('search-event');
        $categories = $this->homeService->geCategory();
        $events = $this->homeService->searchEvent($title);
        return view('page.event', compact('events', 'categories'));
    }
}
