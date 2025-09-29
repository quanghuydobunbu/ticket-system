@include('components/user/header')

<body class="bg-gray-50">
    @include('components/user/nav')

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
                                    <button class="bg-primary-600 hover:bg-primary-700 px-6 py-2 rounded-lg font-semibold" onclick="viewEventDetails('acoustic-night')">
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
                                    <button class="bg-primary-600 hover:bg-primary-700 px-6 py-2 rounded-lg font-semibold" onclick="viewEventDetails('tech-summit')">
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
                                    <button class="bg-primary-600 hover:bg-primary-700 px-6 py-2 rounded-lg font-semibold" onclick="viewEventDetails('hanoi-marathon')">
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
    </section>

    <!-- Events List -->
    <section id="events" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Tất cả sự kiện</h2>
                <div class="flex flex-wrap gap-4 mt-4 md:mt-0">
                    <select class="border border-gray-300 rounded-lg px-4 py-2" id="sortSelect">
                        <option value="">Sắp xếp theo</option>
                        <option value="date">Ngày gần nhất</option>
                        <option value="price">Giá tăng dần</option>
                        <option value="popularity">Phổ biến nhất</option>
                    </select>
                    <select class="border border-gray-300 rounded-lg px-4 py-2" id="categoryFilter">
                        <option value="">Tất cả thể loại</option>
                        <option value="music">Âm nhạc</option>
                        <option value="conference">Hội thảo</option>
                        <option value="sports">Thể thao</option>
                        <option value="art">Nghệ thuật</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="eventsList">
                <!-- Event Card 1 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-200" data-category="music">
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
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-200" data-category="conference">
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
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-200" data-category="sports">
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
            
            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold transition duration-200" onclick="loadMoreEvents()">
                    <i class="fas fa-plus mr-2"></i>Xem thêm sự kiện
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

    <!-- Footer -->
    
    @include('components/user/footer')

    <script>
        // Global variables
        let currentSlide = 0;
        let currentEvent = null;

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
            document.getElementById('cartCount').textContent = totalItems;
        }

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

        // Navigation functions
        function goToCart() {
            window.location.href = 'cart.html';
        }

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
                    const quantityEl = document.getElementById(`quantity-${index}`);
                    if (quantityEl) quantityEl.textContent = '0';
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

        // Add to cart functionality
        function addToCart() {
            if (!currentEvent) return;

            const event = events[currentEvent];
            let cart = getCart();
            let addedItems = 0;

            event.tickets.forEach((ticket, index) => {
                const quantityEl = document.getElementById(`quantity-${index}`);
                if (!quantityEl) return;
                
                const quantity = parseInt(quantityEl.textContent);
                if (quantity > 0) {
                    const cartItem = {
                        eventId: currentEvent,
                        eventTitle: event.title,
                        ticketType: ticket.type,
                        price: ticket.price,
                        quantity: quantity,
                        date: event.date,
                        location: event.location,
                        image: event.image
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
                saveCart(cart);
                closeModalHandler();
                showNotification(`Đã thêm ${addedItems} vé vào giỏ hàng!`);
            } else {
                showNotification('Vui lòng chọn ít nhất 1 vé!', 'error');
            }
        }

        // Search and filter functions
        function searchEvents() {
            const searchInput = document.querySelector('input[placeholder="Tìm kiếm sự kiện..."]');
            const citySelect = document.querySelector('select');
            
            showNotification(`Tìm kiếm: "${searchInput.value}" tại ${citySelect.value}`);
        }

        function filterByCategory(category) {
            const categoryFilter = document.getElementById('categoryFilter');
            categoryFilter.value = category;
            applyFilters();
        }

        function applyFilters() {
            const categoryFilter = document.getElementById('categoryFilter').value;
            const eventCards = document.querySelectorAll('#eventsList > div[data-category]');
            
            eventCards.forEach(card => {
                if (!categoryFilter || card.getAttribute('data-category') === categoryFilter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function loadMoreEvents() {
            showNotification('Đang tải thêm sự kiện...', 'info');
            // Simulate loading more events
        }

        // Event listeners
        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
        document.getElementById('sortSelect').addEventListener('change', function() {
            showNotification(`Sắp xếp theo: ${this.options[this.selectedIndex].text}`, 'info');
        });

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

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            updateCartCount();
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