@extends('layouts/admin')

@section('content')
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Danh sách sự kiện</h5>
              <div class="row">
                    <form method="GET" action="{{ route('bookings.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="search" placeholder="Tìm kiếm theo mã đơn hàng..." 
                                           class="form-control" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="status" onchange="this.form.submit()">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã thanh toán</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    <option value="refunde" {{ request('status') === 'refunde' ? 'selected' : '' }}>Hoàn tiền</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
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
              {{-- <a href="{{ route('bookings.create') }}" class="col-md-3 btn btn-success">Tạo đơn hàng</a> --}}
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Mã đơn</th>
                    <th scope="col">Người đặt hàng</th>
                    <th scope="col">Sự kiện</th>
                    <th scope="col">Tổng tiền</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr>
                            <th>{{ $booking->booking_code ?? '' }}</th>
                            <th>{{ $booking->user->name ?? '' }}</th>
                            <td>{{ $booking->event->title ?? '' }}</td>
                            <td>{{ $booking->final_amount }}đ</td>
                            <td>
                                @if($booking->status == 'confirmed')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Đã thanh toán
                                    </span>
                                @elseif($booking->status == 'pending')
                                    <span class="badge bg-warning">
                                        <i class="bi bi-x-circle me-1"></i>Chờ thanh toán
                                    </span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Hủy thanh toán
                                    </span>
                                @else
                                    <span class="badge bg-infor">
                                        <i class="bi bi-x-circle me-1"></i>Hoàn tiền
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                                    <a href="{{ route('bookings.show', $booking->id) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Xem chi tiết">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    {{-- <a href="{{ route('bookings.edit', $booking->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Chỉnh sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a> --}}
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-btn" 
                                                            title="Xóa"
                                                            data-user-id="{{ $booking->id }}"
                                                            data-user-name="{{ $booking->booking_code }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div> 
                                                
                                                <form id="delete-form-{{ $booking->id }}" 
                                                      action="{{ route('bookings.destroy', $booking->id) }}" 
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
                text: `Bạn có chắc chắn muốn xóa đơn hàng "${userName}"?`,
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