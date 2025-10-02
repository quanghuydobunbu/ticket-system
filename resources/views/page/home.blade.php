@extends('layouts/home')

@section('content')

{{-- @include('components/user/slider') --}}

<!-- Featured Events Slider -->
<section class="py-3 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Sự kiện nổi bật</h2>
        <div class="relative">
            <div id="featuredSlider" class="overflow-hidden rounded-xl">
                <div class="flex transition-transform duration-500 ease-in-out" id="sliderContainer">
                    @foreach($events->take(3) as $index => $event)
                    <!-- Slide {{ $index + 1 }} -->
                    <div class="min-w-full relative">
                        <img src="{{ asset('storage/events/' . $event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-96 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
                            <div class="text-white p-8">
                                <h3 class="text-3xl font-bold mb-2">{{ $event->title }}</h3>
                                <p class="text-lg mb-4">{{ $event->start_datetime ? \Carbon\Carbon::parse($event->start_datetime)->locale('vi')->translatedFormat('d/m/Y') : 'TBA' }} - {{ $event->venue->name }}</p>
                                <button class="bg-primary-600 hover:bg-primary-700 px-6 py-2 rounded-lg font-semibold" onclick="viewEventDetails({{ $event->id }})">
                                    Xem chi tiết
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
{{-- 
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Thể loại sự kiện</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-center cursor-pointer" onclick="filterByCategory('music')">
                <i class="fas fa-music text-4xl text-primary-600 mb-4"></i>
                <h3 class="font-semibold text-gray-900">Âm nhạc</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-center cursor-pointer" onclick="filterByCategory('conference')">
                <i class="fas fa-users text-4xl text-green-600 mb-4"></i>
                <h3 class="font-semibold text-gray-900">Hội thảo</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-center cursor-pointer" onclick="filterByCategory('sports')">
                <i class="fas fa-football-ball text-4xl text-orange-600 mb-4"></i>
                <h3 class="font-semibold text-gray-900">Thể thao</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-center cursor-pointer" onclick="filterByCategory('art')">
                <i class="fas fa-palette text-4xl text-purple-600 mb-4"></i>
                <h3 class="font-semibold text-gray-900">Nghệ thuật</h3>
            </div>
        </div>
    </div>
</section> --}}

<!-- Events List -->
<section id="events" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Sự kiện</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="eventsList">
            @foreach ($events as $event)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-200" data-category="{{ $event->category->name ?? 'other' }}">
                    <img src="{{ asset('storage/events/'.$event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                {{ $event->category->name }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $event->start_datetime ? \Carbon\Carbon::parse($event->start_datetime)->locale('vi')->translatedFormat('d-m-Y') : 'TBA' }}</span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $event->title ?? '' }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($event->description ?? '', 100) }}</p>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                            <span class="text-sm text-gray-600">{{ $event->venue->name }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-primary-600">
                                    @if ($event->is_free)
                                        Miễn phí
                                    @elseif($event->ticketTypes && $event->ticketTypes->count() > 0)
                                        <span class="text-sm text-gray-500">Từ</span>
                                        {{ number_format($event->ticketTypes->min('price')) }}đ - {{ number_format($event->ticketTypes->max('price')) }}đ
                                    @else
                                        Liên hệ
                                    @endif
                                </span>
                            </div>
                            <button class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200" onclick="viewEventDetails({{ $event->id }})">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Load More Button -->
        <div class="text-center mt-12">
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold transition duration-200" onclick="loadMoreEvents()">
                <a href="{{ route('event') }}">
                    <i class="fas fa-plus mr-2"></i>Xem thêm sự kiện
                </a>
            </button>
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

