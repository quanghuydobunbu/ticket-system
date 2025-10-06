@extends('layouts/admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Chi tiết đơn hàng #{{ $booking->booking_code }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Đơn hàng</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            {{-- <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a> --}}
            <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                    @if($booking->status == 'confirmed')
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Đã thanh toán
                        </span>
                    @elseif($booking->status == 'pending')
                        <span class="badge bg-warning">
                            <i class="bi bi-clock me-1"></i>Chờ thanh toán
                        </span>
                    @elseif($booking->status == 'cancelled')
                        <span class="badge bg-danger">
                            <i class="bi bi-x-circle me-1"></i>Đã hủy
                        </span>
                    @else
                        <span class="badge bg-info">
                            <i class="bi bi-arrow-return-left me-1"></i>Hoàn tiền
                        </span>
                    @endif
                </div>
                <div class="card-body mt-3">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Mã đơn hàng</p>
                            <h6>{{ $booking->booking_code }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Ngày đặt</p>
                            <h6>{{ $booking->created_at ?? ''}}</h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        {{-- <div class="col-md-6">
                            <p class="text-muted mb-1">Hạn thanh toán</p>
                            <h6>
                                @if($booking->expires_at)
                                    {{ \Carbon\Carbon::parse($booking->expires_at)->format('d/m/Y H:i') }}
                                    @if($booking->status == 'pending' && \Carbon\Carbon::now()->gt($booking->expires_at))
                                        <span class="badge bg-danger ms-2">Đã hết hạn</span>
                                    @endif
                                @else
                                    <span class="text-muted">Không giới hạn</span>
                                @endif
                            </h6>
                        </div> --}}
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Ngày xác nhận</p>
                            <h6>
                                @if($booking->confirmed_at)
                                    {{ \Carbon\Carbon::parse($booking->confirmed_at)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">Chưa xác nhận</span>
                                @endif
                            </h6>
                        </div>
                    </div>

                    <hr>

                    <!-- Thông tin sự kiện -->
                    <h6 class="mb-3">Thông tin sự kiện</h6>
                    <div class="d-flex align-items-start mb-3">
                        @if($booking->event->featured_image ?? '')
                            <img src="{{ asset('storage/events/' . $booking->event->featured_image) }}" 
                                 alt="{{ $booking->event->title }}" 
                                 class="rounded me-3" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                                
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $booking->event->title ?? 'N/A' }}</h5>
                            <p class="text-muted mb-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $booking->event->start_datetime ? \Carbon\Carbon::parse($booking->event->start_datetime)->format('d/m/Y H:i') : 'N/A' }}
                            </p>
                            <p class="text-muted mb-0">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $booking->event->venue->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <hr>

                    <!-- Chi tiết vé -->
                    <h6 class="mb-3">Chi tiết vé</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Loại vé</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->items as $item)
                                    <tr>
                                        <td>{{ $item->ticketType->name ?? 'N/A' }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->unit_price, 0, ',', '.') }}đ</td>
                                        <td class="text-end">{{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tổng tiền:</strong></td>
                                    <td class="text-end"><strong>{{ number_format($booking->total_amount, 0, ',', '.') }}đ</strong></td>
                                </tr>
                                @if($booking->total_amount != $booking->final_amount)
                                    {{-- <tr>
                                        <td colspan="3" class="text-end text-success">Giảm giá:</td>
                                        <td class="text-end text-success">-{{ number_format($booking->total_amount - $booking->final_amount, 0, ',', '.') }}đ</td>
                                    </tr> --}}
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tổng thanh toán:</strong></td>
                                        <td class="text-end"><strong class="text-primary">{{ number_format($booking->final_amount, 0, ',', '.') }}đ</strong></td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Thông tin khách hàng -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mt-3">
                        <img src="{{ asset('storage/avatars/' . $booking->user->avatar_url) }}" alt="avatar" class="rounded-circle" width="32" height="32">
    
                        <div class="mx-3">
                            <h6 class="mb-0">{{ $booking->user->name ?? 'N/A' }}</h6>
                            <small class="text-muted">Khách hàng</small>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Email</small>
                        <p class="mb-0">{{ $booking->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Số điện thoại</small>
                        <p class="mb-0">{{ $booking->user->phone ?? 'Chưa cập nhật' }}</p>
                    </div>
                </div>
            </div>

            <!-- Hành động -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hành động</h5>
                </div>
                <div class="card-body">
                    @if($booking->status == 'pending')
                        <button type="button" class="btn btn-success w-100 mb-2" onclick="confirmPayment({{ $booking->id }})">
                            <i class="bi bi-check-circle me-1"></i> Xác nhận thanh toán
                        </button>
                        <button type="button" class="btn btn-danger w-100 mb-2" onclick="cancelBooking({{ $booking->id }})">
                            <i class="bi bi-x-circle me-1"></i> Hủy đơn hàng
                        </button>
                    @elseif($booking->status == 'confirmed')
                        <button type="button" class="btn btn-info w-100 mb-2" onclick="refundBooking({{ $booking->id }})">
                            <i class="bi bi-arrow-return-left me-1"></i> Hoàn tiền
                        </button>
                    @endif
                    
                    <button type="button" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-printer me-1"></i> In hóa đơn
                    </button>
                    
                    <button type="button" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-envelope me-1"></i> Gửi email xác nhận
                    </button>
                    
                    <hr>
                    
                    <button type="button" class="btn btn-outline-danger w-100 delete-btn" 
                            data-user-id="{{ $booking->id }}"
                            data-user-name="{{ $booking->booking_code }}">
                        <i class="bi bi-trash me-1"></i> Xóa đơn hàng
                    </button>
                    
                    <form id="delete-form-{{ $booking->id }}" 
                          action="{{ route('bookings.destroy', $booking->id) }}" 
                          method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Xác nhận thanh toán
function confirmPayment(bookingId) {
    Swal.fire({
        title: 'Xác nhận thanh toán?',
        text: 'Bạn có chắc chắn đơn hàng này đã được thanh toán?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Xác nhận',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Gửi request xác nhận thanh toán
            fetch(`/admin/bookings/${bookingId}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Thành công!', 'Đơn hàng đã được xác nhận.', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Lỗi!', data.message || 'Có lỗi xảy ra.', 'error');
                }
            });
        }
    });
}

// Hủy đơn hàng
function cancelBooking(bookingId) {
    Swal.fire({
        title: 'Hủy đơn hàng?',
        text: 'Bạn có chắc chắn muốn hủy đơn hàng này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Hủy đơn',
        cancelButtonText: 'Quay lại'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/bookings/${bookingId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Đã hủy!', 'Đơn hàng đã được hủy.', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Lỗi!', data.message || 'Có lỗi xảy ra.', 'error');
                }
            });
        }
    });
}

// Hoàn tiền
function refundBooking(bookingId) {
    Swal.fire({
        title: 'Hoàn tiền?',
        text: 'Bạn có chắc chắn muốn hoàn tiền cho đơn hàng này?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Hoàn tiền',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/bookings/${bookingId}/refund`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Thành công!', 'Đơn hàng đã được hoàn tiền.', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Lỗi!', data.message || 'Có lỗi xảy ra.', 'error');
                }
            });
        }
    });
}

// Xóa đơn hàng
document.addEventListener('DOMContentLoaded', function() {
    const deleteBtn = document.querySelector('.delete-btn');
    
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            
            Swal.fire({
                title: 'Xác nhận xóa?',
                text: `Bạn có chắc chắn muốn xóa đơn hàng "${userName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Đang xóa...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        });
    }
});
</script>
@endsection