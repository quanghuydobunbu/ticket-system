@extends('layouts/home')

@section('content')

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-2">Vé của tôi</h1>
        <p class="text-primary-100">Quản lý và xem tất cả vé bạn đã đặt</p>
    </div>
</section>

<!-- Filter Tabs -->
<section class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-8 overflow-x-auto">
            <button onclick="filterTickets('all')" class="filter-tab py-4 px-2 border-b-2 border-primary-600 text-primary-600 font-semibold whitespace-nowrap">
                Tất cả
            </button>
            <button onclick="filterTickets('upcoming')" class="filter-tab py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-semibold whitespace-nowrap">
                Sắp diễn ra
            </button>
            <button onclick="filterTickets('past')" class="filter-tab py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-semibold whitespace-nowrap">
                Đã diễn ra
            </button>
            <button onclick="filterTickets('cancelled')" class="filter-tab py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-semibold whitespace-nowrap">
                Đã hủy
            </button>
        </div>
    </div>
</section>

<!-- Tickets List -->
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="ticketsList" class="space-y-6">
            <!-- Tickets will be loaded here dynamically -->
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-16">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-200 rounded-full mb-6">
                <i class="fas fa-ticket-alt text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Chưa có vé nào</h3>
            <p class="text-gray-600 mb-6">Bạn chưa đặt vé nào. Hãy khám phá các sự kiện thú vị!</p>
            <a href="{{ route('event') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-search mr-2"></i>Khám phá sự kiện
            </a>
        </div>
    </div>
</section>

