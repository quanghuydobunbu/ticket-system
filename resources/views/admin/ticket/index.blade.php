@extends('layouts/admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Danh sách vé</h5>
            <div class="row">

            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('tickets.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="search" placeholder="Tìm kiếm theo mã vé, tên người tham dự..." 
                                class="form-control" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="status" onchange="this.form.submit()">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Kích hoạt</option>
                            <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Đã sử dụng</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="checked_in" onchange="this.form.submit()">
                            <option value="">Tất cả check-in</option>
                            <option value="1" {{ request('checked_in') === '1' ? 'selected' : '' }}>Đã check-in</option>
                            <option value="0" {{ request('checked_in') === '0' ? 'selected' : '' }}>Chưa check-in</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="ticket_type_id">
                            <option value="">Tất cả loại vé</option>
                            @foreach($ticketTypes as $ticketType)
                                <option value="{{ $ticketType->id }}" {{ request('ticket_type_id') == $ticketType->id ? 'selected' : '' }}>
                                    {{ $ticketType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Đặt lại
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Preserve other parameters -->
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="direction" value="{{ request('direction') }}">
            </form>

            @if(request()->hasAny(['search', 'status', 'checked_in', 'ticket_type_id']))
                <div class="mb-3">
                    <div class="d-flex flex-wrap gap-1 mt-1">
                        @if(request('search'))
                            <span class="badge bg-info">
                                Tìm kiếm: "{{ request('search') }}"
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-white ms-1">×</a>
                            </span>
                        @endif
                        @if(request('status'))
                            <span class="badge bg-success">
                                Trạng thái: {{ ucfirst(request('status')) }}
                                <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="text-white ms-1">×</a>
                            </span>
                        @endif
                        @if(request('checked_in') !== null)
                            <span class="badge bg-warning">
                                Check-in: {{ request('checked_in') == '1' ? 'Đã check-in' : 'Chưa check-in' }}
                                <a href="{{ request()->fullUrlWithQuery(['checked_in' => null]) }}" class="text-white ms-1">×</a>
                            </span>
                        @endif
                        @if(request('ticket_type_id'))
                            <span class="badge bg-primary">
                                Loại vé: {{ $ticketTypes->where('id', request('ticket_type_id'))->first()?->name }}
                                <a href="{{ request()->fullUrlWithQuery(['ticket_type_id' => null]) }}" class="text-white ms-1">×</a>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
            </div>
            
            <!-- Export buttons -->
            <div class="mb-3 d-flex gap-2">
                <a href="{{ route('tickets.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Tạo vé mới
                </a>
                {{-- <a href="{{ route('tickets.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" 
                   class="btn btn-outline-primary">
                    <i class="bi bi-download"></i> Xuất Excel
                </a> --}}
                <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#qrScanModal">
                    <i class="bi bi-qr-code-scan"></i> Quét QR
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="">
                    <tr>
                        <th scope="col">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'ticket_code', 'direction' => request('sort') === 'ticket_code' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                            class="text-decoration-none text-white">
                                Mã vé
                                @if(request('sort') === 'ticket_code')
                                    <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col">Người tham dự</th>
                        <th scope="col">Loại vé</th>
                        <th scope="col">Booking</th>
                        <th scope="col">QR Code</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Check-in</th>
                        <th scope="col">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                            class="text-decoration-none text-white">
                                Ngày tạo
                                @if(request('sort') === 'created_at')
                                    <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr>
                                <td>
                                    <div>
                                        <strong class="text-primary">{{ $ticket->ticket_code }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $ticket->attendee_name }}</strong>
                                        @if($ticket->booking && $ticket->booking->email)
                                            <div class="text-muted small">{{ $ticket->booking->email }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge bg-info">{{ $ticket->ticketType->name ?? 'N/A' }}</span>
                                        @if($ticket->ticketType && $ticket->ticketType->price)
                                            <div class="text-muted small">
                                                {{ number_format($ticket->ticketType->price, 0, ',', '.') }}đ
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($ticket->booking)
                                        {{-- <a href="{{ route('bookings.show', $ticket->booking->id) }}" 
                                           class="text-decoration-none">
                                            #{{ $ticket->booking->booking_code ?? $ticket->booking_id }}
                                        </a> --}}
                                        <div class="text-muted small">
                                            {{ $ticket->booking->customer_name ?? 'N/A' }}
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($ticket->qr_code)
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#qrModal{{ $ticket->id }}"
                                                title="Xem QR Code">
                                            <i class="bi bi-qr-code"></i>
                                        </button>
                                        
                                        <!-- QR Modal -->
                                        <div class="modal fade" id="qrModal{{ $ticket->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">QR Code - {{ $ticket->ticket_code }}</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        {!! $ticket->qr_code !!}
                                                        <p class="mt-2 small text-muted">{{ $ticket->attendee_name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @switch($ticket->status)
                                        @case('active')
                                            <span class="badge bg-success">Kích hoạt</span>
                                            @break
                                        @case('used')
                                            <span class="badge bg-primary">Đã sử dụng</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Đã hủy</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($ticket->status) }}</span>
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    @if($ticket->checked_in_at)
                                        <div>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Đã check-in
                                            </span>
                                            <div class="small text-muted">
                                                {{ \Carbon\Carbon::parse($ticket->checked_in_at)->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    @else
                                        @if($ticket->status === 'active')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success check-in-btn"
                                                    data-ticket-id="{{ $ticket->id }}"
                                                    data-ticket-code="{{ $ticket->ticket_code }}"
                                                    title="Check-in">
                                                <i class="bi bi-box-arrow-in-right"></i> Check-in
                                            </button>
                                        @else
                                            <span class="badge bg-secondary">Chưa check-in</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y') }}
                                        <br>
                                        <span class="text-muted">{{ \Carbon\Carbon::parse($ticket->created_at)->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('tickets.show', $ticket->id) }}" 
                                        class="btn btn-sm btn-outline-info" 
                                        title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('tickets.edit', $ticket->id) }}" 
                                        class="btn btn-sm btn-outline-primary" 
                                        title="Chỉnh sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        @if($ticket->status !== 'used')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger delete-btn" 
                                                    title="Xóa"
                                                    data-ticket-id="{{ $ticket->id }}"
                                                    data-ticket-code="{{ $ticket->ticket_code }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div> 
                                    
                                    <form id="delete-form-{{ $ticket->id }}" 
                                        action="{{ route('tickets.destroy', $ticket->id) }}" 
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    {{-- <form id="checkin-form-{{ $ticket->id }}" 
                                        action="{{ route('tickets.checkin', $ticket->id) }}" 
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('PATCH')
                                    </form> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-ticket-detailed fs-1 opacity-25"></i>
                                    <p class="mt-2">Chưa có vé nào</p>
                                    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Tạo vé đầu tiên
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($tickets->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Hiển thị {{ $tickets->firstItem() }}-{{ $tickets->lastItem() }} trong tổng số {{ $tickets->total() }} vé
                    </div>
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- QR Scanner Modal -->
    <div class="modal fade" id="qrScanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Quét QR Code Check-in</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="qr-reader" style="width: 100%;"></div>
                    <div class="mt-3">
                        <label for="manual-code" class="form-label">Hoặc nhập mã vé thủ công:</label>
                        <div class="input-group">
                            <input type="text" id="manual-code" class="form-control" placeholder="Nhập mã vé...">
                            <button class="btn btn-primary" id="manual-checkin-btn">Check-in</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete functionality
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const ticketId = this.getAttribute('data-ticket-id');
            const ticketCode = this.getAttribute('data-ticket-code');
            
            Swal.fire({
                title: 'Xác nhận xóa vé?',
                text: `Bạn có chắc chắn muốn xóa vé "${ticketCode}"? Hành động này không thể hoàn tác.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa vé',
                cancelButtonText: 'Hủy bỏ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Đang xóa vé...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('delete-form-' + ticketId).submit();
                }
            });
        });
    });

    // Check-in functionality
    const checkinButtons = document.querySelectorAll('.check-in-btn');
    checkinButtons.forEach(button => {
        button.addEventListener('click', function() {
            const ticketId = this.getAttribute('data-ticket-id');
            const ticketCode = this.getAttribute('data-ticket-code');
            
            Swal.fire({
                title: 'Xác nhận check-in?',
                text: `Bạn có chắc chắn muốn check-in cho vé "${ticketCode}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Check-in',
                cancelButtonText: 'Hủy bỏ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('checkin-form-' + ticketId).submit();
                }
            });
        });
    });

    // QR Scanner functionality
    let html5QrcodeScanner;
    
    document.getElementById('qrScanModal').addEventListener('shown.bs.modal', function() {
        function onScanSuccess(decodedText, decodedResult) {
            // Handle scan success
            processTicketCode(decodedText);
            html5QrcodeScanner.clear();
        }

        function onScanFailure(error) {
            // Handle scan failure
            console.warn(`Code scan error = ${error}`);
        }

        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });

    document.getElementById('qrScanModal').addEventListener('hidden.bs.modal', function() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }
    });

    // Manual check-in
    document.getElementById('manual-checkin-btn').addEventListener('click', function() {
        const code = document.getElementById('manual-code').value.trim();
        if (code) {
            processTicketCode(code);
        } else {
            Swal.fire('Lỗi', 'Vui lòng nhập mã vé', 'error');
        }
    });

   
});
</script>