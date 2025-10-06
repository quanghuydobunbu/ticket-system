<!-- resources/views/check-in/scanner.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Check-in Scanner</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        #qr-reader {
            margin: 20px auto;
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Quét QR Code Check-in</h2>
        <div id="qr-reader" style="width: 100%"></div>
        <div id="result"></div>
        <a style="" href="{{ route('tickets.index') }}">
            <button style="padding: 10px 20px; border-radius: 10px; background-color: #155724; color: #fff
            ;">Quay về</button>        
        </a>
    </div>

    <script>
        let isProcessing = false; // Flag để tránh scan nhiều lần

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            
            isProcessing = true;
            
            fetch('/api/check-in', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    qr_code: decodedText
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('result').innerHTML = 
                        '<div class="alert alert-success">✓ Check-in thành công!<br>' + 
                        'Tên: ' + data.ticket.ticket_code + '<br>' +
                        'Thời gian: ' + data.ticket.checked_in_at + '</div>';
                    setTimeout(() => {
                        isProcessing = false;
                        document.getElementById('result').innerHTML = '';
                    }, 7000);
                } else {
                    document.getElementById('result').innerHTML = 
                        '<div class="alert alert-danger">✗ ' + data.message + '</div>';
                }
                
                // Cho phép scan lại sau 2 giây
                setTimeout(() => {
                    isProcessing = false;
                    document.getElementById('result').innerHTML = '';
                }, 2000);
            })
            .catch(error => {
                document.getElementById('result').innerHTML = 
                    '<div class="alert alert-danger">✗ Lỗi kết nối! Vui lòng thử lại.</div>';
                
                setTimeout(() => {
                    isProcessing = false;
                    document.getElementById('result').innerHTML = '';
                }, 2000);
            });
        }

        function onScanFailure(error) {
            // Không làm gì cả, để scanner tiếp tục hoạt động
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            { 
                fps: 10, 
                qrbox: {width: 250, height: 250},
                aspectRatio: 1.0
            },
            false
        );
        
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</body>
</html>