<!-- Ticket Detail Modal -->
<div id="ticketModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-90vh overflow-y-auto">
            <div class="relative bg-gradient-to-r from-primary-600 to-primary-800 text-white p-8 rounded-t-xl">
                <button id="closeTicketModal" class="absolute top-4 right-4 text-white hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
                <h2 class="text-2xl font-bold mb-2" id="modalEventTitle"></h2>
                <p class="text-primary-100" id="modalTicketType"></p>
            </div>
            
            <div class="p-8">
                <!-- QR Code -->
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-xl p-8 mb-6 text-center">
                    <div id="qrcodeContainer" class="inline-block mb-4"></div>
                    <p class="text-sm text-gray-600">Mã vé: <span id="modalTicketCode" class="font-mono font-semibold"></span></p>
                </div>

                <!-- Ticket Details -->
                <div class="space-y-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-calendar text-primary-600 mt-1 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm text-gray-500">Ngày & Giờ</p>
                            <p class="font-semibold text-gray-900" id="modalDateTime"></p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-primary-600 mt-1 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm text-gray-500">Địa điểm</p>
                            <p class="font-semibold text-gray-900" id="modalLocation"></p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-user text-primary-600 mt-1 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm text-gray-500">Người đặt</p>
                            <p class="font-semibold text-gray-900" id="modalCustomerName"></p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-tag text-primary-600 mt-1 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm text-gray-500">Số lượng</p>
                            <p class="font-semibold text-gray-900" id="modalQuantity"></p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-money-bill-wave text-primary-600 mt-1 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm text-gray-500">Tổng tiền</p>
                            <p class="font-semibold text-primary-600 text-xl" id="modalTotalPrice"></p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-primary-600 mt-1 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm text-gray-500">Trạng thái</p>
                            <span id="modalStatus" class="inline-block px-3 py-1 rounded-full text-sm font-semibold"></span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4">
                    <button onclick="downloadTicket()" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-download mr-2"></i>Tải xuống
                    </button>
                    <button onclick="printTicket()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-print mr-2"></i>In vé
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    // Demo data - Replace with actual data from backend
    const demoTickets = [
        {
            id: 1,
            eventTitle: 'Liveshow Mỹ Tâm 2025',
            ticketType: 'VIP',
            ticketCode: 'MT2025VIP001',
            date: '2025-12-15',
            time: '19:00',
            location: 'Cung Văn Hóa Hữu Nghị, Hà Nội',
            quantity: 2,
            price: 1500000,
            status: 'confirmed',
            customerName: 'Nguyễn Văn A',
            image: 'event1.jpg'
        },
        {
            id: 2,
            eventTitle: 'Vietnam Tech Summit 2025',
            ticketType: 'Standard',
            ticketCode: 'VTS2025STD002',
            date: '2025-11-20',
            time: '08:00',
            location: 'Trung tâm Hội nghị Quốc gia, Hà Nội',
            quantity: 1,
            price: 0,
            status: 'confirmed',
            customerName: 'Nguyễn Văn A',
            image: 'event2.jpg'
        },
        {
            id: 3,
            eventTitle: 'Triển lãm Nghệ thuật Đương đại',
            ticketType: 'Regular',
            ticketCode: 'ART2025REG003',
            date: '2025-10-01',
            time: '09:00',
            location: 'Bảo tàng Mỹ thuật Việt Nam',
            quantity: 3,
            price: 150000,
            status: 'used',
            customerName: 'Nguyễn Văn A',
            image: 'event3.jpg'
        }
    ];

    let currentTicket = null;
    let currentFilter = 'all';

    function getStatusBadge(status) {
        const badges = {
            'confirmed': '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold"><i class="fas fa-check-circle mr-1"></i>Đã xác nhận</span>',
            'used': '<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold"><i class="fas fa-check mr-1"></i>Đã sử dụng</span>',
            'cancelled': '<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold"><i class="fas fa-times-circle mr-1"></i>Đã hủy</span>',
            'pending': '<span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold"><i class="fas fa-clock mr-1"></i>Chờ xác nhận</span>'
        };
        return badges[status] || badges['pending'];
    }

    function isUpcoming(dateString) {
        const eventDate = new Date(dateString);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return eventDate >= today;
    }

    function filterTickets(filter) {
        currentFilter = filter;
        
        // Update tab styles
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.remove('border-primary-600', 'text-primary-600');
            tab.classList.add('border-transparent', 'text-gray-500');
        });
        event.target.classList.remove('border-transparent', 'text-gray-500');
        event.target.classList.add('border-primary-600', 'text-primary-600');
        
        renderTickets();
    }

    function renderTickets() {
        const ticketsList = document.getElementById('ticketsList');
        const emptyState = document.getElementById('emptyState');
        
        let filteredTickets = demoTickets;
        
        // Apply filter
        if (currentFilter === 'upcoming') {
            filteredTickets = demoTickets.filter(t => isUpcoming(t.date) && t.status !== 'cancelled');
        } else if (currentFilter === 'past') {
            filteredTickets = demoTickets.filter(t => !isUpcoming(t.date) || t.status === 'used');
        } else if (currentFilter === 'cancelled') {
            filteredTickets = demoTickets.filter(t => t.status === 'cancelled');
        }
        
        if (filteredTickets.length === 0) {
            ticketsList.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        ticketsList.innerHTML = filteredTickets.map(ticket => {
            const eventDate = new Date(ticket.date);
            const formattedDate = eventDate.toLocaleDateString('vi-VN', {
                weekday: 'long',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            return `
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                    <div class="md:flex">
                        <div class="md:w-64 h-48 md:h-auto">
                            <img src="{{ asset('storage/events') }}/${ticket.image}" alt="${ticket.eventTitle}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">${ticket.eventTitle}</h3>
                                    <p class="text-gray-600 mb-2"><i class="fas fa-ticket-alt text-primary-600 mr-2"></i>${ticket.ticketType} × ${ticket.quantity}</p>
                                </div>
                                ${getStatusBadge(ticket.status)}
                            </div>
                            
                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1"><i class="fas fa-calendar text-gray-400 mr-2"></i>Ngày</p>
                                    <p class="font-semibold text-gray-900">${formattedDate}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1"><i class="fas fa-clock text-gray-400 mr-2"></i>Giờ</p>
                                    <p class="font-semibold text-gray-900">${ticket.time}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-500 mb-1"><i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>Địa điểm</p>
                                    <p class="font-semibold text-gray-900">${ticket.location}</p>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                <div>
                                    <p class="text-sm text-gray-500">Tổng tiền</p>
                                    <p class="text-2xl font-bold text-primary-600">
                                        ${ticket.price === 0 ? 'Miễn phí' : ticket.price.toLocaleString('vi-VN') + '₫'}
                                    </p>
                                </div>
                                <button onclick="viewTicketDetail(${ticket.id})" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                                    <i class="fas fa-eye mr-2"></i>Xem chi tiết
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function viewTicketDetail(ticketId) {
        const ticket = demoTickets.find(t => t.id === ticketId);
        if (!ticket) return;
        
        currentTicket = ticket;
        
        // Update modal content
        document.getElementById('modalEventTitle').textContent = ticket.eventTitle;
        document.getElementById('modalTicketType').textContent = ticket.ticketType;
        document.getElementById('modalTicketCode').textContent = ticket.ticketCode;
        
        const eventDate = new Date(ticket.date);
        const formattedDate = eventDate.toLocaleDateString('vi-VN', {
            weekday: 'long',
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
        document.getElementById('modalDateTime').textContent = `${formattedDate} - ${ticket.time}`;
        document.getElementById('modalLocation').textContent = ticket.location;
        document.getElementById('modalCustomerName').textContent = ticket.customerName;
        document.getElementById('modalQuantity').textContent = `${ticket.quantity} vé`;
        document.getElementById('modalTotalPrice').textContent = ticket.price === 0 ? 'Miễn phí' : ticket.price.toLocaleString('vi-VN') + '₫';
        
        const statusBadge = document.getElementById('modalStatus');
        statusBadge.outerHTML = getStatusBadge(ticket.status);
        
        // Generate QR Code
        const qrcodeContainer = document.getElementById('qrcodeContainer');
        qrcodeContainer.innerHTML = '';
        new QRCode(qrcodeContainer, {
            text: ticket.ticketCode,
            width: 200,
            height: 200,
            colorDark: '#1e40af',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
        
        document.getElementById('ticketModal').classList.remove('hidden');
    }

    function closeTicketModalHandler() {
        document.getElementById('ticketModal').classList.add('hidden');
    }

    document.getElementById('closeTicketModal').addEventListener('click', closeTicketModalHandler);

    document.getElementById('ticketModal').addEventListener('click', (e) => {
        if (e.target.id === 'ticketModal') {
            closeTicketModalHandler();
        }
    });

    function downloadTicket() {
        if (!currentTicket) return;
        showNotification('Đang tải xuống vé...', 'info');
        // Implement download logic here
        setTimeout(() => {
            showNotification('Đã tải xuống vé thành công!', 'success');
        }, 1000);
    }

    function printTicket() {
        window.print();
    }

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

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        renderTickets();
    });
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #ticketModal, #ticketModal * {
            visibility: visible;
        }
        #ticketModal {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        #closeTicketModal {
            display: none;
        }
    }
</style>

@endsection