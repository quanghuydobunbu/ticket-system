@extends('layouts/admin')

@section('content')
<div class="pagetitle">
    <h1>Chi tiết người dùng</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Quản lý người dùng</a></li>
            <li class="breadcrumb-item active">Chi tiết</li>
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

<div class="row">
    <!-- Thông tin chính -->
    <div class="col-lg-4">
        <!-- Card thông tin cá nhân -->
        <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                <div class="avatar-container mb-3">
                    {{-- <img src="{{ $user->avatar_url ? asset('storage/avatars/'.$user->avatar_url) : 'https://via.placeholder.com/150x150/6c757d/ffffff?text='.substr($user->name, 0, 1) }}"> --}}
                    <img src="{{ $user->avatar_url ? asset('storage/avatars/'.$user->avatar_url) : 'https://via.placeholder.com/150x150/6c757d/ffffff?text='.substr($user->name, 0, 1) }}" 
                         alt="Avatar" class="rounded-circle profile-avatar">
                    <div class="status-indicator {{ $user->is_active ? 'online' : 'offline' }}"></div>
                </div>
                
                <h2 class="profile-name">{{ $user->name }}</h2>
                <div class="profile-role mb-3">
                    @php
                        $roleColors = [
                            'admin' => 'bg-danger',
                            'manager' => 'bg-warning',
                            'user' => 'bg-success'
                        ];
                        $roleNames = [
                            'admin' => 'Quản trị viên',
                            'manager' => 'Quản lý',
                            'user' => 'Người dùng'
                        ];
                    @endphp

                                           

                    {{-- <span class="badge {{ $roleColors[$user->role ?? 'user'] ?? 'bg-secondary' }}">
                        {{ $roleNames[$user->role ?? 'user'] ?? 'Chưa xác định' }}
                    </span> --}}

                    @if ($user->role)
                        {{ $user->role->role ? $user->role->role->name : '' }}
                    @endif
                </div>
                <div class="profile-actions d-flex gap-2 w-100">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm flex-fill">
                        <i class="bi bi-pencil"></i> Chỉnh sửa
                    </a>
                    <button class="btn btn-outline-secondary btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#contactModal">
                        <i class="bi bi-chat"></i> Liên hệ
                    </button>
                </div>
            </div>
        </div>

        <!-- Card trạng thái -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-shield-check me-2"></i>Trạng thái tài khoản
                </h5>
                
                <div class="status-list">
                    <div class="status-item">
                        <div class="status-label">
                            <i class="bi bi-power"></i>
                            Kích hoạt
                        </div>
                        <div class="status-value">
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->is_active ? 'Đã kích hoạt' : 'Chưa kích hoạt' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="status-item">
                        <div class="status-label">
                            <i class="bi bi-check-circle"></i>
                            Xác thực email
                        </div>
                        <div class="status-value">
                            <span class="badge {{ $user->is_verified ? 'bg-success' : 'bg-warning' }}">
                                {{ $user->is_verified ? 'Đã xác thực' : 'Chưa xác thực' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="status-item">
                        <div class="status-label">
                            <i class="bi bi-clock"></i>
                            Đăng nhập cuối
                        </div>
                        <div class="status-value">
                            <small class="text-muted">
                                {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Chưa đăng nhập' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chi tiết thông tin -->
    <div class="col-lg-8">
        <!-- Tabs -->
        <div class="card">
            <div class="card-body">
                <div class="tab-content pt-3">
                    <!-- Tab Chi tiết -->
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">ID người dùng:</td>
                                        <td><span class="badge bg-secondary">#{{ $user->id }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Họ và tên:</td>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Email:</td>
                                        <td>
                                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                                {{ $user->email }}
                                                @if($user->is_verified)
                                                    <i class="bi bi-check-circle-fill text-success ms-1" title="Email đã xác thực"></i>
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Số điện thoại:</td>
                                        <td><a href="tel:{{ $user->phone }}" class="text-decoration-none">{{ $user->phone }}</a></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Vai trò:</td>
                                        <td>
                                            @if ($user->role)
                                                {{ $user->role->role ? $user->role->role->description : '' }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Trạng thái:</td>
                                        <td>
                                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $user->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Ngày tạo:</td>
                                        <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Lần cập nhật cuối:</td>
                                        <td>{{ $user->updated_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Đăng nhập cuối:</td>
                                        <td>{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i:s') : 'Chưa có thông tin' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal liên hệ -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Liên hệ với {{ $user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="contact-options">
                    <a href="mailto:{{ $user->email }}" class="contact-option">
                        <i class="bi bi-envelope"></i>
                        <span>Gửi email</span>
                    </a>
                    <a href="tel:{{ $user->phone }}" class="contact-option">
                        <i class="bi bi-phone"></i>
                        <span>Gọi điện</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="d-flex gap-2 justify-content-end">
        <button type="button" class="btn btn-outline-secondary" id="">
            <a href="{{ route('users.index') }}">Quay lại</a>
        </button>
    </div>  
</div>

<style>
/* Profile Card Styles */
.profile-card {
    text-align: center;
}

.avatar-container {
    position: relative;
    display: inline-block;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.status-indicator {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid #fff;
}

.status-indicator.online {
    background-color: #28a745;
}

.status-indicator.offline {
    background-color: #dc3545;
}

.profile-name {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0;
    color: #2c3e50;
}

.profile-role {
    margin-bottom: 1rem;
}

.profile-stats {
    padding: 1rem 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.stat-number {
    font-size: 1.25rem;
    font-weight: 600;
    color: #495057;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
}

/* Status List */
.status-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}

.status-item:last-child {
    border-bottom: none;
}

.status-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

/* Info Cards */
.info-card {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 20px;
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.info-content h6 {
    margin-bottom: 10px;
    font-weight: 600;
}

.info-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.detail-item {
    font-size: 0.9rem;
}

/* Timeline Styles */
.activity-timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 25px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -18px;
    top: 30px;
    width: 2px;
    height: calc(100% - 10px);
    background: #dee2e6;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    z-index: 1;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-content p {
    margin-bottom: 5px;
    font-size: 0.9rem;
}

/* Security Cards */
.security-card {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    margin-bottom: 20px;
}

.security-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: #6c757d;
    flex-shrink: 0;
}

.security-content h6 {
    margin-bottom: 8px;
    font-weight: 600;
}

.security-content p {
    margin-bottom: 12px;
    font-size: 0.9rem;
}

/* Contact Options */
.contact-options {
    display: flex;
    gap: 15px;
}

.contact-option {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 20px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    text-decoration: none;
    color: #495057;
    transition: all 0.3s ease;
}

.contact-option:hover {
    background: #f8f9fa;
    color: #0d6efd;
    transform: translateY(-2px);
    text-decoration: none;
}

.contact-option i {
    font-size: 1.5rem;
}

/* Tab Enhancements */
.nav-tabs .nav-link {
    border: none;
    background: none;
    color: #6c757d;
    padding: 12px 20px;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover,
.nav-tabs .nav-link.active {
    color: #0d6efd;
    background: none;
    border-bottom: 2px solid #0d6efd;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-stats {
        flex-direction: column;
        gap: 15px;
    }
    
    .info-card {
        flex-direction: column;
        text-align: center;
    }
    
    .security-card {
        flex-direction: column;
        text-align: center;
    }
    
    .contact-options {
        flex-direction: column;
    }
    
    .activity-timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -20px;
    }
    
    .timeline-item:not(:last-child)::before {
        left: -8px;
    }
}

/* Animation */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        }, 5000);
    });

    // Smooth scroll for tabs
    const tabLinks = document.querySelectorAll('#userDetailTabs button');
    tabLinks.forEach(link => {
        link.addEventListener('shown.bs.tab', function() {
            const target = document.querySelector(this.getAttribute('data-bs-target'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});

// Action Functions
function toggleUserStatus(userId) {
    if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái người dùng này?')) {
        fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thực hiện thao tác');
        });
    }
}
// Toast notification
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    document.body.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// Print user details
function printUserDetails() {
    window.print();
}


</script>
@endsection
