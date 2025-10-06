@extends('layouts/admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Chi tiết vé</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Danh sách vé</a></li>
                    <li class="breadcrumb-item active">{{ $ticket->ticket_code }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Thông tin vé -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-ticket-detailed"></i> Thông tin vé</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Mã vé</label>
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-primary">{{ $ticket->ticket_code }}</h5>
                                <button class="btn btn-sm btn-outline-secondary ms-2" 
                                        onclick="copyToClipboard('{{ $ticket->ticket_code }}')"
                                        title="Sao chép mã vé">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Trạng thái</label>
                            <div>
                                @switch($ticket->status)
                                    @case('active')
                                        <span class="badge bg-success fs-6">
                                            <i class="bi bi-check-circle"></i> Kích hoạt
                                        </span>
                                        @break
                                    @case('used')
                                        <span class="badge bg-primary fs-6">
                                            <i class="bi bi-check-all"></i> Đã sử dụng
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger fs-6">
                                            <i class="bi bi-x-circle"></i> Đã hủy
                                        </span>
                                        @break
                                @endswitch
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Loại vé</label>
                            <div>
                                <span class="badge bg-info fs-6">{{ $ticket->ticketType->name ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Giá vé</label>
                            <h5 class="mb-0 text-success">
                                {{ number_format($ticket->ticketType->price ?? 0, 0, ',', '.') }}đ
                            </h5>
                        </div>

                         <div class="col-md-6">
                            <label class="text-muted small mb-1">Sự kiện</label>
                            <h5 class="mb-0 text-success">
                                {{ $ticket->ticketType->event->title ?? '' }}
                            </h5>
                        </div>

                        <div class="col-12">
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Tên người tham dự</label>
                            <h6 class="mb-0">{{ $ticket->attendee_name ?? 'N/A' }}</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Email</label>
                            <div>
                                @if($ticket->booking && $ticket->booking->email)
                                    <a href="mailto:{{ $ticket->booking->email }}">{{ $ticket->booking->email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Số điện thoại</label>
                            <div>
                                @if($ticket->booking && $ticket->booking->phone)
                                    <a href="tel:{{ $ticket->booking->phone }}">{{ $ticket->booking->phone }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Ngày đặt vé</label>
                            <div>
                                @if($ticket->created_at)
                                    <i class="bi bi-calendar-event text-muted"></i>
                                    {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin Check-in -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Thông tin Check-in</h5>
                </div>
                <div class="card-body mt-3">
                    @if($ticket->checked_in_at)
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                            <div>
                                <h6 class="mb-1">Đã check-in thành công</h6>
                                <p class="mb-0">
                                    Thời gian: <strong>{{ \Carbon\Carbon::parse($ticket->checked_in_at)->format('d/m/Y H:i:s') }}</strong>
                                </p>
                                <p class="mb-0 small text-muted">
                                    ({{ \Carbon\Carbon::parse($ticket->checked_in_at)->diffForHumans() }})
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Chưa check-in</h6>
                                <p class="mb-0">Vé này chưa được sử dụng để check-in</p>
                            </div>
                            @if($ticket->status === 'active')
                                <button type="button" 
                                        class="btn btn-success"
                                        onclick="confirmCheckin({{ $ticket->id }}, '{{ $ticket->ticket_code }}')">
                                    <i class="bi bi-box-arrow-in-right"></i> Check-in ngay
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thông tin bổ sung -->
            {{-- @if($ticket->booking)
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin đặt vé</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Mã đặt vé</label>
                            <div><strong>{{ $ticket->booking->booking_code ?? 'N/A' }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Số lượng vé</label>
                            <div>{{ $ticket->booking->quantity ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Tổng giá trị</label>
                            <div class="text-success">
                                <strong>{{ number_format($ticket->booking->total_amount ?? 0, 0, ',', '.') }}đ</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Trạng thái thanh toán</label>
                            <div>
                                @if($ticket->booking->payment_status === 'paid')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @elseif($ticket->booking->payment_status === 'pending')
                                    <span class="badge bg-warning">Chờ thanh toán</span>
                                @else
                                    <span class="badge bg-secondary">{{ $ticket->booking->payment_status ?? 'N/A' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif --}}
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- QR Code -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-qr-code"></i> Mã QR</h5>
                </div>
                <div class="card-body text-center">
                    @if($ticket->qr_code)
                        <div class="p-3 bg-white border rounded d-inline-block mt-3">
                            {!! QrCode::size(200)->generate($ticket->qr_code) !!}
                        </div>
                        <p class="mt-3 mb-2 small text-muted">{{ $ticket->attendee_name }}</p>
                        <p class="small text-muted">{{ $ticket->ticket_code }}</p>
                        <button class="btn btn-outline-primary btn-sm" onclick="downloadQR()">
                            <i class="bi bi-download"></i> Tải xuống QR
                        </button>
                    @else
                        <div class="text-muted py-5">
                            <i class="bi bi-qr-code fs-1 opacity-25"></i>
                            <p class="mt-2">Không có mã QR</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear"></i> Thao tác</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Chỉnh sửa vé
                        </a>
                        
                        @if($ticket->status === 'active' && !$ticket->checked_in_at)
                            <button type="button" 
                                    class="btn btn-success"
                                    onclick="confirmCheckin({{ $ticket->id }}, '{{ $ticket->ticket_code }}')">
                                <i class="bi bi-box-arrow-in-right"></i> Check-in
                            </button>
                        @endif

                        @if($ticket->qr_code)
                            <button class="btn btn-outline-secondary" onclick="window.print()">
                                <i class="bi bi-printer"></i> In vé
                            </button>
                        @endif

                        @if($ticket->status !== 'used')
                            <button type="button" 
                                    class="btn btn-outline-danger"
                                    onclick="confirmDelete({{ $ticket->id }}, '{{ $ticket->ticket_code }}')">
                                <i class="bi bi-trash"></i> Xóa vé
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms -->
<form id="checkin-form-{{ $ticket->id }}" 
      action="{{ route('tickets.checkin', $ticket->id) }}" 
      method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="delete-form-{{ $ticket->id }}" 
      action="{{ route('tickets.destroy', $ticket->id) }}" 
      method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Đã sao chép!',
            text: 'Mã vé đã được sao chép vào clipboard',
            timer: 1500,
            showConfirmButton: false
        });
    });
}

// Confirm check-in
function confirmCheckin(ticketId, ticketCode) {
    Swal.fire({
        title: 'Xác nhận check-in?',
        text: `Bạn có chắc chắn muốn check-in vé "${ticketCode}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-check-circle"></i> Check-in',
        cancelButtonText: 'Hủy bỏ',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Đang xử lý...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            document.getElementById('checkin-form-' + ticketId).submit();
        }
    });
}

// Confirm delete
function confirmDelete(ticketId, ticketCode) {
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
}

// Download QR Code
function downloadQR() {
    const svg = document.querySelector('svg');
    const svgData = new XMLSerializer().serializeToString(svg);
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const img = new Image();
    
    img.onload = () => {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);
        const pngFile = canvas.toDataURL('image/png');
        const downloadLink = document.createElement('a');
        downloadLink.download = 'qr-code-{{ $ticket->ticket_code }}.png';
        downloadLink.href = pngFile;
        downloadLink.click();
    };
    
    img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
}

// Show success message if redirected from action
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Lỗi!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>

<style>
@media print {
    .btn-group, .card-header, nav, .btn {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>