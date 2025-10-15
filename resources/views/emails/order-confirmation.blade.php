<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4CAF50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .ticket-card { 
            background: white; 
            border: 2px solid #ddd; 
            border-radius: 8px; 
            padding: 15px; 
            margin: 15px 0;
            text-align: center;
        }
        .qr-code { margin: 15px 0; }
        .qr-code img { max-width: 200px; height: auto; }
        .ticket-code { 
            font-size: 18px; 
            font-weight: bold; 
            color: #4CAF50;
            margin: 10px 0;
        }
        .divider { border-top: 2px dashed #ddd; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Xác nhận đơn hàng</h1>
        </div>
        <div class="content">
            <p>Xin chào {{ $order['customerInfo']['fullName'] ?? '' }},</p>
            <p>Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được xác nhận.</p>
            
            <h3>Thông tin đơn hàng:</h3>
            <p><strong>Mã đơn:</strong> {{ $order['orderId'] ?? '' }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order['amount']) ?? '' }} VNĐ</p>

            <div class="divider"></div>

            <h3>Vé của bạn:</h3>
            @if(isset($order['tickets']) && count($order['tickets']) > 0)
                @foreach($order['tickets'] as $index => $ticket)
                    <div class="ticket-card">
                        <h4>Vé #{{ $index + 1 }}</h4>
                        <div class="ticket-code">{{ $ticket['ticket_code'] }}</div>
                        <p><strong>Người tham dự:</strong> {{ $ticket['attendee_name'] }}</p>
                        @if(isset($ticket['ticket_type']))
                            <p><strong>Loại vé:</strong> {{ $ticket['ticket_type'] }}</p>
                        @endif
                        <div class="qr-code">
                             {!! QrCode::size(250)->generate($ticket['ticket_code']) !!}
                        </div>
                        <p style="font-size: 12px; color: #666;">
                            Vui lòng xuất trình mã QR này tại cổng vào
                        </p>
                    </div>
                @endforeach
            @endif

            <div class="divider"></div>

            <p style="color: #666; font-size: 14px;">
                <strong>Lưu ý:</strong> Vui lòng giữ email này để check-in tại sự kiện.
            </p>
        </div>
    </div>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</body>
</html>