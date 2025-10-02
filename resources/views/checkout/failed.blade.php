@extends('layouts.home')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-4">
        <!-- Failed Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
            <!-- Failed Icon -->
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-times text-4xl text-red-600"></i>
            </div>

            <!-- Failed Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Thanh toán thất bại!</h1>
            
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800 text-sm">{{ session('error') }}</p>
                </div>
            @else
                <p class="text-gray-600 mb-8">Giao dịch không thành công. Vui lòng thử lại hoặc chọn phương thức thanh toán khác.</p>
            @endif

            <!-- Possible Reasons -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Nguyên nhân có thể:</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-circle text-xs text-gray-400 mt-1.5 mr-2"></i>
                        <span>Số dư tài khoản không đủ</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-circle text-xs text-gray-400 mt-1.5 mr-2"></i>
                        <span>Hủy giao dịch trên trang thanh toán</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-circle text-xs text-gray-400 mt-1.5 mr-2"></i>
                        <span>Nhập sai thông tin xác thực</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-circle text-xs text-gray-400 mt-1.5 mr-2"></i>
                        <span>Hết thời gian chờ thanh toán</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-circle text-xs text-gray-400 mt-1.5 mr-2"></i>
                        <span>Vấn đề kết nối mạng</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('checkout') }}" class="block w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-redo mr-2"></i>Thử lại
                </a>
                <a href="{{ route('cart') }}" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-shopping-cart mr-2"></i>Quay lại giỏ hàng
                </a>
                <a href="{{ route('dashboard') }}" class="block w-full text-gray-600 hover:text-gray-900 py-3 font-medium transition duration-200">
                    Về trang chủ
                </a>
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
@endsection