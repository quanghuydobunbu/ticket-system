@extends('layouts/admin')

@section('content')
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Danh sách địa điểm</h5>
              <div class="row">

              <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('venues.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="search" placeholder="Tìm kiếm theo tên địa điểm..." 
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
                                    <a href="{{ route('venues.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise"></i> Đặt lại
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Preserve other parameters -->
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                    </form>

                    @if(request()->hasAny(['search', 'status']))
                        <div class="mb-3">
                            {{-- <small class="text-muted">Bộ lọc đang áp dụng:</small> --}}
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @if(request('search'))
                                    <span class="badge bg-info">
                                        Tìm kiếm: "{{ request('search') }}"
                                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-white ms-1">×</a>
                                    </span>
                                @endif
                                {{-- @if(request('status'))
                                    <span class="badge bg-success">
                                        Trạng thái: {{ request('status') == '1' ? 'Hoạt động' : 'Không hoạt động' }}
                                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="text-white ms-1">×</a>
                                    </span>
                                @endif --}}
                            </div>
                        </div>
                    @endif
              </div>
              
              <a href="{{ route('venues.create') }}" class="col-md-3 btn btn-success">Thêm địa điểm mới</a>

              <table class="table table-striped">
                <thead>
                  <tr>  
                    <th scope="col">#</th>
                    <th scope="col">Tên địa điểm</th>
                    <th scope="col">Địa chỉ</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($venues as $venue)
                        <tr>
                            <th scope="row">{{ $venue->id }}</th>
                            <td>{{ $venue->name }}</td>
                            <td>{{ $venue->address }}</td>
                            <td>
                                @if($venue->is_active)
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
                                <div class="btn-group" role="group">
                                    <a href="{{ route('venues.show', $venue->id) }}" 
                                        class="btn btn-sm btn-outline-info" 
                                        title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('venues.edit', $venue->id) }}" 
                                        class="btn btn-sm btn-outline-primary" 
                                        title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                                    
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger delete-btn" 
                                            title="Xóa"
                                            data-user-id="{{ $venue->id }}"
                                            data-user-name="{{ $venue->name }}">
                                        <i class="bi bi-trash"></i>
                                     </button>
                                </div> 
                                                
                                <form id="delete-form-{{ $venue->id }}" 
                                         action="{{ route('venues.destroy', $venue->id) }}" 
                                       method="POST" style="display: none;">
                                      @csrf
                                      @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
              <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                Hiển thị {{ $venues->firstItem() }}-{{ $venues->lastItem() }} trong tổng số {{ $venues->total() }} kết quả
                            </div>
                            {{ $venues->links() }}
                        </div>
            </div>
          </div>
@endsection


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
                text: `Bạn có chắc chắn muốn xóa địa điểm "${userName}"?`,
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