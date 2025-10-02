@extends('layouts.home')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>

            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Thanh toán thành công!</h1>
            <p class="text-gray-600 mb-8">Cảm ơn bạn đã đặt vé. Thông tin vé sẽ được gửi qua email trong vài phút.</p>

            <!-- Order Info -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin đơn hàng</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mã đơn hàng:</span>
                        <span class="font-semibold text-gray-900">{{ $order['orderId'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mã giao dịch:</span>
                        <span class="font-semibold text-gray-900">{{ $order['transactionNo'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngân hàng:</span>
                        <span class="font-semibold text-gray-900 uppercase">{{ $order['bankCode'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Thời gian:</span>
                        <span class="font-semibold text-gray-900">{{ $order['created_at'] }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-3">
                        <span class="text-gray-600">Tổng tiền:</span>
                        <span class="text-xl font-bold text-primary-600">{{ number_format($order['amount']) }}₫</span>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-blue-50 rounded-xl p-6 mb-8 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin khách hàng</h3>
                
                <div class="space-y-2">
                    <div class="flex items-center">
                        <i class="fas fa-user text-blue-600 w-6"></i>
                        <span class="text-gray-700">{{ $order['customerInfo']['fullName'] }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-blue-600 w-6"></i>
                        <span class="text-gray-700">{{ $order['customerInfo']['email'] }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone text-blue-600 w-6"></i>
                        <span class="text-gray-700">{{ $order['customerInfo']['phone'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Tickets -->
            <div class="bg-gradient-to-br from-primary-50 to-blue-50 rounded-xl p-6 mb-8 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Danh sách vé</h3>
                
                <div class="space-y-4">
                    @foreach($order['cart'] as $item)
                    <div class="bg-white rounded-lg p-4 flex items-start space-x-4">
                        <img src="/storage/events/{{ $item['image'] }}" alt="{{ $item['eventTitle'] }}" class="w-16 h-16 object-cover rounded-lg">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 truncate">{{ $item['eventTitle'] }}</h4>
                            <p class="text-sm text-gray-600">{{ $item['ticketType'] }} x {{ $item['quantity'] }}</p>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>{{ $item['date'] }}
                            </p>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $item['location'] }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ number_format($item['price'] * $item['quantity']) }}₫</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" class="block w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-home mr-2"></i>Về trang chủ
                </a>
                <button onclick="window.print()" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-print mr-2"></i>In hóa đơn
                </button>
            </div>

            <!-- Support -->
            <div class="mt-8 pt-8 border-t">
                <p class="text-sm text-gray-500 mb-2">Cần hỗ trợ?</p>
                <div class="flex justify-center space-x-6">
                    <a href="tel:19001000" class="text-primary-600 hover:underline text-sm">
                        <i class="fas fa-phone mr-1"></i>1900 1000
                    </a>
                    <a href="mailto:support@eventhub.vn" class="text-primary-600 hover:underline text-sm">
                        <i class="fas fa-envelope mr-1"></i>support@eventhub.vn
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .bg-white.rounded-2xl, .bg-white.rounded-2xl * {
            visibility: visible;
        }
        .bg-white.rounded-2xl {
            position: absolute;
            left: 0;
            top: 0;
        }
        button, a {
            display: none !important;
        }
    }
</style>
@endsection