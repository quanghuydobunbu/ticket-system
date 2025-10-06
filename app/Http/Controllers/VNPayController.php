<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VNPayController extends Controller
{
    public function createPayment(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9]{10,11}$/',
            'email' => 'required|email',
            'amount' => 'required|numeric|min:10000',
            'cart' => 'required|json',
        ]);

        // Lưu thông tin đơn hàng vào session
        session([
            'order_info' => [
                'fullName' => $request->fullName,
                'phone' => $request->phone,
                'email' => $request->email,
                'delivery' => $request->delivery ?? 'email',
                'notes' => $request->notes,
                'cart' => json_decode($request->cart, true),
                'amount' => $request->amount,
                'created_at' => now(),
            ]
        ]);

        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $vnp_Url = config('services.vnpay.url');
        $vnp_ReturnUrl = config('services.vnpay.return_url');

        // Tạo mã đơn hàng 
        $vnp_TxnRef = 'EVT' . time();
        $vnp_Amount = $request->amount * 100;
        $vnp_OrderInfo = 'Thanh toan ve su kien - ' . $request->fullName;
        $vnp_OrderType = 'billpayment';
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();

        //Dữ liệu gửi đến VNPay
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        // Sắp xếp dữ liệu theo key
        ksort($inputData);
        $query = "";
        $hashdata = "";
        $i = 0;
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        
        // Tạo secure hash
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        session(['vnp_TxnRef' => $vnp_TxnRef]);

        Log::info('VNPay Payment URL Created', [
            'txnRef' => $vnp_TxnRef,
            'amount' => $vnp_Amount,
            'url' => $vnp_Url
        ]);

        return redirect($vnp_Url);
    }

    public function callback(Request $request)
    {
        // Lấy toàn bộ dữ liệu từ VNPay trả về
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = $request->except('vnp_SecureHash');
        
        // Sắp xếp dữ liệu
        ksort($inputData);
        $hashData = "";
        $i = 0;
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            if ($request->vnp_ResponseCode == '00') {
                $orderInfo = session('order_info');
                
                if (!$orderInfo) {
                    Log::error('VNPay Callback: No order info in session');
                    return redirect()->route('payment.failed')
                        ->with('error', 'Không tìm thấy thông tin đơn hàng!');
                }

                try {
                    DB::beginTransaction();

                    $cart = $orderInfo['cart'] ?? [];
                    $totalAmount = $request->vnp_Amount / 100;
                    
                    // Nhóm các vé theo event_id
                    $eventGroups = [];
                    foreach ($cart as $item) {
                        $eventId = $item['event_id'] ?? $item['eventId'] ?? null;
                        if (!isset($eventGroups[$eventId])) {
                            $eventGroups[$eventId] = [
                                'items' => [],
                                'total' => 0
                            ];
                        }
                        
                        $eventGroups[$eventId]['items'][] = $item;
                        $eventGroups[$eventId]['total'] += $item['price'] * $item['quantity'];
                    }

                    foreach ($eventGroups as $eventId => $group) {
                        $booking = Booking::create([
                            'booking_code' => $request->vnp_TxnRef . '-E' . $eventId,
                            'user_id' => Auth::check() ? Auth::id() : null,
                            'event_id' => $eventId,
                            'total_amount' => $group['total'],
                            'final_amount' => $group['total'], 
                            'status' => 'confirmed',
                            'attendee_info' => json_encode([
                                'full_name' => $orderInfo['fullName'] ?? '',
                                'phone' => $orderInfo['phone'] ?? '',
                                'email' => $orderInfo['email'] ?? '',
                                'delivery_method' => $orderInfo['delivery'] ?? 'email',
                                'notes' => $orderInfo['notes'] ?? '',
                                'transaction_no' => $request->vnp_TransactionNo,
                                'bank_code' => $request->vnp_BankCode,
                                'payment_method' => 'vnpay',
                                'payment_date' => date('d/m/Y H:i:s', strtotime($request->vnp_PayDate)),
                            ]),
                            'confirmed_at' => now(),
                        ]);

                        foreach ($group['items'] as $item) {
                            $bookingItem = BookingItem::create([
                                'booking_id' => $booking->id,
                                'ticket_type_id' => $item['ticketId'] ?? $item['ticketId'] ?? null,
                                'quantity' => $item['quantity'],
                                'unit_price' => $item['price'],
                            ]);

                            // Tạo từng vé riêng lẻ với mã QR
                            for ($i = 1; $i <= $item['quantity']; $i++) {
                                $ticketCode = $this->generateTicketCode($booking->booking_code, $i);
                                $qrCode = $this->generateQRCode($ticketCode);

                                Ticket::create([
                                    'ticket_code' => $ticketCode,
                                    'booking_id' => $booking->id,
                                    'ticket_type_id' => $item['ticketId'] ?? $item['ticketId'] ?? null,
                                    'attendee_name' => $orderInfo['fullName'] ?? '',
                                    'qr_code' => $qrCode,
                                    'status' => 'active',
                                ]);
                            }
                        }

                        Log::info('Booking Created Successfully', [
                            'booking_id' => $booking->id,
                            'booking_code' => $booking->booking_code,
                            'event_id' => $eventId,
                            'amount' => $group['total']
                        ]);
                    }
                    DB::commit();
                    $order = [
                        'orderId' => $request->vnp_TxnRef,
                        'amount' => $totalAmount,
                        'transactionNo' => $request->vnp_TransactionNo,
                        'bankCode' => $request->vnp_BankCode,
                        'payDate' => date('d/m/Y H:i:s', strtotime($request->vnp_PayDate)),
                        'customerInfo' => [
                            'fullName' => $orderInfo['fullName'] ?? '',
                            'phone' => $orderInfo['phone'] ?? '',
                            'email' => $orderInfo['email'] ?? '',
                            'delivery' => $orderInfo['delivery'] ?? 'email',
                        ],
                        'cart' => $cart,
                        'notes' => $orderInfo['notes'] ?? '',
                        'status' => 'paid',
                        'paymentMethod' => 'vnpay',
                        'created_at' => now()->format('d/m/Y H:i:s'),
                    ];

                    // TODO: Gửi email xác nhận
                    // Mail::to($order['customerInfo']['email'])->send(new OrderConfirmation($order));

                    session(['completed_order' => $order]);
                    session()->forget(['order_info', 'vnp_TxnRef']);
                    return redirect()->route('payment.success')
                        ->with('success', 'Thanh toán thành công!');

                } catch (\Exception $e) {
                    DB::rollBack();
                    
                    Log::error('Booking Creation Failed', [
                        'error' => $e->getMessage(),
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    return redirect()->route('payment.failed')
                        ->with('error', 'Có lỗi khi lưu đơn hàng: ' . $e->getMessage());
                }
                
            } else {
                $errorMessage = $this->getResponseDescription($request->vnp_ResponseCode);
                return redirect()->route('payment.failed')
                    ->with('error', $errorMessage);
            }
        } else {
            return redirect()->route('payment.failed')
                ->with('error', 'Dữ liệu không hợp lệ!');
        }
    }

    private function getResponseDescription($responseCode)
    {
        $descriptions = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
            '75' => 'Ngân hàng thanh toán đang bảo trì.',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch',
            '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)',
        ];

        return $descriptions[$responseCode] ?? 'Lỗi không xác định';
    }

    // private function generateTicketCode($bookingCode, $sequence)
    // {
    //     return $bookingCode . '-T' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    // }

    private function generateTicketCode($bookingCode, $index)
    {
        return $bookingCode . '-T' . str_pad($index, 3, '0', STR_PAD_LEFT) . '-' . substr(microtime(true) * 10000, -4);
    }

    private function generateQRCode($ticketCode)
    {
        do {
            $qrCode = strtoupper(Str::random(12));
        } while (Ticket::where('qr_code', $qrCode)->exists());
        
        return $qrCode;
    }

    public function success()
    {
        $order = session('completed_order');
        
        if (!$order) {
            return redirect()->route('dashboard');
        }

        return view('checkout.success', compact('order'));
    }

    public function failed()
    {
        return view('checkout.failed');
    }
}
