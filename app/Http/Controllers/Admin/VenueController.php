<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Services\VenueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VenueController extends Controller
{
    protected $venueService;
    public function __construct(VenueService $venueService){
        $this->venueService = $venueService;
    }
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status')
        ];

        $venues = $this->venueService->getFilterVenue($filters, 4);
        $venues->appends($request->query());
        return view('admin.venue.index')->with('venues', $venues);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $capacity = Http::get('https://provinces.open-api.vn/api/v1/?depth=1');
        return view('admin.venue.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'capacity' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable',
            'is_active' => 'boolean',
        ]);
        $venue = $this->venueService->createVenue($validated);
        if($venue){
            return redirect()->route('venues.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $venue = Venue::findOrFail($id);
        return view('admin.venue.detail')->with('venue', $venue);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $venue = $this->venueService->findById($id);                                                                    
        return view('admin.venue.edit')->with('venue', $venue);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $venue = $this->venueService->findById($id);
        $validated = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'capacity' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable',
            'is_active' => 'boolean',
        ]);

        try {
            $this->venueService->updateVenue($venue,$validated);
            return redirect()->route('venues.index');
        } catch (\Exception $e) {
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
        $venue = $this->venueService->deleteVenue($id);
        if($venue){
            return redirect()->route('venues.index');
        }
    }
}
