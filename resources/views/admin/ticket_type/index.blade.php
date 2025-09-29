    @extends('layouts/admin')

    @section('content')
        <div class="card">
                <div class="card-body">
                <h5 class="card-title">Danh sách loại vé</h5>
                <div class="row">

                <!-- Search and Filter Form -->
                        <form method="GET" action="{{ route('ticket-types.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="search" placeholder="Tìm kiếm theo tên loại vé..." 
                                            class="form-control" value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                                {{-- <div class="col-md-3">
                                    <select class="form-select" name="event_id">
                                        <option value="">Tất cả sự kiện</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                                {{ $event->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-md-3">
                                    <select class="form-select" name="status" onchange="this.form.submit()">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex gap-2">
                                        {{-- <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-funnel"></i> Lọc
                                        </button> --}}
                                        <a href="{{ route('ticket-types.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-clockwise"></i> Đặt lại
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Preserve other parameters -->
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        </form>

                        @if(request()->hasAny(['search', 'event_id', 'is_active']))
                            <div class="mb-3">
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @if(request('search'))
                                        <span class="badge bg-info">
                                            Tìm kiếm: "{{ request('search') }}"
                                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-white ms-1">×</a>
                                        </span>
                                    @endif
                                    @if(request('event_id'))
                                        <span class="badge bg-success">
                                            Sự kiện: {{ $events->where('id', request('event_id'))->first()?->title }}
                                            <a href="{{ request()->fullUrlWithQuery(['event_id' => null]) }}" class="text-white ms-1">×</a>
                                        </span>
                                    @endif
                                    @if(request('is_active') !== null)
                                        <span class="badge bg-warning">
                                            Trạng thái: {{ request('is_active') == '1' ? 'Hoạt động' : 'Không hoạt động' }}
                                            <a href="{{ request()->fullUrlWithQuery(['is_active' => null]) }}" class="text-white ms-1">×</a>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                </div>
                
                <a href="{{ route('ticket-types.create') }}" class="col-md-3 btn btn-success mb-3">
                    <i class="bi bi-plus-circle"></i> Tạo loại vé mới
                </a>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="">
                        <tr>
                            <th scope="col">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                class="text-decoration-none text-white">
                                    Tên loại vé
                                    @if(request('sort') === 'name')
                                        <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">Sự kiện</th>
                            <th scope="col">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => request('sort') === 'price' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                class="text-decoration-none text-white">
                                    Giá bán
                                    @if(request('sort') === 'price')
                                        <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Còn lại</th>
                            <th scope="col">Giới hạn/Đơn</th>
                            <th scope="col">Ngưng bán</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($ticketTypes as $ticketType)
                                @php
                                    $remaining = $ticketType->quantity_total - $ticketType->quantity_sold;
                                    $soldPercentage = $ticketType->quantity_total > 0 ? ($ticketType->quantity_sold / $ticketType->quantity_total) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div>
                                            <strong class="text-primary">{{ $ticketType->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            {{ $ticketType->event->title ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success fs-6">
                                            {{ number_format($ticketType->price, 0, ',', '.') }}<small class="text-muted">đ</small>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary fs-6">{{ number_format($ticketType->quantity_total) }}</span>
                                    </td>
                                    {{-- <td class="text-center">
                                        <div>
                                            <span class="badge bg-info fs-6">{{ number_format($ticketType->quantity_sold) }}</span>
                                            @if($ticketType->quantity_total > 0)
                                                <div class="progress mt-1" style="height: 4px;">
                                                    <div class="progress-bar bg-info" 
                                                        style="width: {{ $soldPercentage }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ number_format($soldPercentage, 1) }}%</small>
                                            @endif
                                        </div>
                                    </td> --}}
                                    <td class="text-center">
                                        <span class="badge {{ $remaining <= 10 ? 'bg-danger' : ($remaining <= 50 ? 'bg-warning' : 'bg-success') }} fs-6">
                                            {{ number_format($remaining) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $ticketType->max_per_order }}</span>
                                    </td>
                                    <td>
                                        @if($ticketType->sale_end)
                                            <div class="small">
                                                {{ \Carbon\Carbon::parse($ticketType->sale_end)->format('d/m/Y') }}
                                                <br>
                                                <span class="text-muted">{{ \Carbon\Carbon::parse($ticketType->sale_end)->format('H:i') }}</span>
                                            </div>
                                            @if(\Carbon\Carbon::parse($ticketType->sale_end)->isPast())
                                                <small class="badge bg-danger">Hết hạn</small>
                                            @elseif(\Carbon\Carbon::parse($ticketType->sale_end)->diffInDays() <= 3)
                                                <small class="badge bg-warning">Sắp hết hạn</small>
                                            @endif
                                        @else
                                            <small class="text-muted">Không giới hạn</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($ticketType->is_active)
                                            @if($ticketType->sale_end && \Carbon\Carbon::parse($ticketType->sale_end)->isPast())
                                                <span class="badge bg-warning">Hết hạn</span>
                                            @elseif($remaining <= 0)
                                                <span class="badge bg-danger">Hết vé</span>
                                            @else
                                                <span class="badge bg-success">Đang bán</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Tạm dừng</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('ticket-types.show', $ticketType->id) }}" 
                                            class="btn btn-sm btn-outline-info" 
                                            title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('ticket-types.edit', $ticketType->id) }}" 
                                            class="btn btn-sm btn-outline-primary" 
                                            title="Chỉnh sửa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger delete-btn" 
                                                    title="Xóa"
                                                    data-ticket-type-id="{{ $ticketType->id }}"
                                                    data-ticket-type-name="{{ $ticketType->name }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div> 
                                        
                                        <form id="delete-form-{{ $ticketType->id }}" 
                                            action="{{ route('ticket-types.destroy', $ticketType->id) }}" 
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4">
                                        <i class="bi bi-ticket-detailed fs-1 opacity-25"></i>
                                        <p class="mt-2">Chưa có loại vé nào</p>
                                        <a href="{{ route('ticket-types.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Tạo loại vé đầu tiên
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($ticketTypes->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Hiển thị {{ $ticketTypes->firstItem() }}-{{ $ticketTypes->lastItem() }} trong tổng số {{ $ticketTypes->total() }} loại vé
                        </div>
                        {{ $ticketTypes->links() }}
                    </div>
                @endif
                </div>
            </div>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const ticketTypeId = this.getAttribute('data-ticket-type-id');
                const ticketTypeName = this.getAttribute('data-ticket-type-name');
                
                if (this.disabled) {
                    Swal.fire({
                        title: 'Không thể xóa',
                        text: 'Không thể xóa loại vé đã có người mua',
                        icon: 'warning',
                        confirmButtonText: 'Đã hiểu'
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Xác nhận xóa loại vé?',
                    text: `Bạn có chắc chắn muốn xóa loại vé "${ticketTypeName}"? Hành động này không thể hoàn tác.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa loại vé',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Đang xóa loại vé...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        document.getElementById('delete-form-' + ticketTypeId).submit();
                    }
                });
            });
        });
    });
    </script>