<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    protected $ticketService;
    public function __construct(TicketService $ticketService){
        $this->ticketService = $ticketService;
    }
    public function index()
    {
        $ticketTypes = $this->ticketService->getTicketType();
        $tickets = Ticket::paginate(10);

        return view('admin.ticket.index')
        ->with('ticketTypes', $ticketTypes)
        ->with('tickets', $tickets);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ticketTypes = $this->ticketService->getTicketType();
        $bookings = Booking::all();

        return view('admin.ticket.create')->with('ticketTypes', $ticketTypes)
        ->with('bookings', $bookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = $this->ticketService->findById($id);
        return view('admin.ticket.detail')->with('ticket', $ticket);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = $this->ticketService->findById($id);
        $ticketTypes = $this->ticketService->getTicketType();
        return view('admin.ticket.edit')->with('ticketTypes', $ticketTypes)->with('ticket', $ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validated = $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'status' => 'required|in:active,used,cancelled',
            'attendee_name' => 'nullable|string|max:255',
            'reset_checkin' => 'nullable|boolean'
        ], [
            'ticket_type_id.required' => 'Vui lòng chọn loại vé',
            'ticket_type_id.exists' => 'Loại vé không hợp lệ',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
            'attendee_name.max' => 'Tên người tham dự không được vượt quá 255 ký tự'
        ]);

        DB::beginTransaction();
        
        try {
            $ticket = Ticket::findOrFail($id);
            
            $ticket->ticket_type_id = $validated['ticket_type_id'];
            $ticket->status = $validated['status'];
            $ticket->attendee_name = $validated['attendee_name'];
            
            if ($request->has('reset_checkin') && $request->reset_checkin == 1) {
                $ticket->checked_in_at = null;
                
                if ($ticket->status === 'used') {
                    $ticket->status = 'active';
                }
            }
            if ($validated['status'] === 'used' && !$ticket->checked_in_at) {
                $ticket->checked_in_at = now();
            }
            
            if (in_array($validated['status'], ['cancelled'])) {
                $ticket->checked_in_at = null;
            }
            
            $ticket->save();
            
            DB::commit();
            
            Log::info('Ticket updated successfully', [
                'ticket_id' => $ticket->id,
                'ticket_code' => $ticket->ticket_code,
                'updated_by' => Auth::id()
            ]);
            
            return redirect()->route('tickets.show', $ticket->id)
                ->with('success', 'Cập nhật thông tin vé thành công!');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error updating ticket: ' . $e->getMessage(), [
                'ticket_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật vé. Vui lòng thử lại.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = $this->ticketService->deleteTicket($id);
        if($ticket){
            return redirect()->route('tickets.index');
        }
    }

    public function checkin($id)
    {
        $ticket = Ticket::findOrFail($id);
        if ($ticket->checked_in) {
            return redirect()->back()->with('error', 'Vé này đã được check-in trước đó');
        }
        
        $ticket->update([
            'status' => 'used',
            'checked_in' => true,
            'checked_in_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'Check-in thành công cho vé ' . $ticket->ticket_code);
    }
}
