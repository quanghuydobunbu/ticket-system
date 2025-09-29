@extends('layouts/home')

@section('content')

<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary-600 to-primary-800 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Khám Phá Sự Kiện</h1>
        <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
            Tìm kiếm và tham gia những sự kiện thú vị nhất trong thành phố
        </p>
        
        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        id="searchInput"
                        placeholder="Tìm kiếm sự kiện..." 
                        class="w-full px-6 py-4 pl-12 rounded-lg border-0 shadow-lg text-gray-900 placeholder-gray-500 focus:ring-4 focus:ring-primary-300"
                    >
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <button 
                    onclick="searchEvents()"
                    class="bg-white text-primary-600 px-8 py-4 rounded-lg font-semibold shadow-lg hover:shadow-xl transition duration-200"
                >
                    Tìm kiếm
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Filters Section -->
<section class="py-8 bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-4 items-center justify-between">
            <div class="flex flex-wrap gap-3">
                <button 
                    onclick="filterByCategory('all')" 
                    class="filter-btn active px-4 py-2 rounded-full border border-gray-300 hover:border-primary-500 hover:text-primary-600 transition duration-200"
                >
                    Tất cả
                </button>
                <button 
                    onclick="filterByCategory('music')" 
                    class="filter-btn px-4 py-2 rounded-full border border-gray-300 hover:border-primary-500 hover:text-primary-600 transition duration-200"
                >
                    <i class="fas fa-music mr-2"></i>Âm nhạc
                </button>
                <button 
                    onclick="filterByCategory('conference')" 
                    class="filter-btn px-4 py-2 rounded-full border border-gray-300 hover:border-primary-500 hover:text-primary-600 transition duration-200"
                >
                    <i class="fas fa-users mr-2"></i>Hội thảo
                </button>
                <button 
                    onclick="filterByCategory('sports')" 
                    class="filter-btn px-4 py-2 rounded-full border border-gray-300 hover:border-primary-500 hover:text-primary-600 transition duration-200"
                >
                    <i class="fas fa-football-ball mr-2"></i>Thể thao
                </button>
                <button 
                    onclick="filterByCategory('art')" 
                    class="filter-btn px-4 py-2 rounded-full border border-gray-300 hover:border-primary-500 hover:text-primary-600 transition duration-200"
                >
                    <i class="fas fa-palette mr-2"></i>Nghệ thuật
                </button>
            </div>
            
            <div class="flex gap-4">
                <!-- Sort Dropdown -->
                <select id="sortSelect" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" onchange="sortEvents()">
                    <option value="newest">Mới nhất</option>
                    <option value="oldest">Cũ nhất</option>
                    <option value="price_low">Giá thấp đến cao</option>
                    <option value="price_high">Giá cao đến thấp</option>
                    <option value="date_asc">Ngày gần nhất</option>
                    <option value="date_desc">Ngày xa nhất</option>
                </select>
                
                <!-- View Toggle -->
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button onclick="setViewMode('grid')" id="gridViewBtn" class="view-btn active px-3 py-2 rounded-md">
                        <i class="fas fa-th"></i>
                    </button>
                    <button onclick="setViewMode('list')" id="listViewBtn" class="view-btn px-3 py-2 rounded-md">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Events List -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Info -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">
                Kết quả: <span id="resultCount">{{ $events->count() }}</span> sự kiện
            </h2>
        </div>
        
        <!-- Events Grid -->
        <div id="eventsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-view="grid">
            @foreach ($events as $event)
                <div class="event-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-200" data-category="{{ $event->category->name ?? 'other' }}" data-date="{{ $event->start_datetime }}" data-price="{{ $event->ticketTypes ? $event->ticketTypes->min('price') : ($event->is_free ? 0 : 999999) }}">
                    <div class="event-card-image">
                        <img src="{{ asset('storage/events/'.$event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                        @if($event->is_free)
                            <div class="absolute top-4 left-4">
                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">MIỄN PHÍ</span>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4">
                            <button class="favorite-btn bg-white bg-opacity-80 hover:bg-opacity-100 p-2 rounded-full" onclick="toggleFavorite({{ $event->id }})">
                                <i class="far fa-heart text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="event-card-content p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                {{ $event->category->name }}
                            </span>
                            <span class="text-sm text-gray-500">
                                {{ $event->start_datetime ? \Carbon\Carbon::parse($event->start_datetime)->locale('vi')->translatedFormat('d-m-Y') : 'TBA' }}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ $event->title ?? '' }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($event->description ?? '', 120) }}</p>
                        
                        <div class="flex items-center mb-4 text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                            <span class="truncate">{{ $event->venue->name }}</span>
                        </div>
                        
                        @if($event->start_datetime)
                        <div class="flex items-center mb-4 text-sm text-gray-600">
                            <i class="fas fa-clock text-gray-400 mr-2"></i>
                            <span>{{ \Carbon\Carbon::parse($event->start_datetime)->format('H:i') }}</span>
                        </div>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-primary-600">
                                    @if ($event->is_free)
                                        Miễn phí
                                    @elseif($event->ticketTypes && $event->ticketTypes->count() > 0)
                                        <span class="text-sm text-gray-500">Từ</span>
                                        {{ number_format($event->ticketTypes->min('price')) }}₫
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
        
        <!-- Pagination -->
        <div class="flex justify-center mt-12">
            {{ $events->links() }}
        </div>
        
        <!-- No Results Message -->
        <div id="noResults" class="text-center py-12 hidden">
            <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Không tìm thấy sự kiện nào</h3>
            <p class="text-gray-500">Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
        </div>
    </div>
