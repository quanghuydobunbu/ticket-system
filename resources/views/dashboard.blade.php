<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventHub - Hệ thống bán vé sự kiện</title>
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
                        <h1 class="text-2xl font-bold text-primary-600">EventHub</h1>
                    </div>
                </div>
                
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#" class="text-gray-900 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">Trang chủ</a>
                        <a href="#events" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">Sự kiện</a>
                        <a href="#about" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">Giới thiệu</a>
                        <a href="#support" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">Hỗ trợ</a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button id="cartBtn" class="text-gray-600 hover:text-primary-600 relative">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </button>
                    </div>

                    @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link">Đăng xuất</button>
                        </form>


                        <a href="{{ route('profile.edit') }}">{{ Auth::user()->name }}</a>

                        {{-- <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link> --}}

                    @else
                        <a  
                            href="{{ route('login') }}"
                            class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Đăng nhập
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Đăng ký
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif

                    {{-- <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
                    </button>
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                        Đăng ký
                    </button> --}}
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-primary-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Khám phá sự kiện tuyệt vời
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">
                    Đặt vé sự kiện yêu thích của bạn chỉ với vài cú click
                </p>
                <div class="max-w-2xl mx-auto">
                    <div class="flex flex-col md:flex-row gap-4">
                        <input type="text" placeholder="Tìm kiếm sự kiện..." class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white">
                        <select class="px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white">
                            <option>Tất cả thành phố</option>
                            <option>Hà Nội</option>
                            <option>TP. Hồ Chí Minh</option>
                            <option>Đà Nẵng</option>
                        </select>
                        <button class="bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                            <i class="fas fa-search mr-2"></i>Tìm kiếm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Events Slider -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Sự kiện nổi bật</h2>
            <div class="relative">
                <div id="featuredSlider" class="overflow-hidden rounded-xl">
                    <div class="flex transition-transform duration-500 ease-in-out" id="sliderContainer">
                        <!-- Slide 1 -->
                        <div class="min-w-full relative">
                            <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Concert" class="w-full h-96 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
                                <div class="text-white p-8">
                                    <h3 class="text-3xl font-bold mb-2">Festival Âm nhạc Mùa hè 2024</h3>
                                    <p class="text-lg mb-4">15/10/2024 - Trung tâm Hội nghị Quốc gia</p>
                                    <button class="bg-primary-600 hover:bg-primary-700 px-6 py-2 rounded-lg font-semibold">
                                        Xem chi tiết
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Slide 2 -->
                        <div class="min-w-full relative">
                            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Conference" class="w-full h-96 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
                                <div class="text-white p-8">
                                    <h3 class="text-3xl font-bold mb-2">Hội thảo Công nghệ AI 2024</h3>
                                    <p class="text-lg mb-4">20/10/2024 - Khách sạn Lotte Hà Nội</p>
                                    <button class="bg-primary-600 hover:bg-primary-700 px-6 py-2 rounded-lg font-semibold">
                                        Xem chi tiết
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Slide 3 -->
                        <div class="min-w-full relative">
                            <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Sports" class="w-full h-96 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
                                <div class="text-white p-8">
                                    <h3 class="text-3xl font-bold mb-2">Giải bóng đá phong trào Hà Nội</h3>
                                    <p class="text-lg mb-4">25/10/2024 - Sân vận động Mỹ Đình</p>
                                    <button class="bg-primary-600 hover:bg-primary-700 px-6 py-2 rounded-lg font-semibold">
                                        Xem chi tiết
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slider Controls -->
                <button id="prevBtn" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-800 p-3 rounded-full shadow-lg">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="nextBtn" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-800 p-3 rounded-full shadow-lg">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Event Categories -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Thể loại sự kiện</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-center cursor-pointer">
                    <i class="fas fa-music text-4xl text-primary-600 mb-4"></i>
                    <h3 class="font-semibold text-gray-900">Âm nhạc</h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-center cursor-pointer">
                    <i class="fas fa-users text-4xl text-green-600 mb-4"></i>
                    <h3 class="font-semibold text-gray-900">Hội thảo</h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-center cursor-pointer">
                    <i class="fas fa-football-ball text-4xl text-orange-600 mb-4"></i>
                    <h3 class="font-semibold text-gray-900">Thể thao</h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-center cursor-pointer">
                    <i class="fas fa-palette text-4xl text-purple-600 mb-4"></i>
                    <h3 class="font-semibold text-gray-900">Nghệ thuật</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Events List -->
    <section id="events" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Tất cả sự kiện</h2>
                <div class="flex flex-wrap gap-4 mt-4 md:mt-0">
                    <select class="border border-gray-300 rounded-lg px-4 py-2">
                        <option>Sắp xếp theo</option>
                        <option>Ngày gần nhất</option>
                        <option>Giá tăng dần</option>
                        <option>Phổ biến nhất</option>
                    </select>
                    <select class="border border-gray-300 rounded-lg px-4 py-2">
                        <option>Tất cả thể loại</option>
                        <option>Âm nhạc</option>
                        <option>Hội thảo</option>
                        <option>Thể thao</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="eventsList">
                <!-- Event Card 1 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-200">
                    <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Concert" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Âm nhạc</span>
                            <span class="text-sm text-gray-500">15/10/2024</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Đêm nhạc Acoustic</h3>
                        <p class="text-gray-600 mb-4">Đêm nhạc acoustic với các ca sĩ nổi tiếng, không khí ấm cúng và lãng mạn.</p>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                            <span class="text-sm text-gray-600">Nhà hát Lớn Hà Nội</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm text-gray-500">Từ</span>
                                <span class="text-lg font-bold text-primary-600">200,000₫</span>
                            </div>
                            <button class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200" onclick="viewEventDetails('acoustic-night')">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Event Card 2 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-200">
                    <img src="https://images.unsplash.com/photo-1515187029135-18ee286d815b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Tech Conference" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Hội thảo</span>
                            <span class="text-sm text-gray-500">18/10/2024</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Vietnam Tech Summit 2024</h3>
                        <p class="text-gray-600 mb-4">Hội thảo công nghệ lớn nhất Việt Nam với các diễn giả hàng đầu thế giới.</p>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                            <span class="text-sm text-gray-600">Trung tâm Hội nghị Quốc gia</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm text-gray-500">Từ</span>
                                <span class="text-lg font-bold text-primary-600">500,000₫</span>
                            </div>
                            <button class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200" onclick="viewEventDetails('tech-summit')">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Event Card 3 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-200">
                    <img src="https://images.unsplash.com/photo-1574391884720-bbc6d4d47f38?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Marathon" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2.5 py-0.5 rounded">Thể thao</span>
                            <span class="text-sm text-gray-500">22/10/2024</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Hanoi Marathon 2024</h3>
                        <p class="text-gray-600 mb-4">Giải marathon quốc tế với cung đường đẹp nhất Hà Nội.</p>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                            <span class="text-sm text-gray-600">Hồ Gươm - Hà Nội</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm text-gray-500">Từ</span>
                                <span class="text-lg font-bold text-primary-600">150,000₫</span>
                            </div>
                            <button class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200" onclick="viewEventDetails('hanoi-marathon')">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Detail Modal -->
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-xl max-w-4xl w-full max-h-90vh overflow-y-auto">
                <div class="relative">
                    <img id="modalImage" src="" alt="Event" class="w-full h-64 object-cover rounded-t-xl">
                    <button id="closeModal" class="absolute top-4 right-4 bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-800 p-2 rounded-full">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="flex-1">
                            <h2 id="modalTitle" class="text-3xl font-bold text-gray-900 mb-4"></h2>
                            <div class="flex items-center mb-4">
                                <i class="fas fa-calendar text-primary-600 mr-3"></i>
                                <span id="modalDate" class="text-gray-600"></span>
                            </div>
                            <div class="flex items-center mb-4">
                                <i class="fas fa-map-marker-alt text-primary-600 mr-3"></i>
                                <span id="modalLocation" class="text-gray-600"></span>
                            </div>
                            <div class="flex items-center mb-6">
                                <i class="fas fa-clock text-primary-600 mr-3"></i>
                                <span id="modalTime" class="text-gray-600"></span>
                            </div>
                            <p id="modalDescription" class="text-gray-700 mb-6 leading-relaxed"></p>
                        </div>
                        
                        <div class="md:w-80">
                            <div class="bg-gray-50 p-6 rounded-xl">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Chọn loại vé</h3>
                                <div id="ticketTypes" class="space-y-4">
                                    <!-- Ticket types will be inserted here -->
                                </div>
                                <div class="mt-6">
                                    <button class="w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold text-lg transition duration-200" onclick="addToCart()">
                                        <i class="fas fa-shopping-cart mr-2"></i>Thêm vào giỏ hàng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Sidebar -->
    <div id="cartSidebar" class="fixed right-0 top-0 h-full w-96 bg-white shadow-xl z-50 transform translate-x-full transition-transform duration-300">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold">Giỏ hàng</h3>
                <button id="closeCart" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div id="cartItems" class="p-6 flex-1 overflow-y-auto">
            <p class="text-gray-500 text-center">Giỏ hàng trống</p>
        </div>
        <div class="border-t p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-lg font-semibold">Tổng cộng:</span>
                <span id="cartTotal" class="text-xl font-bold text-primary-600">0₫</span>
            </div>
            <button class="w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold text-lg transition duration-200" onclick="checkout()">
                Thanh toán
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
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
                    <h4 class="text-lg font-semibold mb-4">Sự kiện</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Âm nhạc</a></li>
                        <li><a href="#" class="hover:text-white">Hội thảo</a></li>
                        <li><a href="#" class="hover:text-white">Thể thao</a></li>
                        <li><a href="#" class="hover:text-white">Nghệ thuật</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Hỗ trợ</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Câu hỏi thường gặp</a></li>
                        <li><a href="#" class="hover:text-white">Chính sách hoàn tiền</a></li>
                        <li><a href="#" class="hover:text-white">Liên hệ</a></li>
                        <li><a href="#" class="hover:text-white">Trung tâm trợ giúp</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Đăng ký nhận tin</h4>
                    <p class="text-gray-400 mb-4">Nhận thông tin về sự kiện mới nhất</p>
                    <div class="flex">
                        <input type="email" placeholder="Email của bạn" class="flex-1 px-4 py-2 rounded-l-lg text-gray-800">
                        <button class="bg-primary-600 hover:bg-primary-700 px-4 py-2 rounded-r-lg">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 EventHub. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <script>
        // Global variables
        let currentSlide = 0;
        let cart = [];
        let currentEvent = null;

        // Slider functionality
        const sliderContainer = document.getElementById('sliderContainer');
        const totalSlides = 3;

        function showSlide(index) {
            const translateX = -index * 100;
            sliderContainer.style.transform = `translateX(${translateX}%)`;
        }

        document.getElementById('nextBtn').addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        });

        // Auto slide
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }, 5000);

        // Event data
        const events = {
            'acoustic-night': {
                title: 'Đêm nhạc Acoustic',
                date: '15/10/2024',
                time: '19:00 - 22:00',
                location: 'Nhà hát Lớn Hà Nội',
                image: 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                description: 'Đêm nhạc acoustic với các ca sĩ nổi tiếng, không khí ấm cúng và lãng mạn. Chương trình sẽ có sự tham gia của nhiều nghệ sĩ tài năng với những ca khúc được yêu thích nhất.',
                tickets: [
                    { type: 'Vé thường', price: 200000, available: 150 },
                    { type: 'Vé VIP', price: 500000, available: 50 },
                    { type: 'Vé Early Bird', price: 150000, available: 25 }
                ]
            },
            'tech-summit': {
                title: 'Vietnam Tech Summit 2024',
                date: '18/10/2024',
                time: '08:00 - 18:00',
                location: 'Trung tâm Hội nghị Quốc gia',
                image: 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                description: 'Hội thảo công nghệ lớn nhất Việt Nam với các diễn giả hàng đầu thế giới. Khám phá những xu hướng công nghệ mới nhất, kết nối với cộng đồng developer và startup.',
                tickets: [
                    { type: 'Vé Student', price: 300000, available: 200 },
                    { type: 'Vé Standard', price: 500000, available: 300 },
                    { type: 'Vé Premium', price: 1000000, available: 100 }
                ]
            },
            'hanoi-marathon': {
                title: 'Hanoi Marathon 2024',
                date: '22/10/2024',
                time: '05:00 - 12:00',
                location: 'Hồ Gươm - Hà Nội',
                image: 'https://images.unsplash.com/photo-1574391884720-bbc6d4d47f38?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                description: 'Giải marathon quốc tế với cung đường đẹp nhất Hà Nội. Tham gia cùng hàng nghìn vận động viên từ khắp nơi trên thế giới trong một sự kiện thể thao đầy ý nghĩa.',
                tickets: [
                    { type: '5KM Fun Run', price: 150000, available: 500 },
                    { type: '21KM Half Marathon', price: 300000, available: 300 },
                    { type: '42KM Full Marathon', price: 500000, available: 200 }
                ]
            }
        };

        // Modal functionality
        function viewEventDetails(eventId) {
            const event = events[eventId];
            if (!event) return;

            currentEvent = eventId;
            document.getElementById('modalTitle').textContent = event.title;
            document.getElementById('modalDate').textContent = event.date;
            document.getElementById('modalTime').textContent = event.time;
            document.getElementById('modalLocation').textContent = event.location;
            document.getElementById('modalImage').src = event.image;
            document.getElementById('modalDescription').textContent = event.description;

            // Render ticket types
            const ticketTypesContainer = document.getElementById('ticketTypes');
            ticketTypesContainer.innerHTML = '';

            event.tickets.forEach((ticket, index) => {
                const ticketDiv = document.createElement('div');
                ticketDiv.className = 'border border-gray-200 rounded-lg p-4';
                ticketDiv.innerHTML = `
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-900">${ticket.type}</h4>
                        <span class="text-lg font-bold text-primary-600">${ticket.price.toLocaleString()}₫</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Còn lại: ${ticket.available}</span>
                        <div class="flex items-center space-x-2">
                            <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full" onclick="decreaseQuantity(${index})">
                                <i class="fas fa-minus text-xs"></i>
                            </button>
                            <span id="quantity-${index}" class="w-8 text-center">0</span>
                            <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full" onclick="increaseQuantity(${index})">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>
                `;
                ticketTypesContainer.appendChild(ticketDiv);
            });

            document.getElementById('eventModal').classList.remove('hidden');
        }

        function closeModalHandler() {
            document.getElementById('eventModal').classList.add('hidden');
            // Reset quantities
            if (currentEvent) {
                events[currentEvent].tickets.forEach((_, index) => {
                    document.getElementById(`quantity-${index}`).textContent = '0';
                });
            }
        }

        document.getElementById('closeModal').addEventListener('click', closeModalHandler);

        // Quantity controls
        function increaseQuantity(ticketIndex) {
            const quantityEl = document.getElementById(`quantity-${ticketIndex}`);
            const currentQuantity = parseInt(quantityEl.textContent);
            const maxAvailable = events[currentEvent].tickets[ticketIndex].available;
            
            if (currentQuantity < maxAvailable) {
                quantityEl.textContent = currentQuantity + 1;
            }
        }

        function decreaseQuantity(ticketIndex) {
            const quantityEl = document.getElementById(`quantity-${ticketIndex}`);
            const currentQuantity = parseInt(quantityEl.textContent);
            
            if (currentQuantity > 0) {
                quantityEl.textContent = currentQuantity - 1;
            }
        }

        // Cart functionality
        function addToCart() {
            if (!currentEvent) return;

            const event = events[currentEvent];
            let addedItems = 0;

            event.tickets.forEach((ticket, index) => {
                const quantity = parseInt(document.getElementById(`quantity-${index}`).textContent);
                if (quantity > 0) {
                    const cartItem = {
                        eventId: currentEvent,
                        eventTitle: event.title,
                        ticketType: ticket.type,
                        price: ticket.price,
                        quantity: quantity,
                        date: event.date,
                        location: event.location
                    };

                    // Check if item already exists in cart
                    const existingItemIndex = cart.findIndex(item => 
                        item.eventId === cartItem.eventId && item.ticketType === cartItem.ticketType
                    );

                    if (existingItemIndex >= 0) {
                        cart[existingItemIndex].quantity += quantity;
                    } else {
                        cart.push(cartItem);
                    }

                    addedItems += quantity;
                }
            });

            if (addedItems > 0) {
                updateCartUI();
                closeModalHandler();
                showNotification(`Đã thêm ${addedItems} vé vào giỏ hàng!`);
            }
        }

        function updateCartUI() {
            const cartCount = document.getElementById('cartCount');
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');

            // Update cart count
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.textContent = totalItems;

            // Update cart items
            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-gray-500 text-center">Giỏ hàng trống</p>';
            } else {
                cartItems.innerHTML = cart.map((item, index) => `
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-900">${item.eventTitle}</h4>
                            <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">${item.ticketType}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">${item.quantity} x ${item.price.toLocaleString()}₫</span>
                            <span class="font-semibold text-primary-600">${(item.quantity * item.price).toLocaleString()}₫</span>
                        </div>
                    </div>
                `).join('');
            }

            // Update total
            const total = cart.reduce((sum, item) => sum + (item.quantity * item.price), 0);
            cartTotal.textContent = total.toLocaleString() + '₫';
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartUI();
        }

        // Cart sidebar
        document.getElementById('cartBtn').addEventListener('click', () => {
            document.getElementById('cartSidebar').classList.remove('translate-x-full');
        });

        document.getElementById('closeCart').addEventListener('click', () => {
            document.getElementById('cartSidebar').classList.add('translate-x-full');
        });

        // Checkout
        function checkout() {
            if (cart.length === 0) {
                showNotification('Giỏ hàng trống!', 'error');
                return;
            }

            // Simulate checkout process
            showNotification('Đang chuyển hướng đến trang thanh toán...', 'success');
            
            // In a real application, this would redirect to a checkout page
            setTimeout(() => {
                alert('Demo: Tính năng thanh toán sẽ được tích hợp với VNPay, Momo, hoặc các cổng thanh toán khác');
            }, 1500);
        }

        // Notification system
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg text-white font-semibold transform translate-x-full transition-transform duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
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
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Close modal when clicking outside
        document.getElementById('eventModal').addEventListener('click', (e) => {
            if (e.target.id === 'eventModal') {
                closeModalHandler();
            }
        });

        // Close cart when clicking outside
        document.addEventListener('click', (e) => {
            const cartSidebar = document.getElementById('cartSidebar');
            const cartBtn = document.getElementById('cartBtn');
            
            if (!cartSidebar.contains(e.target) && !cartBtn.contains(e.target)) {
                cartSidebar.classList.add('translate-x-full');
            }
        });

        // Search functionality
        document.querySelector('button[class*="bg-white text-primary-600"]').addEventListener('click', () => {
            const searchInput = document.querySelector('input[placeholder="Tìm kiếm sự kiện..."]');
            const citySelect = document.querySelector('select');
            
            showNotification(`Tìm kiếm: "${searchInput.value}" tại ${citySelect.value}`);
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            updateCartUI();
        });
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
        
        .max-h-90vh {
            max-height: 90vh;
        }

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
</body>
</html>