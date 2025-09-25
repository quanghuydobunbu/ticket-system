@extends('layouts/admin')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Chỉnh sửa địa điểm: {{ $venue->name }}</h5>
        
        <form action="{{ route('venues.update', $venue->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <!-- Thông tin cơ bản -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Thông tin cơ bản</h6>
                        </div>
                        <div class="card-body mt-3">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên địa điểm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $venue->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address', $venue->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label">Thành phố <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $venue->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="capacity" class="form-label">Sức chứa</label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                       id="capacity" name="capacity" value="{{ old('capacity', $venue->capacity) }}" 
                                       min="1" placeholder="Số lượng người tối đa">
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Để trống nếu không giới hạn sức chứa</div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin liên hệ -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Thông tin liên hệ</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', $venue->phone) }}" 
                                               placeholder="0123 456 789">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $venue->email) }}" 
                                               placeholder="contact@venue.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin bổ sung -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Thông tin thống kê</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Ngày tạo</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $venue->created_at->format('d/m/Y H:i') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Cập nhật lần cuối</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $venue->updated_at->format('d/m/Y H:i') }}" readonly>
                                    </div>
                                </div>
                            </div>
                            @if(isset($venue->events_count))
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Số sự kiện đã tổ chức</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $venue->events_count }} sự kiện" readonly>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Cài đặt</h6>
                        </div>
                        <div class="card-body mt-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $venue->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Kích hoạt địa điểm
                                </label>
                                <div class="form-text">Địa điểm sẽ hiển thị trong danh sách chọn khi tạo sự kiện</div>
                            </div>
                            
                            @if($venue->is_active)
                                <div class="alert alert-success alert-sm">
                                    <i class="bi bi-check-circle"></i> Địa điểm đang hoạt động
                                </div>
                            @else
                                <div class="alert alert-warning alert-sm">
                                    <i class="bi bi-exclamation-triangle"></i> Địa điểm đang tạm dừng
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Thao tác</h6>
                        </div>
                        <div class="card-body mt-3">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Cập nhật địa điểm
                                </button>
                                <a href="{{ route('venues.show', $venue->id) }}" class="btn btn-info">
                                    <i class="bi bi-eye"></i> Xem chi tiết
                                </a>
                                <a href="{{ route('venues.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Quay lại danh sách
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>

        <!-- Form xóa ẩn -->
        <form id="deleteForm" action="{{ route('venues.destroy', $venue->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
// Format phone number on input
document.getElementById('phone').addEventListener('input', function() {
    let phone = this.value.replace(/\D/g, ''); // Remove non-digits
    
    if (phone.length > 0) {
        if (phone.length <= 4) {
            this.value = phone;
        } else if (phone.length <= 7) {
            this.value = phone.substring(0, 4) + ' ' + phone.substring(4);
        } else {
            this.value = phone.substring(0, 4) + ' ' + phone.substring(4, 7) + ' ' + phone.substring(7, 10);
        }
    }
});

// Validate email format on blur
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        this.classList.add('is-invalid');
        // Create or update error message
        let feedback = this.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            this.parentNode.insertBefore(feedback, this.nextSibling);
        }
        feedback.textContent = 'Email không đúng định dạng';
    } else {
        this.classList.remove('is-invalid');
        let feedback = this.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.remove();
        }
    }
});

// Auto format capacity input
document.getElementById('capacity').addEventListener('input', function() {
    if (this.value < 0) {
        this.value = '';
    }
});

// Validate form before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const address = document.getElementById('address').value.trim();
    const city = document.getElementById('city').value.trim();
    
    if (!name || !address || !city) {
        e.preventDefault();
        alert('Vui lòng điền đầy đủ thông tin bắt buộc (Tên địa điểm, Địa chỉ, Thành phố)');
        return false;
    }
    
    const phone = document.getElementById('phone').value.trim();
    const phoneRegex = /^[0-9\s]{10,15}$/;
    if (phone && !phoneRegex.test(phone.replace(/\s/g, ''))) {
        e.preventDefault();
        alert('Số điện thoại không đúng định dạng');
        return false;
    }
    
    const email = document.getElementById('email').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email && !emailRegex.test(email)) {
        e.preventDefault();
        alert('Email không đúng định dạng');
        return false;
    }
});

// Confirm delete function
function confirmDelete() {
    if (confirm('Bạn có chắc chắn muốn xóa địa điểm này?\n\nLưu ý: Thao tác này không thể hoàn tác và có thể ảnh hưởng đến các sự kiện đã liên kết.')) {
        if (confirm('Xác nhận lần cuối: Xóa địa điểm "{{ $venue->name }}"?')) {
            document.getElementById('deleteForm').submit();
        }
    }
}

// Auto-save draft functionality (optional)
let autoSaveTimeout;
const formInputs = document.querySelectorAll('input:not([readonly]), textarea, select');

formInputs.forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Có thể implement auto-save draft ở đây
            console.log('Auto-saving draft...');
        }, 2000);
    });
});

// Show unsaved changes warning
let hasUnsavedChanges = false;
formInputs.forEach(input => {
    input.addEventListener('input', () => {
        hasUnsavedChanges = true;
    });
});

document.querySelector('form').addEventListener('submit', () => {
    hasUnsavedChanges = false;
});

window.addEventListener('beforeunload', (e) => {
    if (hasUnsavedChanges) {
        e.preventDefault();
        e.returnValue = '';
        return 'Bạn có thay đổi chưa được lưu. Bạn có muốn rời khỏi trang?';
    }
});
</script>

<style>
.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.card-header h6 {
    font-weight: 600;
}

.btn-outline-danger:hover {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}

.invalid-feedback {
    font-size: 0.875rem;
}
</style>
@endsection