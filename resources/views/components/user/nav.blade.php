<nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-primary-600">EventHub</h1>
                    </div>
                </div>
                
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-900 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">Trang chủ</a>
                        <a href="{{ route('event') }}" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">Sự kiện</a>
                        <a href="#support" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">Hỗ trợ</a>
                        <a href="#about" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">Vé của tôi</a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <a href="{{ route('cart') }}">
                            <button id="cartBtn" class="text-gray-600 hover:text-primary-600 relative" onclick="goToCart()">
                                <i class="fas fa-shopping-cart text-xl"></i>
                                <span id="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                            </button>
                        </a>
                    </div>
                   
                    @if (Route::has('login'))
                        <nav class="flex items-center justify-end gap-4">
                            @auth
                            <a href="{{ route('profile.edit') }}">{{ Auth::user()->name }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link">Đăng xuất</button>
                            </form>
                                {{-- <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link> --}}

                            @else
                                <a href="{{ route('login') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    Đăng nhập
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                        Đăng ký
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>

            </div>
        </div>
    </nav>