@extends('layouts/admin')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Tạo địa điểm mới</h5>
        
        <form action="{{ route('venues.store') }}" method="POST">
            @csrf
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
                                       id="name" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label">Thành phố <span class="text-danger">*</span></label>
                                
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="capacity" class="form-label">Sức chứa</label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                       id="capacity" name="capacity" value="{{ old('capacity') }}" 
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
                                               id="phone" name="phone" value="{{ old('phone') }}" 
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
                                               id="email" name="email" value="{{ old('email') }}" 
                                               placeholder="contact@venue.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
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
                                       {{ old('is_active', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Kích hoạt địa điểm
                                </label>
                                <div class="form-text">Địa điểm sẽ hiển thị trong danh sách chọn khi tạo sự kiện</div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body mt-3">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-plus-circle"></i> Thêm địa điểm
                                </button>
                                <a href="{{ route('venues.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Quay lại
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
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
</script>
@endsection