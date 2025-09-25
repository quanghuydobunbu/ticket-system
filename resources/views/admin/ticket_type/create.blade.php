@extends('layouts/admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-ticket-detailed"></i> Tạo loại vé mới
                            </h5>
                            <a href="{{ route('ticket-types.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại danh sách
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h6><i class="bi bi-exclamation-triangle"></i> Có lỗi xảy ra:</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('ticket-types.store') }}" method="POST" id="ticketTypeForm">
                            @csrf
                            
                            <div class="row">
                                <!-- Cột trái -->
                                <div class="col-md-6">
                                    <!-- Thông tin cơ bản -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="bi bi-info-circle"></i> Thông tin cơ bản
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- Sự kiện -->
                                            <div class="mb-3">
                                                <label for="event_id" class="form-label">
                                                    Sự kiện <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select @error('event_id') is-invalid @enderror" 
                                                        id="event_id" name="event_id">
                                                    <option value="">-- Chọn sự kiện --</option>
                                                    @foreach($events as $event)
                                                        <option value="{{ $event->id }}" 
                                                                {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                                            {{ $event->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('event_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Tên loại vé -->
                                            <div class="mb-3">
                                                <label for="name" class="form-label">
                                                    Tên loại vé <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" name="name" 
                                                       value="{{ old('name') }}" 
                                                       placeholder="VD: Vé VIP, Vé thường, Early Bird..."
                                                       required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Giá vé -->
                                            <div class="mb-3">
                                                <label for="price" class="form-label">
                                                    Giá bán (VNĐ) <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" 
                                                           class="form-control @error('price') is-invalid @enderror" 
                                                           id="price" name="price" 
                                                           value="{{ old('price') }}" 
                                                           placeholder="500000"
                                                           min="0" step="1000" required>
                                                    <span class="input-group-text">đ</span>
                                                </div>
                                                <div class="form-text">
                                                    <span id="priceDisplay" class="text-muted"></span>
                                                </div>
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Số lượng và giới hạn -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="bi bi-calculator"></i> Số lượng & Giới hạn
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- Tổng số lượng -->
                                            <div class="mb-3">
                                                <label for="quantity_total" class="form-label">
                                                    Tổng số lượng vé <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" 
                                                       class="form-control @error('quantity_total') is-invalid @enderror" 
                                                       id="quantity_total" name="quantity_total" 
                                                       value="{{ old('quantity_total') }}" 
                                                       placeholder="100"
                                                       min="1" required>
                                                <div class="form-text">Số lượng vé có thể bán cho loại vé này</div>
                                                @error('quantity_total')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Số lượng đã bán (ẩn, mặc định 0) -->
                                            <input type="hidden" name="quantity_sold" value="0">

                                            <!-- Giới hạn mỗi đơn hàng -->
                                            <div class="mb-3">
                                                <label for="max_per_order" class="form-label">
                                                    Số lượng tối đa mỗi đơn hàng
                                                </label>
                                                <input type="number" 
                                                       class="form-control @error('max_per_order') is-invalid @enderror" 
                                                       id="max_per_order" name="max_per_order" 
                                                       value="{{ old('max_per_order', 5) }}" 
                                                       placeholder="5"
                                                       min="1">
                                                <div class="form-text">Khách hàng có thể mua tối đa bao nhiêu vé loại này trong 1 đơn</div>
                                                @error('max_per_order')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cột phải -->
                                <div class="col-md-6">
                                    <!-- Thời gian bán -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="bi bi-clock"></i> Thời gian bán hàng
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- Ngày ngừng bán -->
                                            <div class="mb-3">
                                                <label for="sale_end" class="form-label">
                                                    Ngưng bán vào
                                                </label>
                                                <input type="datetime-local" 
                                                       class="form-control @error('sale_end') is-invalid @enderror" 
                                                       id="sale_end" name="sale_end" 
                                                       value="{{ old('sale_end') }}">
                                                <div class="form-text">
                                                    Để trống nếu không giới hạn thời gian bán
                                                </div>
                                                @error('sale_end')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Trạng thái -->
                                            <div class="mb-3">
                                                <label for="is_active" class="form-label">Trạng thái</label>
                                                <input type="text" name="is_active" value="0" hidden>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                                           type="checkbox" id="is_active" name="is_active" value="1"
                                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_active">
                                                        <span id="statusLabel">Đang hoạt động</span>
                                                    </label>
                                                </div>
                                                <div class="form-text">
                                                    Chỉ những loại vé đang hoạt động mới có thể được bán
                                                </div>
                                                @error('is_active')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preview -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="bi bi-eye"></i> Xem trước
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="ticket-preview border rounded p-3 mb-3">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="text-primary mb-1" id="previewName">Tên loại vé</h6>
                                                        <small class="text-muted" id="previewEvent">Chọn sự kiện</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="h5 text-success mb-1" id="previewPrice">0đ</div>
                                                        <small class="text-muted">Giá vé</small>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <div class="small text-muted">Tổng số</div>
                                                        <div class="fw-bold" id="previewTotal">0</div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="small text-muted">Max/Đơn</div>
                                                        <div class="fw-bold" id="previewMax">0</div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="small text-muted">Trạng thái</div>
                                                        <div class="fw-bold" id="previewStatus">
                                                            <span class="badge bg-success">Hoạt động</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 text-center">
                                                    <small class="text-muted" id="previewSaleEnd">Không giới hạn thời gian</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('ticket-types.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle"></i> Hủy bỏ
                                        </a>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-circle"></i> Tạo loại vé
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format price display
    const priceInput = document.getElementById('price');
    const priceDisplay = document.getElementById('priceDisplay');
    
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
    
    function updatePriceDisplay() {
        const price = priceInput.value;
        if (price && price > 0) {
            priceDisplay.textContent = `${formatPrice(price)} VNĐ`;
        } else {
            priceDisplay.textContent = '';
        }
    }
    
    priceInput.addEventListener('input', updatePriceDisplay);
    updatePriceDisplay(); // Initial call
    
    // Status label toggle
    const statusCheckbox = document.getElementById('is_active');
    const statusLabel = document.getElementById('statusLabel');
    
    function updateStatusLabel() {
        if (statusCheckbox.checked) {
            statusLabel.textContent = 'Đang hoạt động';
        } else {
            statusLabel.textContent = 'Tạm dừng';
        }
    }
    
    statusCheckbox.addEventListener('change', updateStatusLabel);
    
    // Live preview updates
    const nameInput = document.getElementById('name');
    const eventSelect = document.getElementById('event_id');
    const quantityInput = document.getElementById('quantity_total');
    const maxOrderInput = document.getElementById('max_per_order');
    const saleEndInput = document.getElementById('sale_end');
    
    const previewName = document.getElementById('previewName');
    const previewEvent = document.getElementById('previewEvent');
    const previewPrice = document.getElementById('previewPrice');
    const previewTotal = document.getElementById('previewTotal');
    const previewMax = document.getElementById('previewMax');
    const previewStatus = document.getElementById('previewStatus');
    const previewSaleEnd = document.getElementById('previewSaleEnd');
    
    function updatePreview() {
        // Name
        previewName.textContent = nameInput.value || 'Tên loại vé';
        
        // Event
        const selectedOption = eventSelect.options[eventSelect.selectedIndex];
        previewEvent.textContent = selectedOption.value ? selectedOption.text : 'Chọn sự kiện';
        
        // Price
        const price = priceInput.value;
        previewPrice.textContent = price ? `${formatPrice(price)}đ` : '0đ';
        
        // Quantity
        previewTotal.textContent = quantityInput.value || '0';
        
        // Max per order
        previewMax.textContent = maxOrderInput.value || '0';
        
        // Status
        if (statusCheckbox.checked) {
            previewStatus.innerHTML = '<span class="badge bg-success">Hoạt động</span>';
        } else {
            previewStatus.innerHTML = '<span class="badge bg-secondary">Tạm dừng</span>';
        }
        
        // Sale end
        if (saleEndInput.value) {
            const saleDate = new Date(saleEndInput.value);
            previewSaleEnd.textContent = `Ngưng bán: ${saleDate.toLocaleString('vi-VN')}`;
        } else {
            previewSaleEnd.textContent = 'Không giới hạn thời gian';
        }
    }
    
    // Add event listeners for preview updates
    [nameInput, eventSelect, priceInput, quantityInput, maxOrderInput, statusCheckbox, saleEndInput].forEach(element => {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });
    
    // Initial preview update
    updatePreview();
    
    // Form validation
    document.getElementById('ticketTypeForm').addEventListener('submit', function(e) {
        const saleEnd = saleEndInput.value;
        if (saleEnd) {
            const saleDate = new Date(saleEnd);
            const now = new Date();
            
            if (saleDate <= now) {
                e.preventDefault();
                alert('Thời gian ngưng bán phải sau thời điểm hiện tại!');
                return false;
            }
        }
        
        const quantity = parseInt(quantityInput.value);
        const maxPerOrder = parseInt(maxOrderInput.value);
        
        if (maxPerOrder > quantity) {
            e.preventDefault();
            alert('Số lượng tối đa mỗi đơn không thể lớn hơn tổng số lượng vé!');
            return false;
        }
    });
});
</script>

<style>
.ticket-preview {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid #0d6efd !important;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-label {
    font-weight: 500;
}

.text-danger {
    color: #dc3545 !important;
}

.btn-success {
    background-color: #198754;
    border-color: #198754;
}

.btn-success:hover {
    background-color: #157347;
    border-color: #146c43;
}
</style>