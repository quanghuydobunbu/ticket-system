@extends('layouts/admin')

@section('content')
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Danh sách sự kiện</h5>
              <div class="row">

              <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('events.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="search" placeholder="Tìm kiếm theo tên, địa điểm..." 
                                           class="form-control" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="status" onchange="this.form.submit()">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
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
              
              <a href="{{ route('events.create') }}" class="col-md-3 btn btn-success">Tạo sự kiện mới</a>

              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tên sự kiện</th>
                    <th scope="col">Địa điểm diễn ra</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <th scope="row">{{ $event->id }}</th>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->slug }}</td>
                            <td>{{ $event->status->label() }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                                    <a href="{{ route('events.show', $event->id) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Xem chi tiết">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('events.edit', $event->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Chỉnh sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-btn" 
                                                            title="Xóa"
                                                            data-user-id="{{ $event->id }}"
                                                            data-user-name="{{ $event->title }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div> 
                                                
                                                <form id="delete-form-{{ $event->id }}" 
                                                      action="{{ route('events.destroy', $event->id) }}" 
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
                                Hiển thị {{ $events->firstItem() }}-{{ $events->lastItem() }} trong tổng số {{ $events->total() }} kết quả
                            </div>
                            {{ $events->links() }}
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
                text: `Bạn có chắc chắn muốn xóa sự kiện "${userName}"?`,
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