@extends('layouts/admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">Chỉnh sửa loại vé</h5>
                    <small class="text-muted">Cập nhật thông tin loại vé: {{ $ticketType->name }}</small>
                </div>

                <div class="btn-group" role="group">
                    <a href="{{ route('ticket-types.show', $ticketType->id) }}" class="btn btn-outline-info">
                        <i class="bi bi-eye"></i> Xem chi tiết
                    </a>
                    <a href="{{ route('ticket-types.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Có lỗi xảy ra:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $remaining = $ticketType->quantity_total - $ticketType->quantity_sold;
                $soldPercentage = $ticketType->quantity_total > 0 ? ($ticketType->quantity_sold / $ticketType->quantity_total) * 100 : 0;
            @endphp

            <!-- Thông tin hiện tại -->
            @if($ticketType->quantity_sold > 0)
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle fs-4 me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading">Lưu ý khi chỉnh sửa</h6>
                            <p class="mb-2">Loại vé này đã có <strong>{{ number_format($ticketType->quantity_sold) }} vé được bán</strong> ({{ number_format($soldPercentage, 1) }}% tổng số vé).</p>
                            <small class="text-muted">
                                • Một số thông tin có thể bị hạn chế chỉnh sửa để đảm bảo tính nhất quán với các vé đã bán<br>
                                • Hãy cân nhắc kỹ trước khi thay đổi giá hoặc số lượng vé
                            </small>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('ticket-types.update', $ticketType->id) }}" method="POST" id="ticketTypeForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Thông tin cơ bản -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">
                                            Tên loại vé <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $ticketType->name) }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="event_id" class="form-label">
                                            Sự kiện <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('event_id') is-invalid @enderror" 
                                                id="event_id" 
                                                name="event_id" 
                                                required
                                                {{ $ticketType->quantity_sold > 0 ? 'disabled' : '' }}>
                                            <option value="">Chọn sự kiện</option>
                                            @foreach($events as $event)
                                                <option value="{{ $event->id }}" 
                                                        {{ old('event_id', $ticketType->event_id) == $event->id ? 'selected' : '' }}>
                                                    {{ $event->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($ticketType->quantity_sold > 0)
                                            <input type="hidden" name="event_id" value="{{ $ticketType->event_id }}">
                                            <small class="text-muted">Không thể thay đổi sự kiện vì đã có vé được bán</small>
                                        @endif
                                        @error('event_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">
                                            Giá bán (VNĐ) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" 
                                                   name="price" 
                                                   value="{{ old('price', $ticketType->price) }}" 
                                                   min="0" 
                                                   step="1000"
                                                   required>
                                            <span class="input-group-text">VNĐ</span>
                                        </div>
                                        @if($ticketType->quantity_sold > 0)
                                            <small class="text-warning">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                Cân nhắc kỹ khi thay đổi giá vì đã có {{ $ticketType->quantity_sold }} vé được bán
                                            </small>
                                        @endif
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="max_per_order" class="form-label">
                                            Giới hạn mua/đơn hàng <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               class="form-control @error('max_per_order') is-invalid @enderror" 
                                               id="max_per_order" 
                                               name="max_per_order" 
                                               value="{{ old('max_per_order', $ticketType->max_per_order) }}" 
                                               min="1" 
                                               max="50"
                                               required>
                                        <small class="text-muted">Số vé tối đa một khách hàng có thể mua trong một đơn hàng</small>
                                        @error('max_per_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <label for="sale_end" class="form-label">
                                            Ngày kết thúc bán vé
                                        </label>
                                        <input type="datetime-local" 
                                               class="form-control @error('sale_end') is-invalid @enderror" 
                                               id="sale_end" 
                                               name="sale_end" 
                                               value="{{ old('sale_end', $ticketType->sale_end ? \Carbon\Carbon::parse($ticketType->sale_end)->format('Y-m-d\TH:i') : '') }}">
                                        <small class="text-muted">Để trống nếu không giới hạn thời gian bán</small>
                                        @error('sale_end')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quản lý số lượng & trạng thái -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-gear me-2"></i>Số lượng & Trạng thái
                                </h6>
                            </div>
                            <div class="card-body">
                                <!-- Thống kê hiện tại -->
                                <div class="mb-3 p-3 bg-light rounded">
                                    <h6 class="mb-2">Thống kê hiện tại</h6>
                                    <div class="d-flex justify-content-between mb-1">
                                        <small>Tổng vé:</small>
                                        <strong>{{ number_format($ticketType->quantity_total) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <small>Đã bán:</small>
                                        <strong class="text-info">{{ number_format($ticketType->quantity_sold) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <small>Còn lại:</small>
                                        <strong class="text-success">{{ number_format($remaining) }}</strong>
                                    </div>
                                    @if($ticketType->quantity_total > 0)
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-info" 
                                                 style="width: {{ $soldPercentage }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ number_format($soldPercentage, 1) }}% đã bán</small>
                                    @endif
                                </div>

                                <!-- Số lượng tổng -->
                                <div class="mb-3">
                                    <label for="quantity_total" class="form-label">
                                        Tổng số vé <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('quantity_total') is-invalid @enderror" 
                                           id="quantity_total" 
                                           name="quantity_total" 
                                           value="{{ old('quantity_total', $ticketType->quantity_total) }}" 
                                           min="{{ $ticketType->quantity_sold }}"
                                           required>
                                    @if($ticketType->quantity_sold > 0)
                                        <small class="text-warning">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Không thể nhỏ hơn {{ number_format($ticketType->quantity_sold) }} (số vé đã bán)
                                        </small>
                                    @endif
                                    @error('quantity_total')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <input type="hidden" name="quantity_sold" value="{{ $ticketType->quantity_sold }}">
                                <!-- Trạng thái hoạt động -->
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <input type="hidden" name="is_active" value="0">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               role="switch" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', $ticketType->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            <span class="active-label">{{ $ticketType->is_active ? 'Đang hoạt động' : 'Tạm dừng' }}</span>
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Tắt để tạm dừng bán loại vé này. Khách hàng sẽ không thể mua vé khi trạng thái là "Tạm dừng"
                                    </small>
                                </div>

                                <!-- Dự báo doanh thu -->
                                <div class="p-3 bg-success bg-opacity-10 rounded">
                                    <h6 class="text-success mb-2">
                                        <i class="bi bi-cash me-1"></i>Doanh thu
                                    </h6>
                                    <div class="d-flex justify-content-between mb-1">
                                        <small>Hiện tại:</small>
                                        <strong class="text-success" id="current-revenue">
                                            {{ number_format($ticketType->price * $ticketType->quantity_sold, 0, ',', '.') }}đ
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small>Dự kiến tối đa:</small>
                                        <strong class="text-success" id="max-revenue">
                                            {{ number_format($ticketType->price * $ticketType->quantity_total, 0, ',', '.') }}đ
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nút hành động -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('ticket-types.show', $ticketType->id) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Hủy bỏ
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Cập nhật loại vé
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price');
    const quantityInput = document.getElementById('quantity_total');
    const isActiveSwitch = document.getElementById('is_active');
    const activeLabel = document.querySelector('.active-label');
    const currentRevenueEl = document.getElementById('current-revenue');
    const maxRevenueEl = document.getElementById('max-revenue');
    const quantitySold = {{ $ticketType->quantity_sold }};

    // Cập nhật label trạng thái
    function updateActiveLabel() {
        if (isActiveSwitch.checked) {
            activeLabel.textContent = 'Đang hoạt động';
            activeLabel.className = 'text-success';
        } else {
            activeLabel.textContent = 'Tạm dừng';
            activeLabel.className = 'text-secondary';
        }
    }

    // Cập nhật doanh thu khi thay đổi giá hoặc số lượng
    function updateRevenue() {
        const price = parseInt(priceInput.value) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        
        const currentRevenue = price * quantitySold;
        const maxRevenue = price * quantity;
        
        currentRevenueEl.textContent = new Intl.NumberFormat('vi-VN').format(currentRevenue) + 'đ';
        maxRevenueEl.textContent = new Intl.NumberFormat('vi-VN').format(maxRevenue) + 'đ';
    }

    // Event listeners
    isActiveSwitch.addEventListener('change', updateActiveLabel);
    priceInput.addEventListener('input', updateRevenue);
    quantityInput.addEventListener('input', updateRevenue);

    // Validation cho số lượng
    quantityInput.addEventListener('blur', function() {
        const value = parseInt(this.value);
        if (value < quantitySold) {
            this.value = quantitySold;
            updateRevenue();
            
            // Hiển thị cảnh báo
            const alert = document.createElement('div');
            alert.className = 'alert alert-warning alert-dismissible fade show mt-2';
            alert.innerHTML = `
                <i class="bi bi-exclamation-triangle me-2"></i>
                Số lượng vé không thể nhỏ hơn ${quantitySold.toLocaleString('vi-VN')} (số vé đã bán)
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            this.parentNode.appendChild(alert);
            
            // Tự động ẩn sau 3 giây
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 3000);
        }
    });

    // Format giá khi nhập
    priceInput.addEventListener('blur', function() {
        const value = parseInt(this.value);
        if (value && value > 0) {
            // Làm tròn về bội số của 1000
            const rounded = Math.round(value / 1000) * 1000;
            this.value = rounded;
            updateRevenue();
        }
    });

    // Khởi tạo
    updateActiveLabel();
    updateRevenue();

    // Xác nhận khi submit form nếu có thay đổi quan trọng
    document.getElementById('ticketTypeForm').addEventListener('submit', function(e) {
        const originalPrice = {{ $ticketType->price }};
        const currentPrice = parseInt(priceInput.value);
        const hasSoldTickets = quantitySold > 0;
        
        if (hasSoldTickets && originalPrice !== currentPrice) {
            e.preventDefault();
            
            if (confirm(`Bạn đang thay đổi giá từ ${originalPrice.toLocaleString('vi-VN')}đ thành ${currentPrice.toLocaleString('vi-VN')}đ.\n\nĐã có ${quantitySold} vé được bán với giá cũ. Bạn có chắc chắn muốn tiếp tục?`)) {
                this.submit();
            }
        }
    });
});
</script>

<style>
.bg-opacity-10 {
    background-color: rgba(var(--bs-success-rgb), 0.1) !important;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.progress {
    background-color: rgba(0, 0, 0, 0.1);
}

.alert {
    border: none;
    border-left: 4px solid;
}

.alert-info {
    border-left-color: #0dcaf0;
    background-color: rgba(13, 202, 240, 0.1);
}

.alert-warning {
    border-left-color: #ffc107;
    background-color: rgba(255, 193, 7, 0.1);
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
}
</style>