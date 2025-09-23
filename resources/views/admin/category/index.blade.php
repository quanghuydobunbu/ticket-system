@extends('layouts/admin')

@section('content')
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Danh mục sự kiện</h5>
              <a href="{{ route('categories.create') }}" class="btn btn-success">Thêm danh mục</a>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tên danh mục</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <th scope="row">{{ $category->id }}</th>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                {{-- {{ $category->is_active == 1 ? 'Hoạt động' : 'Không hoạt động'}} --}}
                                @if($category->is_active)
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
                                                    <a href="{{ route('categories.show', $category->id) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Xem chi tiết">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('categories.edit', $category->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Chỉnh sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-btn" 
                                                            title="Xóa"
                                                            data-user-id="{{ $category->id }}"
                                                            data-user-name="{{ $category->name }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div> 
                                                
                                                <form id="delete-form-{{ $category->id }}" 
                                                      action="{{ route('categories.destroy', $category->id) }}" 
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
              <!-- End Table with stripped rows -->
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
                text: `Bạn có chắc chắn muốn xóa danh mục "${userName}"?`,
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