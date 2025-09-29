<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventHub - Thanh toán</title>
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
                        <a href="cart.html" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">Giỏ hàng</a>
                        <span class="text-gray-900 font-medium px-3 py-2 rounded-md text-sm">Thanh toán</span>
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

    <!-- Checkout Progress -->
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
                    <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-medium">2</div>
                    <span class="ml-2 text-sm font-medium text-primary-600">Thanh toán</span>
                </div>
                <div class="w-16 h-px bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                    <span class="ml-2 text-sm text-gray-500">Hoàn thành</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="index.html" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                        <i class="fas fa-home mr-2"></i>Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="cart.html" class="text-sm font-medium text-gray-700 hover:text-primary-600">Giỏ hàng</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">Thanh toán</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Checkout Form -->
            <div class="flex-1">
                <form id="checkoutForm" class="space-y-8">
                    <!-- Customer Information -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Thông tin khách hàng</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên *</label>
                                <input type="text" id="fullName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Nguyễn Văn A">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
                                <input type="tel" id="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="0901234567">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" id="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="example@email.com">
                            </div>
                        </div>

                        <!-- Ticket Delivery Options -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Cách nhận vé</label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="radio" name="delivery" value="email" checked class="text-primary-600 focus:ring-primary-500">
                                    <span class="ml-3">
                                        <span class="font-medium">Gửi qua Email</span>
                                        <span class="block text-sm text-gray-500">Vé điện tử sẽ được gửi qua email</span>
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="delivery" value="sms" class="text-primary-600 focus:ring-primary-500">
                                    <span class="ml-3">
                                        <span class="font-medium">Gửi SMS</span>
                                        <span class="block text-sm text-gray-500">Mã vé sẽ được gửi qua tin nhắn SMS</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Phương thức thanh toán</h2>
                        
                        <div class="space-y-4">
                            <!-- VNPay -->
                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary-500 transition-colors">
                                <input type="radio" name="payment" value="vnpay" checked class="mt-1 text-primary-600 focus:ring-primary-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-900">VNPay</span>
                                        <img src="https://vnpay.vn/s1/statics.vnpay.vn/2023/6/0oxhzjmxbksr1686814746087.png" alt="VNPay" class="h-6">
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Thanh toán qua ví điện tử VNPay</p>
                                </div>
                            </label>

                            <!-- Momo -->
                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary-500 transition-colors">
                                <input type="radio" name="payment" value="momo" class="mt-1 text-primary-600 focus:ring-primary-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-900">Ví MoMo</span>
                                        <img src="https://developers.momo.vn/v3/vi/assets/images/square-logo.png" alt="MoMo" class="h-6">
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Thanh toán qua ví điện tử MoMo</p>
                                </div>
                            </label>

                            <!-- Credit Card -->
                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary-500 transition-colors">
                                <input type="radio" name="payment" value="credit-card" class="mt-1 text-primary-600 focus:ring-primary-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-900">Thẻ tín dụng/ghi nợ</span>
                                        <div class="flex space-x-2">
                                            <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                                            <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Visa, MasterCard, JCB</p>
                                </div>
                            </label>

                            <!-- Bank Transfer -->
                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary-500 transition-colors">
                                <input type="radio" name="payment" value="bank-transfer" class="mt-1 text-primary-600 focus:ring-primary-500">
                                <div class="ml-3 flex-1">
                                    <span class="font-medium text-gray-900">Chuyển khoản ngân hàng</span>
                                    <p class="text-sm text-gray-500 mt-1">Chuyển khoản qua Internet Banking</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Special Notes -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Ghi chú đặc biệt</h2>
                        <textarea id="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Ghi chú thêm về đơn hàng (tùy chọn)"></textarea>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-start">
                            <input type="checkbox" id="acceptTerms" required class="mt-1 text-primary-600 focus:ring-primary-500">
                            <label for="acceptTerms" class="ml-3 text-sm text-gray-700">
                                Tôi đồng ý với <a href="#" class="text-primary-600 hover:underline">Điều khoản sử dụng</a> và 
                                <a href="#" class="text-primary-600 hover:underline">Chính sách bảo mật</a> của EventHub
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="lg:w-96">
                <div class="bg-white rounded-xl shadow-md sticky top-24">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Tóm tắt đơn hàng</h3>
                        
                        <!-- Cart Items Summary -->
                        <div id="orderItems" class="space-y-4 mb-6">
                            <!-- Items will be loaded here -->
                        </div>

                        <!-- Totals -->
                        <div class="border-t pt-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính</span>
                                <span id="subtotal" class="font-medium">0₫</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phí xử lý</span>
                                <span id="processingFee" class="font-medium">25,000₫</span>
                            </div>
                            <div id="discountRow" class="hidden flex justify-between text-green-600">
                                <span>Giảm giá</span>
                                <span id="discountAmount">0₫</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Tổng cộng</span>
                                    <span id="totalAmount" class="text-xl font-bold text-primary-600">0₫</span>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <a href="{{ route('comfirm_checkout') }}">
                            <button class="w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold text-lg transition duration-200 mt-6">
                                <i class="fas fa-lock mr-2"></i>Thanh toán an toàn
                            </button>
                        </a>
                        

                        <!-- Security Notice -->
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Thanh toán được bảo mật</span>
                            </div>
                            <p class="text-xs text-gray-500">Thông tin của bạn được mã hóa và bảo vệ</p>
                        </div>

                        <!-- Support -->
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-500">Cần hỗ trợ?</p>
                            <div class="flex justify-center space-x-4 mt-2">
                                <a href="tel:19001000" class="text-primary-600 hover:underline text-sm">
                                    <i class="fas fa-phone mr-1"></i>1900 1000
                                </a>
                                <a href="mailto:support@eventhub.vn" class="text-primary-600 hover:underline text-sm">
                                    <i class="fas fa-envelope mr-1"></i>Email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-xl max-w-md w-full p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-2xl text-green-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Thanh toán thành công!</h2>
                <p class="text-gray-600 mb-6">Vé của bạn đã được đặt thành công. Thông tin vé sẽ được gửi qua email trong vài phút.</p>
                <div class="space-y-3">
                    <button onclick="goToHome()" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold">
                        Về trang chủ
                    </button>
                    <button onclick="downloadTicket()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold">
                        <i class="fas fa-download mr-2"></i>Tải vé về
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get cart from localStorage
        function getCart() {
            try {
                return JSON.parse(localStorage.getItem('eventHub_cart') || '[]');
            } catch {
                return [];
            }
        }

        // Navigation functions
        function goToHome() {
            window.location.href = 'index.html';
        }

        // Calculate totals
        function calculateTotals() {
            const cart = getCart();
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const processingFee = cart.length > 0 ? 25000 : 0;
            
            // Apply promo code if exists
            let discount = 0;
            try {
                const promo = JSON.parse(localStorage.getItem('eventHub_promo') || '{}');
                if (promo.discount) {
                    if (promo.discount < 1) {
                        discount = subtotal * promo.discount;
                    } else {
                        discount = Math.min(promo.discount, subtotal);
                    }
                }
            } catch {}

            const total = Math.max(0, subtotal + processingFee - discount);

            return { subtotal, processingFee, discount, total };
        }

        // Update order summary
        function updateOrderSummary() {
            const cart = getCart();
            const orderItems = document.getElementById('orderItems');

            // Check if cart is empty
            if (cart.length === 0) {
                window.location.href = 'cart.html';
                return;
            }

            // Render cart items
            orderItems.innerHTML = cart.map(item => `
                <div class="flex items-center space-x-3">
                    <img src="${item.image}" alt="${item.eventTitle}" class="w-12 h-12 object-cover rounded-lg">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">${item.eventTitle}</p>
                        <p class="text-xs text-gray-500">${item.ticketType} x${item.quantity}</p>
                    </div>
                    <span class="text-sm font-medium text-gray-900">${(item.price * item.quantity).toLocaleString()}₫</span>
                </div>
            `).join('');

            // Update totals
            const totals = calculateTotals();
            document.getElementById('subtotal').textContent = totals.subtotal.toLocaleString() + '₫';
            document.getElementById('processingFee').textContent = totals.processingFee.toLocaleString() + '₫';
            document.getElementById('totalAmount').textContent = totals.total.toLocaleString() + '₫';

            // Show discount if applied
            if (totals.discount > 0) {
                document.getElementById('discountRow').classList.remove('hidden');
                document.getElementById('discountAmount').textContent = '-' + totals.discount.toLocaleString() + '₫';
            }
        }

        // Form validation
        function validateForm() {
            const form = document.getElementById('checkoutForm');
            const requiredFields = form.querySelectorAll('[required]');
            
            for (let field of requiredFields) {
                if (!field.value.trim()) {
                    field.focus();
                    showNotification('Vui lòng điền đầy đủ thông tin bắt buộc!', 'error');
                    return false;
                }
            }

            // Validate email
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById('email').focus();
                showNotification('Email không hợp lệ!', 'error');
                return false;
            }

            // Validate phone
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^[0-9]{10,11}$/;
            if (!phoneRegex.test(phone)) {
                document.getElementById('phone').focus();
                showNotification('Số điện thoại không hợp lệ!', 'error');
                return false;
            }

            return true;
        }

        // Process payment
        async function processPayment() {
            if (!validateForm()) {
                return;
            }

            const cart = getCart();
            if (cart.length === 0) {
                showNotification('Giỏ hàng trống!', 'error');
                return;
            }

            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
            button.disabled = true;

            // Collect form data
            const formData = {
                customerInfo: {
                    fullName: document.getElementById('fullName').value,
                    phone: document.getElementById('phone').value,
                    email: document.getElementById('email').value,
                    delivery: document.querySelector('input[name="delivery"]:checked').value
                },
                paymentMethod: document.querySelector('input[name="payment"]:checked').value,
                notes: document.getElementById('notes').value,
                cart: cart,
                totals: calculateTotals()
            };

            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 2000));

                // Simulate different payment methods
                const paymentMethod = formData.paymentMethod;
                let paymentSuccess = true;

                switch (paymentMethod) {
                    case 'vnpay':
                        showNotification('Đang chuyển hướng đến VNPay...', 'info');
                        break;
                    case 'momo':
                        showNotification('Đang chuyển hướng đến MoMo...', 'info');
                        break;
                    case 'credit-card':
                        showNotification('Đang xử lý thẻ tín dụng...', 'info');
                        break;
                    case 'bank-transfer':
                        showNotification('Đang tạo lệnh chuyển khoản...', 'info');
                        break;
                }

                if (paymentSuccess) {
                    // Clear cart after successful payment
                    localStorage.removeItem('eventHub_cart');
                    localStorage.removeItem('eventHub_promo');
                    
                    // Save order info for receipt
                    const orderInfo = {
                        orderId: 'EVT' + Date.now(),
                        date: new Date().toLocaleDateString('vi-VN'),
                        ...formData
                    };
                    localStorage.setItem('eventHub_lastOrder', JSON.stringify(orderInfo));

                    // Show success modal
                    document.getElementById('successModal').classList.remove('hidden');
                } else {
                    showNotification('Thanh toán thất bại! Vui lòng thử lại.', 'error');
                }
            } catch (error) {
                showNotification('Có lỗi xảy ra! Vui lòng thử lại.', 'error');
            } finally {
                // Restore button
                button.innerHTML = originalText;
                button.disabled = false;
            }
        }

        // Download ticket (demo)
        function downloadTicket() {
            const orderInfo = JSON.parse(localStorage.getItem('eventHub_lastOrder') || '{}');
            if (!orderInfo.orderId) {
                showNotification('Không tìm thấy thông tin đơn hàng!', 'error');
                return;
            }

            // Create a simple text receipt
            const receiptContent = `
EVENTHUB - VÉ SỰ KIỆN
====================

Mã đơn hàng: ${orderInfo.orderId}
Ngày đặt: ${orderInfo.date}
Khách hàng: ${orderInfo.customerInfo.fullName}
Email: ${orderInfo.customerInfo.email}
Số điện thoại: ${orderInfo.customerInfo.phone}

CHI TIẾT VÉ:
${orderInfo.cart.map(item => `
- ${item.eventTitle}
  Loại vé: ${item.ticketType}
  Số lượng: ${item.quantity}
  Đơn giá: ${item.price.toLocaleString()}₫
  Thành tiền: ${(item.price * item.quantity).toLocaleString()}₫
  Ngày: ${item.date}
  Địa điểm: ${item.location}
`).join('\n')}

TỔNG TIỀN: ${orderInfo.totals.total.toLocaleString()}₫

Cảm ơn bạn đã sử dụng dịch vụ EventHub!
            `;

            // Download as text file
            const blob = new Blob([receiptContent], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `EventHub_Ticket_${orderInfo.orderId}.txt`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            showNotification('Đã tải vé thành công!', 'success');
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

        // Form auto-fill for demo purposes
        function fillDemoData() {
            document.getElementById('fullName').value = 'Nguyễn Văn Demo';
            document.getElementById('phone').value = '0901234567';
            document.getElementById('email').value = 'demo@example.com';
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', () => {
            updateOrderSummary();
            
            // Auto-fill demo data after 1 second for testing
            setTimeout(() => {
                if (document.getElementById('fullName').value === '') {
                    fillDemoData();
                }
            }, 1000);
        });

        // Close success modal when clicking outside
        document.getElementById('successModal').addEventListener('click', (e) => {
            if (e.target.id === 'successModal') {
                goToHome();
            }
        });
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

        /* Form styling */
        input[type="radio"]:checked + span {
            color: #2563eb;
        }

        /* Payment method selection */
        input[type="radio"]:checked + div {
            border-color: #2563eb;
            background-color: #eff6ff;
        }
    </style>
</body>
</html>