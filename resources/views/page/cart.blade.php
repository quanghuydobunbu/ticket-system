@extends('layouts.home')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="index.html" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                        <i class="fas fa-home mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Giỏ hàng</span>
                    </div>
                </li>
            </ol>
        </nav>
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1">
                <div class="bg-white rounded-xl shadow-md">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900">Giỏ hàng của bạn</h2>
                        <p class="text-gray-600 mt-1">Kiểm tra lại thông tin trước khi thanh toán</p>
                    </div>

                    <div id="emptyCart" class="p-12 text-center hidden">
                        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Giỏ hàng trống</h3>
                        <p class="text-gray-500 mb-6">Bạn chưa có vé nào trong giỏ hàng</p>
                        <button onclick="goToHome()" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold">
                            <i class="fas fa-arrow-left mr-2"></i>Tiếp tục mua sắm
                        </button>
                    </div>

                    <div id="cartItemsList" class="divide-y divide-gray-200">
                    </div>

                    <div id="continueShopping" class="p-6 border-t border-gray-200 hidden">
                        <button onclick="goToHome()" class="text-primary-600 hover:text-primary-700 font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>Tiếp tục mua sắm
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:w-96">
                <div id="orderSummary" class="bg-white rounded-xl shadow-md sticky top-24 hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Tóm tắt đơn hàng</h3>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính</span>
                                <span id="subtotal" class="font-medium">0₫</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phí xử lý</span>
                                <span id="processingFee" class="font-medium">25,000₫</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Tổng cộng</span>
                                    <span id="totalAmount" class="text-xl font-bold text-primary-600">0₫</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mã giảm giá</label>
                            <div class="flex gap-2">
                                <input type="text" id="promoCode" placeholder="Nhập mã giảm giá" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <button onclick="applyPromoCode()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium">
                                    Áp dụng
                                </button>
                            </div>
                        </div>

                         <a href="{{ route('checkout') }}">
                             <button class="w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold text-lg transition duration-200">
                                 <i class="fas fa-credit-card mr-2"></i>Tiến hành thanh toán
                             </button>
                         </a>

                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                                <span class="text-sm text-gray-600">Thanh toán an toàn & bảo mật</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
        function getCart() {
            try {
                return JSON.parse(localStorage.getItem('eventHub_cart') || '[]');
            } catch {
                return [];
            }
        }

        function saveCart(cart) {
            localStorage.setItem('eventHub_cart', JSON.stringify(cart));
            updateCartDisplay();
        }

        function goToHome() {
            window.location.href = 'index.html';
        }

        function goToCheckout() {
            const cart = getCart();
            if (cart.length === 0) {
                showNotification('Giỏ hàng trống!', 'error');
                return;
            }
            window.location.href = 'checkout.html';
        }

        function updateCartCount() {
            const cart = getCart();
            // const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').textContent = cart.length;
        }

        function updateQuantity(index, newQuantity) {
            let cart = getCart();
            if (newQuantity <= 0) {
                removeFromCart(index);
                return;
            }
            
            cart[index].quantity = newQuantity;
            saveCart(cart);
            showNotification('Đã cập nhật số lượng', 'success');
        }

        function removeFromCart(index) {
            let cart = getCart();
            const removedItem = cart.splice(index, 1)[0];
            saveCart(cart);
            showNotification(`Đã xóa ${removedItem.ticketType} khỏi giỏ hàng`, 'success');
        }

        function clearCart() {
            if (confirm('Bạn có chắc chắn muốn xóa tất cả vé trong giỏ hàng?')) {
                localStorage.removeItem('eventHub_cart');
                updateCartDisplay();
                showNotification('Đã xóa tất cả vé khỏi giỏ hàng', 'success');
            }
        }

        function applyPromoCode() {
            const promoCode = document.getElementById('promoCode').value.trim().toUpperCase();
            
            if (!promoCode) {
                showNotification('Vui lòng nhập mã giảm giá!', 'error');
                return;
            }

            const validCodes = {
                'WELCOME10': { discount: 0.1, description: 'Giảm 10%' },
                'STUDENT20': { discount: 0.2, description: 'Giảm 20% cho sinh viên' },
                'EARLY50': { discount: 50000, description: 'Giảm 50,000₫' }
            };

            if (validCodes[promoCode]) {
                const discount = validCodes[promoCode];
                showNotification(`Áp dụng thành công: ${discount.description}`, 'success');
                // Store applied promo code
                localStorage.setItem('eventHub_promo', JSON.stringify(discount));
                updateCartDisplay();
            } else {
                showNotification('Mã giảm giá không hợp lệ!', 'error');
            }
        }

        function calculateTotals() {
            const cart = getCart();
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const processingFee = cart.length > 0 ? 25000 : 0;
            
            let discount = 0;
            try {
                const promo = JSON.parse(localStorage.getItem('eventHub_promo') || '{}');
                if (promo.discount) {
                    if (promo.discount < 1) {
                        // Percentage discount
                        discount = subtotal * promo.discount;
                    } else {
                        // Fixed amount discount
                        discount = Math.min(promo.discount, subtotal);
                    }
                }
            } catch {}

            const total = Math.max(0, subtotal + processingFee - discount);

            return { subtotal, processingFee, discount, total };
        }
        function updateCartDisplay() {
            const cart = getCart();
            const cartItemsList = document.getElementById('cartItemsList');
            const emptyCart = document.getElementById('emptyCart');
            const orderSummary = document.getElementById('orderSummary');
            const continueShopping = document.getElementById('continueShopping');

            updateCartCount();

            if (cart.length === 0) {
                emptyCart.classList.remove('hidden');
                cartItemsList.innerHTML = '';
                orderSummary.classList.add('hidden');
                continueShopping.classList.add('hidden');
                return;
            }

            emptyCart.classList.add('hidden');
            orderSummary.classList.remove('hidden');
            continueShopping.classList.remove('hidden');

            // Render cart items
            cartItemsList.innerHTML = cart.map((item, index) => `
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Event Image -->
                        <div class="flex-shrink-0">
                            <img src="storage/events/${item.image}" alt="${item.eventTitle}" class="w-24 h-24 object-cover rounded-lg">
                        </div>
                        
                        <!-- Event Details -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-bold text-gray-900 mb-1">${item.eventTitle}</h4>
                            <p class="text-primary-600 font-medium mb-1">${item.ticketType}</p>
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <span>${item.location}</span>
                            </div>
                            <p class="text-lg font-bold text-gray-900">${item.price.toLocaleString()}₫ x ${item.quantity}</p>
                        </div>
                        
                        <!-- Quantity Controls -->
                        <div class="flex flex-col items-end justify-between">
                            <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700 mb-4">
                                <i class="fas fa-trash"></i>
                            </button>
                            
                            <div class="flex items-center space-x-2">
                                <button onclick="updateQuantity(${index}, ${item.quantity - 1})" 
                                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="w-12 text-center font-medium">${item.quantity}</span>
                                <button onclick="updateQuantity(${index}, ${item.quantity + 1})" 
                                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Item Total -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tổng tiền:</span>
                            <span class="text-xl font-bold text-primary-600">${(item.price * item.quantity).toLocaleString()}₫</span>
                        </div>
                    </div>
                </div>
            `).join('');

            // Add clear cart button
            cartItemsList.innerHTML += `
                <div class="p-6 border-t border-gray-200 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <button onclick="clearCart()" class="text-red-600 hover:text-red-700 font-medium">
                            <i class="fas fa-trash mr-2"></i>Xóa tất cả
                        </button>
                        <span class="text-sm text-gray-500">${cart.length} sản phẩm trong giỏ hàng</span>
                    </div>
                </div>
            `;

            // Update order summary
            const totals = calculateTotals();
            document.getElementById('subtotal').textContent = totals.subtotal.toLocaleString() + '₫';
            document.getElementById('processingFee').textContent = totals.processingFee.toLocaleString() + '₫';
            document.getElementById('totalAmount').textContent = totals.total.toLocaleString() + '₫';

            // Show discount if applied
            if (totals.discount > 0) {
                const discountRow = `
                    <div class="flex justify-between text-green-600">
                        <span>Giảm giá</span>
                        <span>-${totals.discount.toLocaleString()}₫</span>
                    </div>
                `;
                document.getElementById('processingFee').parentElement.insertAdjacentHTML('afterend', discountRow);
            }
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
            updateCartDisplay();
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
    </style>