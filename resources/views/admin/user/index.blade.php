@extends('layouts/admin')

@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Quản lý người dùng</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Danh sách người dùng</h5>
                        @can('users.create')
                            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> Thêm người dùng
                            </a>
                        @endcan
                    </div>

                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('users.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="search" placeholder="Tìm kiếm theo tên, email..." 
                                           class="form-control" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="status" onchange="this.form.submit()">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise"></i> Đặt lại
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Preserve other parameters -->
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                    </form>

                    <!-- Active Filters Display -->
                    @if(request()->hasAny(['search', 'status']))
                        <div class="mb-3">
                            <small class="text-muted">Bộ lọc đang áp dụng:</small>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @if(request('search'))
                                    <span class="badge bg-info">
                                        Tìm kiếm: "{{ request('search') }}"
                                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-white ms-1">×</a>
                                    </span>
                                @endif
                                @if(request('status'))
                                    <span class="badge bg-success">
                                        Trạng thái: {{ request('status') == '1' ? 'Hoạt động' : 'Không hoạt động' }}
                                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="text-white ms-1">×</a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Tổng người dùng</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $users->total() }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Kết quả hiện tại</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person-check"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $users->count() }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($users && $users->count() > 0)
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" width="20%">
                                            <a href="{{ request()->fullUrlWithQuery([
                                                'sort' => 'name',
                                                'direction' => (request('sort') == 'name' && request('direction') == 'asc') ? 'desc' : 'asc'
                                            ]) }}" class="text-decoration-none text-dark">
                                                Họ và tên
                                                @if(request('sort') == 'name')
                                                    <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up ms-1 text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col" width="25%">
                                            <a href="{{ request()->fullUrlWithQuery([
                                                'sort' => 'email',
                                                'direction' => (request('sort') == 'email' && request('direction') == 'asc') ? 'desc' : 'asc'
                                            ]) }}" class="text-decoration-none text-dark">
                                                Email
                                                @if(request('sort') == 'email')
                                                    <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up ms-1 text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col" width="15%">
                                            <a href="{{ request()->fullUrlWithQuery([
                                                'sort' => 'is_active',
                                                'direction' => (request('sort') == 'is_active' && request('direction') == 'asc') ? 'desc' : 'asc'
                                            ]) }}" class="text-decoration-none text-dark">
                                                Trạng thái
                                                @if(request('sort') == 'is_active')
                                                    <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up ms-1 text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col" width="25%">Vai trò</th>
                                        <th scope="col" width="10%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        @if($item->avatar_url)
                                                            <img src="{{ asset('storage/avatars/'.$item->avatar_url) }}" alt="avatar" class="rounded-circle" width="32" height="32">
                                                        @else
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                {{ strtoupper(substr($item->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span class="fw-medium">{{ $item->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $item->email }}" class="text-decoration-none">
                                                    {{ $item->email }}
                                                </a>
                                            </td>
                                              
                                            <td>
                                                @if($item->is_active)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Hoạt động
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle me-1"></i>Không hoạt động
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    @if ($item->role && $item->role->role)
                                                        <span class="badge bg-secondary">{{ $item->role->role->description }}</span>
                                                    @else
                                                        <span class="text-muted">Chưa có vai trò</span>
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('users.show', $item->id) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Xem chi tiết">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('users.edit', $item->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Chỉnh sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-btn" 
                                                            title="Xóa"
                                                            data-user-id="{{ $item->id }}"
                                                            data-user-name="{{ $item->name }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>

                                                <!-- Hidden delete form -->
                                                <form id="delete-form-{{ $item->id }}" 
                                                      action="{{ route('users.destroy', $item->id) }}" 
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination with filters preserved -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                Hiển thị {{ $users->firstItem() }}-{{ $users->lastItem() }} trong tổng số {{ $users->total() }} kết quả
                            </div>
                            {{ $users->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <i class="bi bi-people" style="font-size: 4rem; color: #ccc;"></i>
                            @if(request()->hasAny(['search', 'status']))
                                <h4 class="mt-3 text-muted">Không tìm thấy kết quả</h4>
                                <p class="text-muted">Thử thay đổi bộ lọc để tìm thấy người dùng</p>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Xóa bộ lọc
                                </a>
                            @else
                                <h4 class="mt-3 text-muted">Không có người dùng nào</h4>
                                <p class="text-muted">Bắt đầu bằng cách thêm người dùng đầu tiên</p>
                                <a href="{{ route('users.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Thêm người dùng
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@endsection

<style>
    .info-card {
        background: linear-gradient(90deg, #4154f1 0%, #677dff 100%);
        color: white;
    }
    .info-card .card-title {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
    }
    .info-card .card-icon {
        background: rgba(255, 255, 255, 0.1);
        width: 48px;
        height: 48px;
    }
    .revenue-card {
        background: linear-gradient(90deg, #2eca6a 0%, #4fcf82 100%);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(65, 84, 241, 0.05);
    }
    .avatar-sm img, .avatar-sm div {
        object-fit: cover;
    }
    .badge a {
        text-decoration: none !important;
    }
    .badge a:hover {
        opacity: 0.8;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            
            Swal.fire({
                title: 'Xác nhận xóa?',
                text: `Bạn có chắc chắn muốn xóa người dùng "${userName}"?`,
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
    });
});
</script>