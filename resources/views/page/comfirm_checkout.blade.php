<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventHub - Đặt vé thành công</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-primary-600" onclick="goToHome()" style="cursor: pointer;">EventHub</h1>
                    </div>
                </div>
                
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="index.html" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">Trang chủ</a>
                        <a href="#" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">Đơn hàng của tôi</a>
                        <span class="text-gray-900 font-medium px-3 py-2 rounded-md text-sm">Xác nhận đơn hàng</span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Progress Bar -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-center space-x-8">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-green-600">Giỏ hàng</span>
                </div>
                <div class="w-16 h-px bg-green-500"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-green-600">Thanh toán</span>
                </div>
                <div class="w-16 h-px bg-green-500"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-green-600">Hoàn thành</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Hero -->
    <section class="bg-gradient-to-br from-green-50 to-blue-50 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                <i class="fas fa-check text-3xl text-green-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Đặt vé thành công!</h1>
            <p class="text-xl text-gray-600 mb-2">Cảm ơn bạn đã tin tưởng EventHub</p>
            <p class="text-gray-500">Vé của bạn đã được gửi qua email và tin nhắn SMS</p>
        </div>
    </section>

    <!-- Order Details -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md">
                    <!-- Order Header -->
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Chi tiết đơn hàng</h2>
                                <p class="text-gray-600">Mã đơn hàng: <span id="orderId" class="font-mono font-medium text-primary-600">EVT123456789</span></p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-2"></i>Đã thanh toán
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin khách hàng</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Họ và tên</p>
                                <p id="customerName" class="font-medium text-gray-900">Nguyễn Văn A</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Số điện thoại</p>
                                <p id="customerPhone" class="font-medium text-gray-900">0901234567</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p id="customerEmail" class="font-medium text-gray-900">example@email.com</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Cách nhận vé</p>
                                <p id="deliveryMethod" class="font-medium text-gray-900">Gửi qua Email</p>
                            </div>
                        </div>
                    </div>

                    <!-- Event Tickets -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Vé sự kiện</h3>
                        <div id="ticketsList" class="space-y-6">
                            <!-- Tickets will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="bg-white rounded-xl shadow-md mt-8">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Bước tiếp theo</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Kiểm tra email</h4>
                                    <p class="text-gray-600 text-sm">Vé điện tử đã được gửi đến email của bạn. Vui lòng kiểm tra cả hộp thư spam.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-mobile-alt text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Lưu vé trên điện thoại</h4>
                                    <p class="text-gray-600 text-sm">Tải vé về máy hoặc chụp màn hình để dễ dàng xuất trình tại cổng vào.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-calendar-check text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Chuẩn bị tham dự</h4>
                                    <p class="text-gray-600 text-sm">Đến đúng giờ và mang theo giấy tờ tùy thân để xác minh vé.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Payment Summary -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tóm tắt thanh toán</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tạm tính</span>
                            <span id="orderSubtotal" class="font-medium">0₫</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phí xử lý</span>
                            <span id="orderProcessingFee" class="font-medium">25,000₫</span>
                        </div>
                        <div id="orderDiscountRow" class="hidden flex justify-between text-green-600">
                            <span>Giảm giá</span>
                            <span id="orderDiscount">0₫</span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Tổng đã thanh toán</span>
                                <span id="orderTotal" class="text-xl font-bold text-primary-600">0₫</span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500 mt-2">
                            Thanh toán qua: <span id="paymentMethod" class="font-medium">VNPay</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            Thời gian: <span id="paymentTime" class="font-medium">--</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Hành động</h3>
                    <div class="space-y-3">
                        <button onclick="downloadTicket()" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                            <i class="fas fa-download mr-2"></i>Tải vé về
                        </button>
                        <button onclick="sendEmailAgain()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                            <i class="fas fa-paper-plane mr-2"></i>Gửi lại email
                        </button>
                        <button onclick="addToCalendar()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                            <i class="fas fa-calendar-plus mr-2"></i>Thêm vào lịch
                        </button>
                    </div>
                </div>

                <!-- Support -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Cần hỗ trợ?</h3>
                    <div class="space-y-3">
                        <a href="tel:19001000" class="flex items-center text-primary-600 hover:text-primary-700">
                            <i class="fas fa-phone mr-3"></i>
                            <span>Hotline: 1900 1000</span>
                        </a>
                        <a href="mailto:support@eventhub.vn" class="flex items-center text-primary-600 hover:text-primary-700">
                            <i class="fas fa-envelope mr-3"></i>
                            <span>Email: support@eventhub.vn</span>
                        </a>
                        <a href="#" class="flex items-center text-primary-600 hover:text-primary-700">
                            <i class="fas fa-comments mr-3"></i>
                            <span>Chat trực tuyến</span>
                        </a>
                    </div>
                    
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Đội ngũ hỗ trợ làm việc 24/7 để giải đáp mọi thắc mắc của bạn.
                        </p>
                    </div>
                </div>

                <!-- Continue Shopping -->
                <div class="bg-gradient-to-r from-primary-600 to-purple-600 rounded-xl p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">Khám phá thêm sự kiện</h3>
                    <p class="text-sm opacity-90 mb-4">Tìm hiểu các sự kiện thú vị khác đang diễn ra</p>
                    <button onclick="goToHome()" class="bg-white text-primary-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                        Xem sự kiện khác
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4">EventHub</h3>
                    <p class="text-gray-400 mb-4">Nền tảng bán vé sự kiện hàng đầu Việt Nam</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Hỗ trợ</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Câu hỏi thường gặp</a></li>
                        <li><a href="#" class="hover:text-white">Chính sách hoàn tiền</a></li>
                        <li><a href="#" class="hover:text-white">Hướng dẫn sử dụng vé</a></li>
                        <li><a href="#" class="hover:text-white">Liên hệ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Chính sách</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Điều khoản sử dụng</a></li>
                        <li><a href="#" class="hover:text-white">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-white">Chính sách cookie</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kết nối với chúng tôi</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone mr-2"></i>1900 1000</li>
                        <li><i class="fas fa-envelope mr-2"></i>support@eventhub.vn</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Hà Nội, Việt Nam</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 EventHub. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navigation functions
        function goToHome() {
            window.location.href = 'index.html';
        }

        // Get order info from localStorage
        function getOrderInfo() {
            try {
                return JSON.parse(localStorage.getItem('eventHub_lastOrder') || '{}');
            } catch {
                // Return demo data if no order found
                return {
                    orderId: 'EVT' + Date.now(),
                    date: new Date().toLocaleDateString('vi-VN'),
                    customerInfo: {
                        fullName: 'Nguyễn Văn Demo',
                        phone: '0901234567',
                        email: 'demo@example.com',
                        delivery: 'email'
                    },
                    paymentMethod: 'vnpay',
                    cart: [
                        {
                            eventId: 'acoustic-night',
                            eventTitle: 'Đêm nhạc Acoustic',
                            ticketType: 'Vé thường',
                            price: 200000,
                            quantity: 2,
                            date: '15/10/2024',
                            location: 'Nhà hát Lớn Hà Nội',
                            image: 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'
                        }
                    ],
                    totals: {
                        subtotal: 400000,
                        processingFee: 25000,
                        discount: 0,
                        total: 425000
                    }
                };
            }
        }

        // Load order details
        function loadOrderDetails() {
            const orderInfo = getOrderInfo();
            
            // Update order header
            document.getElementById('orderId').textContent = orderInfo.orderId;

            // Update customer info
            document.getElementById('customerName').textContent = orderInfo.customerInfo.fullName;
            document.getElementById('customerPhone').textContent = orderInfo.customerInfo.phone;
            document.getElementById('customerEmail').textContent = orderInfo.customerInfo.email;
            document.getElementById('deliveryMethod').textContent = 
                orderInfo.customerInfo.delivery === 'email' ? 'Gửi qua Email' : 'Gửi SMS';

            // Update payment info
            const paymentMethods = {
                'vnpay': 'VNPay',
                'momo': 'Ví MoMo',
                'credit-card': 'Thẻ tín dụng',
                'bank-transfer': 'Chuyển khoản ngân hàng'
            };
            document.getElementById('paymentMethod').textContent = paymentMethods[orderInfo.paymentMethod];
            document.getElementById('paymentTime').textContent = orderInfo.date + ' ' + new Date().toLocaleTimeString('vi-VN');

            // Update tickets list
            const ticketsList = document.getElementById('ticketsList');
            ticketsList.innerHTML = orderInfo.cart.map(item => `
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-shrink-0">
                            <img src="${item.image}" alt="${item.eventTitle}" class="w-24 h-24 object-cover rounded-lg">
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-lg font-bold text-gray-900">${item.eventTitle}</h4>
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    Đã xác nhận
                                </span>
                            </div>
                            <p class="text-primary-600 font-medium mb-2">${item.ticketType}</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-gray-600 mb-3">
                                <div>
                                    <i class="fas fa-calendar mr-1"></i>
                                    ${item.date}
                                </div>
                                <div>
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    ${item.location}
                                </div>
                                <div>
                                    <i class="fas fa-ticket-alt mr-1"></i>
                                    ${item.quantity} vé
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">
                                    ${item.price.toLocaleString()}₫ x ${item.quantity}
                                </span>
                                <button onclick="generateQRCode('${item.eventId}', '${item.ticketType}')" 
                                        class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                                    <i class="fas fa-qrcode mr-1"></i>Xem mã QR
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ticket Numbers -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600 mb-2">Mã vé:</p>
                        <div class="flex flex-wrap gap-2">
                            ${Array.from({length: item.quantity}, (_, i) => 
                                `<span class="bg-gray-100 px-3 py-1 rounded text-sm font-mono">${orderInfo.orderId}-${String(i + 1).padStart(3, '0')}</span>`
                            ).join('')}
                        </div>
                    </div>
                </div>
            `).join('');

            // Update payment summary
            document.getElementById('orderSubtotal').textContent = orderInfo.totals.subtotal.toLocaleString() + '₫';
            document.getElementById('orderProcessingFee').textContent = orderInfo.totals.processingFee.toLocaleString() + '₫';
            document.getElementById('orderTotal').textContent = orderInfo.totals.total.toLocaleString() + '₫';

            if (orderInfo.totals.discount > 0) {
                document.getElementById('orderDiscountRow').classList.remove('hidden');
                document.getElementById('orderDiscount').textContent = '-' + orderInfo.totals.discount.toLocaleString() + '₫';
            }
        }

        // Download ticket
        function downloadTicket() {
            const orderInfo = getOrderInfo();
            
            const receiptContent = `
EVENTHUB - VÉ SỰ KIỆN ĐIỆN TỬ
=============================

Mã đơn hàng: ${orderInfo.orderId}
Ngày đặt: ${orderInfo.date}
Trạng thái: ĐÃ THANH TOÁN

THÔNG TIN KHÁCH HÀNG:
Họ tên: ${orderInfo.customerInfo.fullName}
Email: ${orderInfo.customerInfo.email}
Điện thoại: ${orderInfo.customerInfo.phone}

CHI TIẾT VÉ:
${orderInfo.cart.map((item, index) => `
Sự kiện: ${item.eventTitle}
Loại vé: ${item.ticketType}
Số lượng: ${item.quantity}
Giá: ${item.price.toLocaleString()}₫/vé
Tổng: ${(item.price * item.quantity).toLocaleString()}₫
Ngày diễn ra: ${item.date}
Địa điểm: ${item.location}
Mã vé: ${Array.from({length: item.quantity}, (_, i) => 
    `${orderInfo.orderId}-${String(i + 1).padStart(3, '0')}`).join(', ')}
`).join('\n')}

TỔNG KẾT:
Tạm tính: ${orderInfo.totals.subtotal.toLocaleString()}₫
Phí xử lý: ${orderInfo.totals.processingFee.toLocaleString()}₫
${orderInfo.totals.discount > 0 ? `Giảm giá: -${orderInfo.totals.discount.toLocaleString()}₫` : ''}
TỔNG CỘNG: ${orderInfo.totals.total.toLocaleString()}₫

Phương thức thanh toán: ${document.getElementById('paymentMethod').textContent}

HƯỚNG DẪN SỬ DỤNG VÉ:
1. Mang vé điện tử này (có thể in ra hoặc hiển thị trên điện thoại)
2. Xuất trình cùng giấy tờ tùy thân tại cổng vào
3. Đến đúng giờ theo lịch trình sự kiện

LƯU Ý:
- Vé chỉ có giá trị sử dụng một lần
- Không được bán lại vé với mục đích thương mại
- Liên hệ hotline 1900 1000 nếu cần hỗ trợ

Cảm ơn bạn đã tin tưởng EventHub!
Chúc bạn có những trải nghiệm tuyệt vời tại sự kiện!
            `;

            // Create and download file
            const blob = new Blob([receiptContent], { type: 'text/plain;charset=utf-8' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `EventHub_Tickets_${orderInfo.orderId}.txt`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            showNotification('Đã tải vé thành công!', 'success');
        }

        // Send email again
        function sendEmailAgain() {
            showNotification('Đang gửi lại email...', 'info');
            
            // Simulate email sending
            setTimeout(() => {
                showNotification('Đã gửi lại email thành công! Vui lòng kiểm tra hộp thư.', 'success');
            }, 2000);
        }

        // Add to calendar
        function addToCalendar() {
            const orderInfo = getOrderInfo();
            const firstEvent = orderInfo.cart[0];
            
            if (!firstEvent) {
                showNotification('Không tìm thấy thông tin sự kiện!', 'error');
                return;
            }

            // Create calendar event
            const eventDate = firstEvent.date.split('/');
            const startDate = new Date(2024, parseInt(eventDate[1]) - 1, parseInt(eventDate[0]), 19, 0);
            const endDate = new Date(startDate.getTime() + 3 * 60 * 60 * 1000); // 3 hours later

            const calendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(firstEvent.eventTitle)}&dates=${startDate.toISOString().replace(/[-:]/g, '').split('.')[0]}Z/${endDate.toISOString().replace(/[-:]/g, '').split('.')[0]}Z&details=${encodeURIComponent('Vé: ' + firstEvent.ticketType + '\nMã vé: ' + orderInfo.orderId + '\nLiên hệ: support@eventhub.vn')}&location=${encodeURIComponent(firstEvent.location)}`;
            
            window.open(calendarUrl, '_blank');
            showNotification('Đã mở lịch Google Calendar!', 'success');
        }

        // Generate QR code (demo)
        function generateQRCode(eventId, ticketType) {
            const orderInfo = getOrderInfo();
            const qrData = `${orderInfo.orderId}-${eventId}-${ticketType}`;
            
            // Create simple QR code modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center px-4';
            modal.innerHTML = `
                <div class="bg-white rounded-xl max-w-sm w-full p-6 text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Mã QR vé của bạn</h3>
                    <div class="bg-gray-200 w-48 h-48 mx-auto mb-4 flex items-center justify-center rounded-lg">
                        <div class="text-center">
                            <i class="fas fa-qrcode text-4xl text-gray-500 mb-2"></i>
                            <p class="text-sm text-gray-600">QR Code</p>
                            <p class="text-xs text-gray-500 mt-2 break-all">${qrData}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">Xuất trình mã này tại cổng vào sự kiện</p>
                    <button onclick="this.closest('.fixed').remove()" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-semibold">
                        Đóng
                    </button>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Auto close after 10 seconds
            setTimeout(() => {
                if (document.body.contains(modal)) {
                    document.body.removeChild(modal);
                }
            }, 10000);
        }

        // Notification system
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 
                           type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg text-white font-semibold transform translate-x-full transition-transform duration-300 ${bgColor}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Hide and remove notification
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', () => {
            loadOrderDetails();
            
            // Add confetti effect for success
            setTimeout(() => {
                createConfetti();
            }, 500);
        });

        // Confetti animation
        function createConfetti() {
            const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57', '#ff9ff3', '#54a0ff'];
            
            for (let i = 0; i < 50; i++) {
                createConfettiPiece(colors[Math.floor(Math.random() * colors.length)]);
            }
        }

        function createConfettiPiece(color) {
            const confetti = document.createElement('div');
            confetti.style.cssText = `
                position: fixed;
                width: 10px;
                height: 10px;
                background: ${color};
                top: -10px;
                left: ${Math.random() * 100}vw;
                z-index: 1000;
                pointer-events: none;
                border-radius: 2px;
            `;
            
            document.body.appendChild(confetti);
            
            const animation = confetti.animate([
                { transform: 'translateY(-10px) rotateZ(0deg)', opacity: 1 },
                { transform: `translateY(100vh) rotateZ(${Math.random() * 360}deg)`, opacity: 0 }
            ], {
                duration: Math.random() * 3000 + 2000,
                easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
            });
            
            animation.addEventListener('finish', () => {
                if (document.body.contains(confetti)) {
                    document.body.removeChild(confetti);
                }
            });
        }
    </script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Animations */
        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0, -30px, 0);
            }
            70% {
                transform: translate3d(0, -15px, 0);
            }
            90% {
                transform: translate3d(0, -4px, 0);
            }
        }

        .animate-bounce {
            animation: bounce 2s infinite;
        }
    </style>
</body>
</html>