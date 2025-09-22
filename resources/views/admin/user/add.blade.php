@extends('layouts/admin')

@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Form thêm người dùng -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-person-plus me-2"></i>Thêm người dùng mới
                </h5>
                
                <form id="addUserForm" class="row g-3" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
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
                                    <div id="imagePreview" style="background-image: url('https://via.placeholder.com/150x150/6c757d/ffffff?text=Avatar');">
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">Chọn ảnh đại diện</small>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="fullName" name="full_name" placeholder="Nguyễn Văn A">
                            <label for="fullName">
                                <i class="bi bi-person me-1"></i>Họ và tên
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input class="form-control" @error('email') is-invalid @enderror id="email" name="email" placeholder="example@email.com">
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
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="0123456789">
                            <label for="phone">
                                <i class="bi bi-phone me-1"></i>Số điện thoại
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating position-relative">
                            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••">
                            <label for="password">
                                <i class="bi bi-lock me-1"></i>Mật khẩu
                            </label>
                            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" id="togglePassword">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="••••••••">
                            <label for="confirmPassword">
                                <i class="bi bi-lock-fill me-1"></i>Xác nhận mật khẩu
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="role" name="role">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->description }}</option>
                                @endforeach
                            </select>
                            <label for="role">
                                <i class="bi bi-shield-check me-1"></i>Vai trò
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="status" name="status">
                                <option value="1">Kích hoạt</option>
                                <option value="0">Không kích hoạt</option>
                            </select>
                            <label for="status">
                                <i class="bi bi-toggle-on me-1"></i>Trạng thái tài khoản
                            </label>
                        </div>
                    </div>   
                    
                    <div class="col-12">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-outline-secondary" id="">
                                <a href="{{ route('users.index') }}">Quay lại</a>
                            </button>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Thêm người dùng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-info-circle me-2"></i>Hướng dẫn
                </h5>
                <div class="alert alert-info">
                    <h6 class="alert-heading">Lưu ý khi tạo tài khoản:</h6>
                    <ul class="mb-0">
                        <li>Mật khẩu tối thiểu 8 ký tự</li>
                        <li>Email phải là duy nhất</li>
                        <li>Số điện thoại phải đúng định dạng</li>
                        <li>Ảnh đại diện tối đa 2MB</li>
                    </ul>
                </div>
                
                <div class="mt-3">
                    <h6>Phân quyền:</h6>
                    <div class="badge-container">
                        <span class="badge bg-danger">Quản trị viên</span>
                        <small class="d-block text-muted">Toàn quyền hệ thống</small>
                    </div>
                    <div class="badge-container mt-2">
                        <span class="badge bg-warning">Quản lý</span>
                        <small class="d-block text-muted">Quản lý nội dung</small>
                    </div>
                    <div class="badge-container mt-2">
                        <span class="badge bg-success">Người dùng</span>
                        <small class="d-block text-muted">Quyền cơ bản</small>
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

/* Form Enhancements */
.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    opacity: 0.65;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
}

.form-control:focus {
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

/* Table Styles */
.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.075);
}

/* Badge Container */
.badge-container {
    padding: 8px 0;
    border-left: 3px solid #dee2e6;
    padding-left: 10px;
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
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar preview
    const imageUpload = document.getElementById('imageUpload');
    const imagePreview = document.getElementById('imagePreview');
    
    imageUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
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
    const form = document.getElementById('addUserForm');
    const confirmPassword = document.getElementById('confirmPassword');
    
    confirmPassword.addEventListener('input', function() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Mật khẩu không khớp');
            confirmPassword.classList.add('is-invalid');
        } else {
            confirmPassword.setCustomValidity('');
            confirmPassword.classList.remove('is-invalid');
            confirmPassword.classList.add('is-valid');
        }
    });
    
    
    // Form submit with loading
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Đang xử lý...';
        submitBtn.disabled = true;
        
        // Simulate processing (remove this in real implementation)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 2000);
    });
});
</script>

@endsection