<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use App\Services\TicketTypeService;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $ticketTypeService;
    public function __construct(TicketTypeService $ticketTypeService){
        $this->ticketTypeService = $ticketTypeService;
    }
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status')
        ];

        $events = $this->ticketTypeService->getEvent();
        $ticketTypes = $this->ticketTypeService->getAllFillter($filters, 6);
        $ticketTypes->appends($request->query());
        return view('admin.ticket_type.index')->with('ticketTypes', $ticketTypes)
        ->with('events', $events);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $events = Event::all();
        return view('admin.ticket_type.create')->with('events', $events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'quantity_total' => 'required',
            'quantity_sold' => 'required',
            'max_per_order' => 'required',
            'sale_end' => 'nullable',
            'is_active' => 'required|boolean'
        ]);
        $ticket_type = $this->ticketTypeService->createTicketType($validated);
        if($ticket_type){
            return redirect()->route('ticket-types.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $ticketType = TicketType::findOrFail($id);
        return view('admin.ticket_type.detail')->with('ticketType', $ticketType);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $events = Event::all();
        $ticketType = TicketType::findOrFail($id);
        return view('admin.ticket_type.edit')->with('ticketType', $ticketType)->with('events', $events);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'event_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'quantity_total' => 'required',
            'quantity_sold' => 'required',
            'max_per_order' => 'required',
            'sale_end' => 'nullable',
            'is_active' => 'required|boolean'
        ]);
        if(isset($validated['is_active'] ))
        $ticket_type = $this->ticketTypeService->updateTicketType($id, $validated);
        if($ticket_type){
            return redirect()->route('ticket-types.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket_type = $this->ticketTypeService->deleteTicketType($id);
        if($ticket_type){
            return redirect()->route('ticket-types.index');
        }
    }
}
