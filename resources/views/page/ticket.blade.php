@extends('layouts.home')

@section('content')

<section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-2">Vé của tôi</h1>
        <p class="text-primary-100">Quản lý và xem tất cả vé bạn đã đặt</p>
    </div>
</section>

<section class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
       
    </div>
</section>

<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(isset($tickets) && $tickets->count() > 0)
            <div id="ticketsList" class="space-y-6">
                @foreach($tickets as $ticket)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                        <div class="md:flex">
                            <div class="md:w-64 h-48 md:h-auto">
                                <img src="{{ asset('storage/events/' .$ticket['image'] ) }}" 
                                     alt="{{ $ticket['eventTitle'] ?? 'Event' }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $ticket['eventTitle'] ?? 'N/A' }}</h3>
                                        <p class="text-gray-600 mb-2">
                                            <i class="fas fa-ticket-alt text-primary-600 mr-2"></i>
                                            {{ $ticket['ticketType'] ?? 'N/A' }} × {{ $ticket['quantity'] ?? 0 }}
                                        </p>
                                    </div>
                                    @php
                                        $statusConfig = [
                                            'confirmed' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fa-check-circle', 'text' => 'Đã xác nhận'],
                                            'used' => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-check', 'text' => 'Đã sử dụng'],
                                            'cancelled' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fa-times-circle', 'text' => 'Đã hủy'],
                                            'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-clock', 'text' => 'Chờ xác nhận']
                                        ];
                                        $ticketStatus = $ticket['status'] ?? 'pending';
                                        $status = $statusConfig[$ticketStatus] ?? $statusConfig['pending'];
                                    @endphp
                                    <span class="{{ $status['class'] }} px-3 py-1 rounded-full text-sm font-semibold">
                                        <i class="fas {{ $status['icon'] }} mr-1"></i>{{ $status['text'] }}
                                    </span>
                                </div>
                                
                                <div class="grid md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">
                                            <i class="fas fa-calendar text-gray-400 mr-2"></i>Ngày
                                        </p>
                                        <p class="font-semibold text-gray-900">
                                            @if(isset($ticket['date']) && $ticket['date'])
                                                {{ \Carbon\Carbon::parse($ticket['date'])->locale('vi')->isoFormat('dddd, DD/MM/YYYY') }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">
                                            <i class="fas fa-clock text-gray-400 mr-2"></i>Thời gian
                                        </p>
                                        <p class="font-semibold text-gray-900">{{ $ticket['time'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-500 mb-1">
                                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>Địa điểm
                                        </p>
                                        {{-- @dd($ticket['location']) --}}
                                        <p class="font-semibold text-gray-900">{{ $ticket['location'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                    <div>
                                        <p class="text-sm text-gray-500">Tổng tiền</p>
                                        <p class="text-2xl font-bold text-primary-600">
                                            @if(isset($ticket['price']) && $ticket['price'] == 0)
                                                Miễn phí
                                            @else
                                                {{ number_format($ticket['price'] ?? 0, 0, ',', '.') }}₫
                                            @endif
                                        </p>
                                    </div>
                                    <button onclick="viewTicketDetail({{ $ticket['id'] ?? 0 }})" 
                                            class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                                        <i class="fas fa-eye mr-2"></i>Xem chi tiết
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div id="emptyState" class="text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-200 rounded-full mb-6">
                    <i class="fas fa-ticket-alt text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Chưa có vé nào</h3>
                <p class="text-gray-600 mb-6">Bạn chưa đặt vé nào. Hãy khám phá các sự kiện thú vị!</p>
                <a href="{{ route('event') }}" 
                   class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-search mr-2"></i>Khám phá sự kiện
                </a>
            </div>
        @endif
    </div>
</section>

<div id="ticketModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="bg-white rounded-xl max-w-4xl w-full">
            <div class="relative bg-gradient-to-r from-primary-600 to-primary-800 text-white p-8 rounded-t-xl">
                <button id="closeTicketModal" class="absolute top-4 right-4 text-blue-800 hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
                <h2 class="text-2xl font-bold mb-2" id="modalEventTitle"></h2>
                <p class="text-primary-100" id="modalTicketType"></p>
                <p class="text-primary-100 text-sm mt-2">Mã đặt vé: <span id="modalBookingCode" class="font-mono"></span></p>
            </div>
            
            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-6 mb-8 pb-6 border-b">
                    <div class="space-y-4">
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
                    </div>
                    <div class="space-y-4">
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
                                <p class="text-sm text-gray-500">Tổng số vé</p>
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
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-ticket-alt mr-2"></i>Danh sách vé
                    </h3>
                    <div id="ticketsList" class="space-y-6">
                        <!-- Tickets will be inserted here by JavaScript -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 mt-8 pt-6 border-t">
                    <button onclick="downloadAllTickets()" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-download mr-2"></i>Tải xuống tất cả
                    </button>
                    <button onclick="printAllTickets()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-print mr-2"></i>In tất cả
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    let currentTicket = null;
    let qrCodeInstances = [];

    function getStatusBadgeHTML(status) {
        const badges = {
            'confirmed': '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold"><i class="fas fa-check-circle mr-1"></i>Đã xác nhận</span>',
            'used': '<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold"><i class="fas fa-check mr-1"></i>Đã sử dụng</span>',
            'cancelled': '<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold"><i class="fas fa-times-circle mr-1"></i>Đã hủy</span>',
            'active': '<span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold"><i class="fas fa-ticket-alt mr-1"></i>Chưa sử dụng</span>'
        };
        return badges[status] || badges['active'];
    }

    async function viewTicketDetail(ticketId) {
        try {
            const response = await fetch(`/my-tickets/${ticketId}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const ticket = await response.json();
            
            currentTicket = ticket;
            qrCodeInstances = []; // Reset QR codes
            
            // Update modal header
            document.getElementById('modalEventTitle').textContent = ticket.eventTitle || 'N/A';
            document.getElementById('modalTicketType').textContent = ticket.ticketType || 'N/A';
            document.getElementById('modalBookingCode').textContent = ticket.booking_code || 'N/A';
            
            // Update event details
            if (ticket.date) {
                const eventDate = new Date(ticket.date);
                const formattedDate = eventDate.toLocaleDateString('vi-VN', {
                    weekday: 'long',
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                document.getElementById('modalDateTime').textContent = `${formattedDate} - ${ticket.time || 'N/A'}`;
            } else {
                document.getElementById('modalDateTime').textContent = 'N/A';
            }
            
            document.getElementById('modalLocation').textContent = ticket.location || 'N/A';
            document.getElementById('modalCustomerName').textContent = ticket.customerName || 'N/A';
            document.getElementById('modalQuantity').textContent = `${ticket.quantity || 0} vé`;
            
            const price = ticket.price || 0;
            document.getElementById('modalTotalPrice').textContent = price === 0 ? 'Miễn phí' : price.toLocaleString('vi-VN') + '₫';
            
            // Render individual tickets
            renderIndividualTickets(ticket.tickets);
            
            document.getElementById('ticketModal').classList.remove('hidden');
        } catch (error) {
            console.error('Error loading ticket details:', error);
            showNotification('Không thể tải thông tin vé', 'error');
        }
    }

    function renderIndividualTickets(tickets) {
        const container = document.getElementById('ticketsList');
        container.innerHTML = '';

        if (!tickets || tickets.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-center py-8">Không có vé nào</p>';
            return;
        }

        tickets.forEach((ticket, index) => {
            const ticketElement = document.createElement('div');
            ticketElement.className = 'border-2 border-gray-200 rounded-xl p-6 hover:border-primary-300 transition';
            ticketElement.innerHTML = `
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- QR Code -->
                    <div class="flex-shrink-0">
                        <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                            <div id="qrcode-${index}" class="inline-block mb-2"></div>
                            <p class="text-xs text-gray-600 font-mono font-semibold">${ticket.ticket_code || 'N/A'}</p>
                        </div>
                    </div>
                    
                    <!-- Ticket Info -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 mb-1">Vé #${index + 1}</h4>
                                <p class="text-gray-600">
                                    <i class="fas fa-user text-gray-400 mr-2"></i>
                                    ${ticket.attendee_name || 'Chưa có tên'}
                                </p>
                            </div>
                            ${getStatusBadgeHTML(ticket.status)}
                        </div>
                        
                        ${ticket.checked_in_at ? `
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                <span class="text-green-800">Đã check-in: ${new Date(ticket.checked_in_at).toLocaleString('vi-VN')}</span>
                            </div>
                        ` : ''}
                        
                        <div class="mt-4 flex gap-2">
                            <button onclick="printSingleTicket(${index})" class="text-sm px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                                <i class="fas fa-print mr-1"></i>In vé này
                            </button>
                            <button onclick="downloadSingleTicket(${index})" class="text-sm px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                                <i class="fas fa-download mr-1"></i>Tải xuống
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(ticketElement);
            
            // Generate QR Code for this ticket
            setTimeout(() => {
                const qrElement = document.getElementById(`qrcode-${index}`);
                if (qrElement && ticket.ticket_code) {
                    const qrCode = new QRCode(qrElement, {
                        text: ticket.qr_code,
                        width: 150,
                        height: 150,
                        colorDark: '#1e40af',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.H
                    });
                    qrCodeInstances.push(qrCode);
                }
            }, 100);
        });
    }

    function closeTicketModalHandler() {
        document.getElementById('ticketModal').classList.add('hidden');
        qrCodeInstances = [];
    }

    document.getElementById('closeTicketModal').addEventListener('click', closeTicketModalHandler);

    document.getElementById('ticketModal').addEventListener('click', (e) => {
        if (e.target.id === 'ticketModal') {
            closeTicketModalHandler();
        }
    });

    function downloadAllTickets() {
        if (!currentTicket) return;
        showNotification('Đang tải xuống tất cả vé...', 'info');
        
        // TODO: Implement actual download
        setTimeout(() => {
            showNotification('Đã tải xuống tất cả vé thành công!', 'success');
        }, 1000);
    }

    function printAllTickets() {
        if (!currentTicket) return;
        window.open(`/my-tickets/${currentTicket.id}/print`, '_blank');
    }

    function printSingleTicket(index) {
        if (!currentTicket || !currentTicket.tickets[index]) return;
        showNotification('Đang chuẩn bị in vé...', 'info');
        // TODO: Implement single ticket print
    }

    function downloadSingleTicket(index) {
        if (!currentTicket || !currentTicket.tickets[index]) return;
        showNotification('Đang tải xuống vé...', 'info');
        // TODO: Implement single ticket download
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
        button {
            display: none !important;
        }
    }
</style>

@endsection