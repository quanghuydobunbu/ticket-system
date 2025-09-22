@extends('layouts/admin')

@section('content')
<div class="pagetitle">
    <h1>Sửa người dùng</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Quản lý người dùng</a></li>
            <li class="breadcrumb-item active">Sửa người dùng</li>
        </ol>
    </nav>
</div>

<!-- Hiển thị thông báo -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <!-- Form sửa người dùng -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-person-gear me-2"></i>Chỉnh sửa thông tin người dùng
                </h5>
                
                <form id="editUserForm" class="row g-3" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="col-12">
                        <div class="text-center mb-3">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' id="imageUpload" name="avatar" accept=".png, .jpg, .jpeg" />
                                    <label for="imageUpload" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-camera"></i>
                                    </label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url('{{ $user->avatar_url ? asset('storage/avatars/'.$user->avatar_url) : 'https://via.placeholder.com/150x150/6c757d/ffffff?text='.substr($user->name, 0, 1) }}');">
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">Chọn ảnh đại diện mới (tùy chọn)</small>
                            @error('avatar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                   id="fullName" name="full_name" placeholder="Nguyễn Văn A" 
                                   value="{{ old('full_name', $user->name) }}">
                            <label for="fullName">
                                <i class="bi bi-person me-1"></i>Họ và tên
                            </label>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" placeholder="example@email.com" 
                                   value="{{ old('email', $user->email) }}">
                            <label for="email">
                                <i class="bi bi-envelope me-1"></i>Email
                            </label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" placeholder="0123456789" 
                                   value="{{ old('phone', $user->phone) }}">
                            <label for="phone">
                                <i class="bi bi-phone me-1"></i>Số điện thoại
                            </label>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Thay đổi mật khẩu:</strong> Để trống nếu không muốn thay đổi mật khẩu hiện tại.
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating position-relative">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="••••••••">
                            <label for="password">
                                <i class="bi bi-lock me-1"></i>Mật khẩu mới (tùy chọn)
                            </label>
                            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" id="togglePassword">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="confirmPassword" 
                                   name="password_confirmation" placeholder="••••••••">
                            <label for="confirmPassword">
                                <i class="bi bi-lock-fill me-1"></i>Xác nhận mật khẩu mới
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                                <option value="">Chọn vai trò</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ (string)old('role', $user->role->role_id ?? '') === (string)$role->id ? 'selected' : '' }}>
                                        {{ $role->description }}
                                    </option>
                                @endforeach

                                
                            </select>
                            <label for="role">
                                <i class="bi bi-shield-check me-1"></i>Vai trò
                            </label>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="1" {{ old('status', $user->is_active) == 1 ? 'selected' : '' }}>
                                    Kích hoạt
                                </option>
                                <option value="0" {{ old('status', $user->is_active) == 0 ? 'selected' : '' }}>
                                    Không kích hoạt
                                </option>
                            </select>
                            <label for="status">
                                <i class="bi bi-toggle-on me-1"></i>Trạng thái tài khoản
                            </label>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>   
                    
                    <div class="col-12">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Quay lại
                            </a>
                            
                            <button type="button" class="btn btn-outline-warning" id="resetForm">
                                <i class="bi bi-arrow-clockwise me-1"></i>Đặt lại
                            </button>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Cập nhật
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Thông tin bổ sung -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-info-circle me-2"></i>Thông tin tài khoản
                </h5>
                
                <div class="user-info">
                    <div class="info-item">
                        <strong>ID:</strong> 
                        <span class="badge bg-secondary">#{{ $user->id }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Ngày tạo:</strong>
                        <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Cập nhật cuối:</strong>
                        <span>{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Trạng thái xác thực:</strong>
                        @if($user->is_verified == 1)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Đã xác thực
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="bi bi-clock"></i> Chưa xác thực
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="alert alert-warning mt-3">
                    <h6 class="alert-heading">Lưu ý khi chỉnh sửa:</h6>
                    <ul class="mb-0">
                        <li>Email phải là duy nhất trong hệ thống</li>
                        <li>Để trống mật khẩu nếu không muốn thay đổi</li>
                        <li>Ảnh đại diện tối đa 2MB</li>
                        <li>Thay đổi vai trò sẽ ảnh hưởng đến quyền truy cập</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-clock-history me-2"></i>Hoạt động gần đây
                </h5>
                <div class="activity-feed">
                    <div class="activity-item">
                        <div class="activity-icon bg-primary">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-text">Đăng nhập lần cuối</p>
                            <small class="text-muted">
                                {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Chưa có thông tin' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Avatar Upload Styles */
.avatar-upload {
    position: relative;
    max-width: 150px;
    margin: auto;
}

.avatar-upload .avatar-edit {
    position: absolute;
    right: 5px;
    bottom: 5px;
    z-index: 1;
}

.avatar-upload .avatar-edit input {
    display: none;
}

.avatar-upload .avatar-edit label {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    text-align: center;
    line-height: 22px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.avatar-upload .avatar-preview {
    width: 150px;
    height: 150px;
    position: relative;
    border-radius: 50%;
    border: 3px solid #dee2e6;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.avatar-upload .avatar-preview:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.avatar-upload .avatar-preview > div {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

/* User Info Styles */
.user-info .info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f1f1f1;
}

.user-info .info-item:last-child {
    border-bottom: none;
}

/* Activity Feed Styles */
.activity-feed {
    max-height: 200px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 15px;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-text {
    margin: 0;
    font-size: 14px;
}

/* Form Enhancements */
.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label,
.form-floating > .form-select:focus ~ label,
.form-floating > .form-select:not([value=""]) ~ label {
    opacity: 0.65;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

/* Button Styles */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Card Animations */
.card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

/* Alert Styles */
.alert-dismissible .btn-close {
    padding: 0.75rem 1rem;
}

/* Loading Animation */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .avatar-upload .avatar-preview {
        width: 120px;
        height: 120px;
    }
    
    .d-flex.gap-2.justify-content-end {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 10px;
    }
    
    .user-info .info-item {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store original values for reset functionality
    const originalValues = {
        full_name: document.getElementById('fullName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        role: document.getElementById('role').value,
        status: document.getElementById('status').value,
        avatar: document.getElementById('imagePreview').style.backgroundImage
    };
    
    // Avatar preview
    const imageUpload = document.getElementById('imageUpload');
    const imagePreview = document.getElementById('imagePreview');
    
    imageUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ảnh không được vượt quá 2MB');
                this.value = '';
                return;
            }
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Chỉ chấp nhận file ảnh định dạng JPG, JPEG, PNG, GIF');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.style.backgroundImage = `url('${e.target.result}')`;
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });
    
    // Form validation
    const form = document.getElementById('editUserForm');
    const confirmPassword = document.getElementById('confirmPassword');
    
    // Password confirmation validation
    function validatePasswordMatch() {
        if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Mật khẩu không khớp');
            confirmPassword.classList.add('is-invalid');
            confirmPassword.classList.remove('is-valid');
        } else if (confirmPassword.value) {
            confirmPassword.setCustomValidity('');
            confirmPassword.classList.remove('is-invalid');
            confirmPassword.classList.add('is-valid');
        } else {
            confirmPassword.setCustomValidity('');
            confirmPassword.classList.remove('is-invalid', 'is-valid');
        }
    }
    
    password.addEventListener('input', validatePasswordMatch);
    confirmPassword.addEventListener('input', validatePasswordMatch);
    
    // Reset form
    const resetBtn = document.getElementById('resetForm');
    resetBtn.addEventListener('click', function() {
        if (confirm('Bạn có chắc chắn muốn đặt lại tất cả các thay đổi?')) {
            // Reset form fields to original values
            document.getElementById('fullName').value = originalValues.full_name;
            document.getElementById('email').value = originalValues.email;
            document.getElementById('phone').value = originalValues.phone;
            document.getElementById('role').value = originalValues.role;
            document.getElementById('status').value = originalValues.status;
            document.getElementById('password').value = '';
            document.getElementById('confirmPassword').value = '';
            document.getElementById('imageUpload').value = '';
            imagePreview.style.backgroundImage = originalValues.avatar;
            
            // Remove validation classes
            const inputs = form.querySelectorAll('.form-control, .form-select');
            inputs.forEach(input => {
                input.classList.remove('is-invalid', 'is-valid');
            });
        }
    });
    
    // Form submit with loading
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Validate required fields
        const requiredFields = ['fullName', 'email', 'phone'];
        let isValid = true;
        
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Validate password confirmation if password is entered
        if (password.value) {
            validatePasswordMatch();
            if (confirmPassword.classList.contains('is-invalid')) {
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            return;
        }
        
        submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Đang cập nhật...';
        submitBtn.disabled = true;
        
        // Re-enable button after 5 seconds as fallback
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
    
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        }, 5000);
    });
});
</script>

@endsection