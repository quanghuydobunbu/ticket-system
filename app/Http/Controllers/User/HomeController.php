<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Event;
use App\Models\Ticket;
use App\Services\HomeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function my_ticket(){
        $user_id = Auth::id();
        $my_ticket = Booking::where('user_id', '=', $user_id)->get();
        return view('page.ticket')->with('my_ticket', $my_ticket);
    }
}