<script>
    const laravelEvents = @json($events->keyBy('id'));
    
    let currentSlide = 0;
    let currentEvent = null;
    const totalSlides = {{ $events->count() >= 3 ? 3 : $events->count() }};

    function getCart() {
        try {
            return JSON.parse(localStorage.getItem('eventHub_cart') || '[]');
        } catch {
            return [];
        }
    }
    function saveCart(cart) {
        localStorage.setItem('eventHub_cart', JSON.stringify(cart));
        updateCartCount();
    }


    function updateCartCount() {
            const cart = getCart();
            // const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').textContent = cart.length;
        }

    // Slider functionality
    const sliderContainer = document.getElementById('sliderContainer');

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
    if (totalSlides > 1) {
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }, 5000);
    }

    // Modal functionality
    function viewEventDetails(eventId) {
        const event = laravelEvents[eventId];
        if (!event) {
            console.error('Event not found:', eventId);
            return;
        }

        currentEvent = eventId;
        
        // Format date and time
        let formattedDate = 'TBA';
        let timeString = 'TBA';
        
        if (event.start_datetime) {
            const startDateTime = new Date(event.start_datetime);
            formattedDate = startDateTime.toLocaleDateString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });

            const startTime = startDateTime.toLocaleTimeString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit'
            });

            if (event.end_datetime) {
                const endDateTime = new Date(event.end_datetime);
                const endTime = endDateTime.toLocaleTimeString('vi-VN', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                timeString = startTime + ' - ' + endTime;
            } else {
                timeString = startTime;
            }
        }

        // Update modal content
        document.getElementById('modalTitle').textContent = event.title;
        document.getElementById('modalDate').textContent = formattedDate;
        document.getElementById('modalTime').textContent = timeString;
        document.getElementById('modalLocation').textContent = (event.venue ? event.venue.name : '') + (event.venue && event.venue.address ? ', ' + event.venue.address : '');
        document.getElementById('modalImage').src = '{{ asset("storage/events") }}/' + event.featured_image;
        document.getElementById('modalDescription').textContent = event.description;

        // Render ticket types
        const ticketTypesContainer = document.getElementById('ticketTypes');
        ticketTypesContainer.innerHTML = '';

        if (event.ticket_types && event.ticket_types.length > 0) {
            event.ticket_types.forEach((ticket, index) => {
                const ticketDiv = document.createElement('div');
                ticketDiv.className = 'border border-gray-200 rounded-lg p-4';
                
                const priceDisplay = event.is_free ? 'Miễn phí' : `${parseInt(ticket.price).toLocaleString('vi-VN')}₫`;
                
                ticketDiv.innerHTML = `
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-900">${ticket.name || ticket.type}</h4>
                        <span class="text-lg font-bold text-primary-600">${priceDisplay}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Còn lại: ${ticket.quantity_sold || 0}</span>
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
        } else if (event.is_free) {
            // Nếu không có tickets riêng và là sự kiện miễn phí
            ticketTypesContainer.innerHTML = `
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-900">Vé tham gia</h4>
                        <span class="text-lg font-bold text-green-600">Miễn phí</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Còn lại: ${event.max_attendees - event.current_attendees}</span>
                        <div class="flex items-center space-x-2">
                            <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full" onclick="decreaseQuantity(0)">
                                <i class="fas fa-minus text-xs"></i>
                            </button>
                            <span id="quantity-0" class="w-8 text-center">0</span>
                            <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full" onclick="increaseQuantity(0)">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        } else {
            ticketTypesContainer.innerHTML = '<p class="text-gray-500">Chưa có thông tin vé</p>';
        }

        document.getElementById('eventModal').classList.remove('hidden');
    }

    function closeModalHandler() {
        document.getElementById('eventModal').classList.add('hidden');
        // Reset quantities
        if (currentEvent && laravelEvents[currentEvent]) {
            const event = laravelEvents[currentEvent];
            const ticketCount = event.ticket_types ? event.ticket_types.length : (event.is_free ? 1 : 0);
            for (let i = 0; i < ticketCount; i++) {
                const quantityEl = document.getElementById(`quantity-${i}`);
                if (quantityEl) quantityEl.textContent = '0';
            }
        }
    }

    document.getElementById('closeModal').addEventListener('click', closeModalHandler);

    // Quantity controls
    function increaseQuantity(ticketIndex) {
        if (!currentEvent || !laravelEvents[currentEvent]) return;
        
        const event = laravelEvents[currentEvent];
        const quantityEl = document.getElementById(`quantity-${ticketIndex}`);
        const currentQuantity = parseInt(quantityEl.textContent);
        
        let maxAvailable = 100; // default
        if (event.ticket_types && event.ticket_types[ticketIndex]) {
            maxAvailable = event.ticket_types[ticketIndex].quantity_available || 100;
        } else if (event.is_free) {
            maxAvailable = event.max_attendees - event.current_attendees;
        }
        
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

    // Add to cart functionality
    function addToCart() {
        if (!currentEvent || !laravelEvents[currentEvent]) return;

        const event = laravelEvents[currentEvent];
        let cart = getCart();
        let addedItems = 0;

        if (event.ticket_types && event.ticket_types.length > 0) {
            event.ticket_types.forEach((ticket, index) => {
                const quantityEl = document.getElementById(`quantity-${index}`);
                if (!quantityEl) return;
                
                const quantity = parseInt(quantityEl.textContent);
               
                if (quantity > 0 && ticket.quantity_sold > 0) {
                    const cartItem = {
                        eventId: currentEvent,
                        eventTitle: event.title,
                        ticketType: ticket.name || ticket.type,
                        ticketId: ticket.id,
                        price: ticket.price,
                        quantity: quantity,
                        date: event.start_datetime,
                        location: event.venue ? event.venue.name : '',
                        image: event.featured_image
                    };

                    // Check if item already exists in cart
                    const existingItemIndex = cart.findIndex(item => 
                        item.eventId === cartItem.eventId && item.ticketId === cartItem.ticketId
                    );

                    if (existingItemIndex >= 0) {
                        cart[existingItemIndex].quantity += quantity;
                    } else {
                        cart.push(cartItem);
                    }

                    addedItems += quantity;
                }
            });
        } else if (event.is_free) {
            // Handle free event
            const quantityEl = document.getElementById('quantity-0');
            if (quantityEl) {
                const quantity = parseInt(quantityEl.textContent);
                if (quantity > 0) {
                    const cartItem = {
                        eventId: currentEvent,
                        eventTitle: event.title,
                        ticketType: 'Vé tham gia',
                        ticketId: 'free-ticket',
                        price: 0,
                        quantity: quantity,
                        date: event.start_datetime,
                        location: event.venue ? event.venue.name : '',
                        image: event.featured_image
                    };

                    const existingItemIndex = cart.findIndex(item => 
                        item.eventId === cartItem.eventId && item.ticketId === cartItem.ticketId
                    );

                    if (existingItemIndex >= 0) {
                        cart[existingItemIndex].quantity += quantity;
                    } else {
                        cart.push(cartItem);
                    }

                    addedItems += quantity;
                }
            }
        }

        if (addedItems > 0) {
            saveCart(cart);
            closeModalHandler();
            showNotification(`Đã thêm ${addedItems} vé vào giỏ hàng!`);
        } else {
            showNotification('Vui lòng kiểm tra lại!', 'error');
        }
    }

    // Search and filter functions
    function filterByCategory(category) {
        const eventCards = document.querySelectorAll('#eventsList > div[data-category]');
        
        eventCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category').toLowerCase();
            if (cardCategory.includes(category.toLowerCase()) || category === 'all') {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        showNotification(`Lọc theo thể loại: ${category}`, 'info');
    }

    function loadMoreEvents() {
        showNotification('Đang tải thêm sự kiện...', 'info');
        // Implement pagination logic here
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
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Close modal when clicking outside
    document.getElementById('eventModal').addEventListener('click', (e) => {
        if (e.target.id === 'eventModal') {
            closeModalHandler();
        }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        updateCartCount();
        console.log('Laravel Events:', laravelEvents); // Debug
    });
</script>
@endsection