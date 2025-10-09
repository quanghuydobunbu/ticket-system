<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $eventService;
    public function __construct(EventService $eventService){
        $this->eventService = $eventService;
    }
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status')
        ];

        $events = $this->eventService->getEventsWithFilters($filters, 6);
        $events->appends($request->query());
        // $events = $this->eventService->getAllEvent();
        return view('admin.event.index')->with('events', $events);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizers = $this->eventService->getAllOrganizer();
        $categories = $this->eventService->getAllCategory();
        $venues = $this->eventService->getAllVenue();
        return view('admin.event.create')->with('categories', $categories)->with('organizers', $organizers)->with('venues', $venues);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organizer_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'venue_id' => 'nullable|exists:venues,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:events,slug',
            'description' => 'nullable|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after:start_datetime',
            'registration_end' => 'nullable|date|before:start_datetime',
            'max_attendees' => 'nullable|integer|min:1',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required',
            'is_featured' => 'boolean',
            'is_free' => 'boolean',
        ]);
        try {
            $this->eventService->createEvent(array_merge($validated, [
                'featured_image' => $request->file('featured_image')
            ]));
            return redirect()->route('events.index');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = $this->eventService->findEvent($id);
        
        if($event){
            return view('admin.event.detail')->with('event', $event);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = $this->eventService->findEvent($id);
        $organizers = $this->eventService->getAllOrganizer();
        $categories = $this->eventService->getAllCategory();
        $venues = $this->eventService->getAllVenue();
        return view('admin.event.edit')->with('event', $event)->with('organizers', $organizers)->with('categories', $categories)
        ->with('venues', $venues);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = $this->eventService->findEvent($id);
        if(!$event){
            return redirect()->route('events.index')->with('error', 'Không tìm thấy sự kiện nào');
        }
        $validated = $request->validate([
            'organizer_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'venue_id' => 'nullable|exists:venues,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string',
            'description' => 'nullable|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after:start_datetime',
            'registration_end' => 'nullable|date|before:start_datetime',
            'max_attendees' => 'nullable|integer|min:1',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required',
            'is_featured' => 'boolean',
            'is_free' => 'boolean',
        ]);

        try{
            $this->eventService->updateEvent($event, array_merge($validated, [
                'featured_image' => $request->file('featured_image')
            ]));
            return redirect()->route('events.index');
        }catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = $this->eventService->deleteEvent($id);
        if($event){
            return redirect()->route('events.index');
        }
    }
}