</section>

<!-- Event Detail Modal (same as homepage) -->
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

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .filter-btn.active {
        background-color: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    
    .view-btn.active {
        background-color: white;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    [data-view="list"] {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    [data-view="list"] .event-card {
        display: flex;
        align-items: stretch;
        max-width: none;
    }
    
    [data-view="list"] .event-card-image {
        flex: 0 0 300px;
        position: relative;
    }
    
    [data-view="list"] .event-card-image img {
        height: 100%;
        object-fit: cover;
    }
    
    [data-view="list"] .event-card-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
</style>

<script>
    // Truyền dữ liệu từ Laravel vào JavaScript
    const laravelEvents = @json($events->keyBy('id'));
    let currentEvent = null;
    let currentFilter = 'all';
    let currentSort = 'newest';
    let viewMode = 'grid';

    // Get cart from localStorage or initialize empty
    function getCart() {
        try {
            return JSON.parse(localStorage.getItem('eventHub_cart') || '[]');
        } catch {
            return [];
        }
    }

    // Save cart to localStorage
    function saveCart(cart) {
        localStorage.setItem('eventHub_cart', JSON.stringify(cart));
        updateCartCount();
    }

    // Update cart count in nav
    function updateCartCount() {
        const cart = getCart();
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const cartCountEl = document.getElementById('cartCount');
        if (cartCountEl) {
            cartCountEl.textContent = totalItems;
        }
    }

    // Search functionality
    function searchEvents() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
        const eventCards = document.querySelectorAll('.event-card');
        let visibleCount = 0;

        eventCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            const category = card.querySelector('.bg-blue-100').textContent.toLowerCase();
            const location = card.querySelector('.fa-map-marker-alt').parentElement.textContent.toLowerCase();

            const matches = title.includes(searchTerm) || 
                           description.includes(searchTerm) || 
                           category.includes(searchTerm) || 
                           location.includes(searchTerm);

            if (matches || searchTerm === '') {
                card.style.display = viewMode === 'grid' ? 'block' : 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        updateResultCount(visibleCount);
        toggleNoResults(visibleCount === 0);
    }

    // Filter functionality
    function filterByCategory(category) {
        currentFilter = category;
        const eventCards = document.querySelectorAll('.event-card');
        let visibleCount = 0;

        // Update active filter button
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        eventCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category').toLowerCase();
            
            if (category === 'all' || cardCategory.includes(category.toLowerCase())) {
                card.style.display = viewMode === 'grid' ? 'block' : 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        updateResultCount(visibleCount);
        toggleNoResults(visibleCount === 0);
    }

    // Sort functionality
    function sortEvents() {
        const sortValue = document.getElementById('sortSelect').value;
        currentSort = sortValue;
        const container = document.getElementById('eventsContainer');
        const cards = Array.from(container.querySelectorAll('.event-card'));

        cards.sort((a, b) => {
            switch (sortValue) {
                case 'newest':
                    return new Date(b.dataset.date || 0) - new Date(a.dataset.date || 0);
                case 'oldest':
                    return new Date(a.dataset.date || 0) - new Date(b.dataset.date || 0);
                case 'price_low':
                    return parseInt(a.dataset.price || 0) - parseInt(b.dataset.price || 0);
                case 'price_high':
                    return parseInt(b.dataset.price || 0) - parseInt(a.dataset.price || 0);
                case 'date_asc':
                    return new Date(a.dataset.date || '9999-12-31') - new Date(b.dataset.date || '9999-12-31');
                case 'date_desc':
                    return new Date(b.dataset.date || '1900-01-01') - new Date(a.dataset.date || '1900-01-01');
                default:
                    return 0;
            }
        });

        // Re-append sorted cards
        cards.forEach(card => container.appendChild(card));
    }

    // View mode toggle
    function setViewMode(mode) {
        viewMode = mode;
        const container = document.getElementById('eventsContainer');
        
        // Update active view button
        document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById(mode + 'ViewBtn').classList.add('active');
        
        // Update container class
        container.setAttribute('data-view', mode);
        
        if (mode === 'grid') {
            container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8';
        } else {
            container.className = 'flex flex-col gap-6';
        }
        
        // Update card display
        const cards = container.querySelectorAll('.event-card:not([style*="display: none"])');
        cards.forEach(card => {
            card.style.display = mode === 'grid' ? 'block' : 'flex';
        });
    }

    // Update result count
    function updateResultCount(count) {
        document.getElementById('resultCount').textContent = count;
    }

    // Toggle no results message
    function toggleNoResults(show) {
        const noResults = document.getElementById('noResults');
        const eventsContainer = document.getElementById('eventsContainer');
        
        if (show) {
            noResults.classList.remove('hidden');
            eventsContainer.style.display = 'none';
        } else {
            noResults.classList.add('hidden');
            eventsContainer.style.display = viewMode === 'grid' ? 'grid' : 'flex';
        }
    }

    // Favorite functionality
    function toggleFavorite(eventId) {
        const btn = event.target.closest('.favorite-btn');
        const icon = btn.querySelector('i');
        
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            icon.style.color = '#ef4444';
            showNotification('Đã thêm vào danh sách yêu thích!');
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            icon.style.color = '';
            showNotification('Đã xóa khỏi danh sách yêu thích!');
        }
    }

    // Modal functionality (copy from homepage)
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

    // Quantity controls
    function increaseQuantity(ticketIndex) {
        if (!currentEvent || !laravelEvents[currentEvent]) return;
        
        const event = laravelEvents[currentEvent];
        const quantityEl = document.getElementById(`quantity-${ticketIndex}`);
        const currentQuantity = parseInt(quantityEl.textContent);
        
        let maxAvailable = 100;
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

    // Add to cart functionality (copy from homepage)
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
                if (quantity > 0) {
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
            showNotification('Vui lòng chọn ít nhất 1 vé!', 'error');
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
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Event listeners
    document.getElementById('closeModal').addEventListener('click', closeModalHandler);
    
    document.getElementById('eventModal').addEventListener('click', (e) => {
        if (e.target.id === 'eventModal') {
            closeModalHandler();
        }
    });

    document.getElementById('searchInput').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            searchEvents();
        }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        updateCartCount();
        updateResultCount(document.querySelectorAll('.event-card').length);
    });
</script>

@endsection