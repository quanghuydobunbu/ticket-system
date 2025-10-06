<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function scanner()
    {
        return view('admin.check-in.scanner');
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);

        $ticket = Ticket::where('qr_code', $request->qr_code)->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'QR code không hợp lệ!'
            ], 404);
        }

        if ($ticket->status == 'used') {
            return response()->json([
                'success' => false,
                'message' => 'Vé này đã được check-in lúc ' . $ticket->checked_in_at
            ], 400);
        }

        $ticket->status = 'used';
        $ticket->checked_in_at = now();
        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-in thành công!',
            'ticket' => [
                'name' => $ticket->name,
                'code' => $ticket->qr_code,
                'checked_in_at' => $ticket->checked_in_at
            ]
        ]);
    }
}
