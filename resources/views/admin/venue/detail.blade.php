@extends('layouts/admin')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Chi tiết địa điểm</h5>
            <div>
                @if($venue->is_active)
                    <span class="badge bg-success me-2">Đang hoạt động</span>
                @else
                    <span class="badge bg-secondary me-2">Tạm dừng</span>
                @endif
                <div class="btn-group" role="group">
                    <a href="{{ route('venues.edit', $venue) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Chỉnh sửa
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <!-- Thông tin cơ bản -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong>Tên địa điểm:</strong>
                            </div>
                            <div class="col-sm-9">
                                <span class="h5">{{ $venue->name }}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong>Địa chỉ:</strong>
                            </div>
                            <div class="col-sm-9">
                                <address class="mb-0">
                                    {{ $venue->address }}
                                </address>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong>Thành phố:</strong>
                            </div>
                            <div class="col-sm-9">
                                <i class="bi bi-geo-alt text-primary me-1"></i>{{ $venue->city }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <strong>Sức chứa:</strong>
                            </div>
                            <div class="col-sm-9">
                                @if($venue->capacity)
                                    <i class="bi bi-people text-info me-1"></i>
                                    <span class="badge bg-info">{{ number_format($venue->capacity) }} người</span>
                                @else
                                    <span class="text-muted">Không giới hạn</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin liên hệ -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-telephone me-2"></i>Thông tin liên hệ
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong class="d-block">Số điện thoại:</strong>
                                    @if($venue->phone)
                                        <a href="tel:{{ $venue->phone }}" class="text-decoration-none">
                                            <i class="bi bi-telephone-fill text-success me-1"></i>
                                            {{ $venue->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">Chưa cung cấp</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong class="d-block">Email:</strong>
                                    @if($venue->email)
                                        <a href="mailto:{{ $venue->email }}" class="text-decoration-none">
                                            <i class="bi bi-envelope-fill text-primary me-1"></i>
                                            {{ $venue->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">Chưa cung cấp</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sự kiện liên quan (nếu có) -->
                @if(isset($venue->events) && $venue->events->count() > 0)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-calendar-event me-2"></i>Sự kiện tại địa điểm này
                        </h6>
                        <span class="badge bg-primary">{{ $venue->events->count() }} sự kiện</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tên sự kiện</th>
                                        <th>Ngày diễn ra</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($venue->events->take(5) as $event)
                                    <tr>
                                        <td>{{ $event->title }}</td>
                                        <td>{{ $event->start_datetime ? \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') : 'Chưa xác định' }}</td>
                                        <td>
                                            @if($event->status == 'active')
                                                <span class="badge bg-success">Hoạt động</span>
                                            @elseif($event->status == 'cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @else
                                                <span class="badge bg-warning">Chờ xử lý</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($venue->events->count() > 5)
                            <div class="text-center">
                                <a href="{{ route('events.index', ['venue_id' => $venue->id]) }}" class="btn btn-sm btn-outline-secondary">
                                    Xem tất cả {{ $venue->events->count() }} sự kiện
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-4">
                <!-- Thông tin hệ thống -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-gear me-2"></i>Thông tin hệ thống
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong class="d-block">Trạng thái:</strong>
                            @if($venue->is_active)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Đang hoạt động
                                </span>
                                <small class="text-muted d-block mt-1">
                                    Hiển thị trong danh sách chọn khi tạo sự kiện
                                </small>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-pause-circle me-1"></i>Tạm dừng
                                </span>
                                <small class="text-muted d-block mt-1">
                                    Không hiển thị trong danh sách chọn
                                </small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong class="d-block">Ngày tạo:</strong>
                            <span class="text-muted">{{ $venue->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="mb-3">
                            <strong class="d-block">Cập nhật lần cuối:</strong>
                            <span class="text-muted">{{ $venue->updated_at->format('d/m/Y H:i') }}</span>
                        </div>

                        @if($venue->created_by)
                        <div class="mb-3">
                            <strong class="d-block">Người tạo:</strong>
                            <span class="text-muted">{{ $venue->creator->name ?? 'N/A' }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Thống kê nhanh -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-bar-chart me-2"></i>Thống kê
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary mb-0">{{ $venue->events_count ?? 0 }}</h4>
                                    <small class="text-muted">Tổng sự kiện</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-0">{{ $venue->active_events_count ?? 0 }}</h4>
                                <small class="text-muted">Đang diễn ra</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thao tác -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('venues.edit', $venue) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Chỉnh sửa thông tin
                            </a>
                            
                            <a href="{{ route('events.create', ['venue_id' => $venue->id]) }}" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Tạo sự kiện tại đây
                            </a>
                            
                            <hr>
                            
                            <a href="{{ route('venues.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Cảnh báo!</strong> Bạn có chắc chắn muốn xóa địa điểm <strong>"{{ $venue->name }}"</strong>?
                </div>
                
                @if(isset($venue->events) && $venue->events->count() > 0)
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle me-2"></i>
                    Địa điểm này đang có <strong>{{ $venue->events->count() }} sự kiện</strong> liên quan. 
                    Vui lòng xử lý các sự kiện trước khi xóa.
                </div>
                @else
                <p class="mb-0">Thao tác này không thể hoàn tác. Tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                @if(!isset($venue->events) || $venue->events->count() == 0)
                <form action="{{ route('venues.destroy', $venue) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Xóa vĩnh viễn
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Copy contact info to clipboard
document.addEventListener('DOMContentLoaded', function() {
    // Add copy functionality for phone and email
    const phoneLink = document.querySelector('a[href^="tel:"]');
    const emailLink = document.querySelector('a[href^="mailto:"]');
    
    if (phoneLink) {
        phoneLink.addEventListener('click', function(e) {
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();
                navigator.clipboard.writeText(this.textContent.trim());
                showToast('Đã copy số điện thoại');
            }
        });
    }
    
    if (emailLink) {
        emailLink.addEventListener('click', function(e) {
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();
                navigator.clipboard.writeText(this.textContent.trim());
                showToast('Đã copy email');
            }
        });
    }
});

function showToast(message) {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.className = 'position-fixed top-0 end-0 p-3';
    toast.style.zIndex = '1050';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="bi bi-check-circle text-success me-2"></i>
                <strong class="me-auto">Thành công</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Confirm before toggle status
document.querySelectorAll('form[action*="toggle-status"]').forEach(form => {
    form.addEventListener('submit', function(e) {
        const button = this.querySelector('button[type="submit"]');
        const action = button.textContent.includes('Tạm dừng') ? 'tạm dừng' : 'kích hoạt lại';
        
        if (!confirm(`Bạn có chắc chắn muốn ${action} địa điểm này?`)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection