@extends('layouts/admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">Chi tiết loại vé</h5>
                    <small class="text-muted">Thông tin chi tiết về loại vé {{ $ticketType->name }}</small>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('ticket-types.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                    <a href="{{ route('ticket-types.edit', $ticketType->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @php
                $remaining = $ticketType->quantity_total - $ticketType->quantity_sold;
                $soldPercentage = $ticketType->quantity_total > 0 ? ($ticketType->quantity_sold / $ticketType->quantity_total) * 100 : 0;
                $remainingPercentage = 100 - $soldPercentage;
            @endphp

            <!-- Trạng thái tổng quan -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert {{ $ticketType->is_active ? 
                        ($ticketType->sale_end && \Carbon\Carbon::parse($ticketType->sale_end)->isPast() ? 'alert-warning' : 
                         ($remaining <= 0 ? 'alert-danger' : 'alert-success')) : 'alert-secondary' }} d-flex align-items-center">
                        <i class="bi {{ $ticketType->is_active ? 
                            ($ticketType->sale_end && \Carbon\Carbon::parse($ticketType->sale_end)->isPast() ? 'bi-clock-history' : 
                             ($remaining <= 0 ? 'bi-x-circle' : 'bi-check-circle')) : 'bi-pause-circle' }} fs-4 me-3"></i>
                        <div>
                            <h6 class="mb-1">
                                @if($ticketType->is_active)
                                    @if($ticketType->sale_end && \Carbon\Carbon::parse($ticketType->sale_end)->isPast())
                                        Loại vé đã hết hạn bán
                                    @elseif($remaining <= 0)
                                        Loại vé đã hết
                                    @else
                                        Loại vé đang được bán
                                    @endif
                                @else
                                    Loại vé đã tạm dừng bán
                                @endif
                            </h6>
                            <small class="mb-0">
                                @if($ticketType->sale_end && \Carbon\Carbon::parse($ticketType->sale_end)->isPast())
                                    Ngừng bán từ {{ \Carbon\Carbon::parse($ticketType->sale_end)->format('d/m/Y H:i') }}
                                @elseif($remaining <= 0)
                                    Đã bán hết {{ number_format($ticketType->quantity_total) }} vé
                                @elseif(!$ticketType->is_active)
                                    Loại vé này hiện đang được tạm dừng bán
                                @else
                                    Còn {{ number_format($remaining) }} vé trong tổng số {{ number_format($ticketType->quantity_total) }} vé
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Thông tin cơ bản -->
                <div class="col-md-8">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Tên loại vé</label>
                                    <div class="fw-bold fs-5 text-primary">{{ $ticketType->name }}</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Sự kiện</label>
                                    <div class="fw-semibold">
                                        @if($ticketType->event)
                                            <a href="#" class="text-decoration-none">{{ $ticketType->event->title }}</a>
                                        @else
                                            <span class="text-muted">Chưa có sự kiện</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Giá bán</label>
                                    <div class="fw-bold fs-4 text-success">
                                        {{ number_format($ticketType->price, 0, ',', '.') }}<small class="text-muted">đ</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Giới hạn mua/đơn hàng</label>
                                    <div class="fw-semibold">
                                        <span class="badge bg-secondary fs-6">{{ $ticketType->max_per_order }}</span> vé/đơn
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Ngày kết thúc bán</label>
                                    <div class="fw-semibold">
                                        @if($ticketType->sale_end)
                                            {{ \Carbon\Carbon::parse($ticketType->sale_end)->format('d/m/Y H:i') }}
                                            @if(\Carbon\Carbon::parse($ticketType->sale_end)->isPast())
                                                <span class="badge bg-danger ms-2">Đã hết hạn</span>
                                            @elseif(\Carbon\Carbon::parse($ticketType->sale_end)->diffInDays() <= 3)
                                                <span class="badge bg-warning ms-2">Sắp hết hạn</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Không giới hạn</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Trạng thái hoạt động</label>
                                    <div>
                                        @if($ticketType->is_active)
                                            <span class="badge bg-success fs-6">
                                                <i class="bi bi-check-circle me-1"></i>Đang hoạt động
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">
                                                <i class="bi bi-pause-circle me-1"></i>Tạm dừng
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê bán vé -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-graph-up me-2"></i>Thống kê bán vé
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Progress bar -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Tiến độ bán vé</span>
                                    <span class="fw-bold">{{ number_format($soldPercentage, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 12px;">
                                    <div class="progress-bar bg-info" style="width: {{ $soldPercentage }}%"></div>
                                </div>
                            </div>

                            <!-- Số liệu thống kê -->
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-center border rounded p-2">
                                        <div class="fs-4 fw-bold text-primary">{{ number_format($ticketType->quantity_total) }}</div>
                                        <small class="text-muted">Tổng vé</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center border rounded p-2">
                                        <div class="fs-4 fw-bold text-info">{{ number_format($ticketType->quantity_sold) }}</div>
                                        <small class="text-muted">Đã bán</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center border rounded p-2">
                                        <div class="fs-4 fw-bold {{ $remaining <= 10 ? 'text-danger' : ($remaining <= 50 ? 'text-warning' : 'text-success') }}">
                                            {{ number_format($remaining) }}
                                        </div>
                                        <small class="text-muted">Còn lại</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center border rounded p-2">
                                        <div class="fs-4 fw-bold text-success">
                                            {{ number_format($ticketType->price * $ticketType->quantity_sold, 0, ',', '.') }}<small>đ</small>
                                        </div>
                                        <small class="text-muted">Doanh thu</small>
                                    </div>
                                </div>
                            </div>

                            @if($remaining <= 10 && $remaining > 0)
                                <div class="alert alert-warning mt-3 py-2">
                                    <small>
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Sắp hết vé! Chỉ còn {{ $remaining }} vé
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin thời gian -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-clock-history me-2"></i>Lịch sử thời gian
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Ngày tạo</label>
                                    <div class="fw-semibold">
                                        {{ $ticketType->created_at ? \Carbon\Carbon::parse($ticketType->created_at)->format('d/m/Y H:i:s') : 'N/A' }}
                                    </div>
                                    @if($ticketType->created_at)
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($ticketType->created_at)->diffForHumans() }}</small>
                                    @endif
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Cập nhật lần cuối</label>
                                    <div class="fw-semibold">
                                        {{ $ticketType->updated_at ? \Carbon\Carbon::parse($ticketType->updated_at)->format('d/m/Y H:i:s') : 'N/A' }}
                                    </div>
                                    @if($ticketType->updated_at)
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($ticketType->updated_at)->diffForHumans() }}</small>
                                    @endif
                                </div>
                                
                                <div class="col-md-4">
                                    @if($ticketType->sale_end)
                                        <label class="form-label text-muted">Thời gian còn lại để bán</label>
                                        <div class="fw-semibold">
                                            @if(\Carbon\Carbon::parse($ticketType->sale_end)->isPast())
                                                <span class="text-danger">Đã kết thúc</span>
                                            @else
                                                <span class="text-success">{{ \Carbon\Carbon::parse($ticketType->sale_end)->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nút hành động -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('ticket-types.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('ticket-types.edit', $ticketType->id) }}" class="btn btn-primary">
                                <i class="bi bi-pencil me-1"></i>Chỉnh sửa
                            </a>
                            @if($ticketType->quantity_sold == 0)
                                <button type="button" 
                                        class="btn btn-outline-danger delete-btn" 
                                        data-ticket-type-id="{{ $ticketType->id }}"
                                        data-ticket-type-name="{{ $ticketType->name }}">
                                    <i class="bi bi-trash me-1"></i>Xóa
                                </button>
                            @else
                                <button type="button" class="btn btn-outline-danger" disabled title="Không thể xóa vì đã có người mua">
                                    <i class="bi bi-trash me-1"></i>Xóa
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form xóa ẩn -->
    <form id="delete-form-{{ $ticketType->id }}" 
          action="{{ route('ticket-types.destroy', $ticketType->id) }}" 
          method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButton = document.querySelector('.delete-btn');
    
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            const ticketTypeId = this.getAttribute('data-ticket-type-id');
            const ticketTypeName = this.getAttribute('data-ticket-type-name');
            
            Swal.fire({
                title: 'Xác nhận xóa loại vé?',
                text: `Bạn có chắc chắn muốn xóa loại vé "${ticketTypeName}"? Hành động này không thể hoàn tác.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa loại vé',
                cancelButtonText: 'Hủy bỏ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Đang xóa loại vé...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('delete-form-' + ticketTypeId).submit();
                }
            });
        });
    }
});
</script>