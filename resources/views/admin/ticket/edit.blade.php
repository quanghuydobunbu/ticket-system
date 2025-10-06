@extends('layouts/admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Chỉnh sửa vé</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Danh sách vé</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->ticket_code }}</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" id="editTicketForm">
                @csrf
                @method('PUT')

                <!-- Thông tin cơ bản -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-ticket-detailed"></i> Thông tin vé</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mt-1">
                            <!-- Mã vé (readonly) -->
                            <div class="col-md-6">
                                <label class="form-label">Mã vé <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control bg-light" 
                                       value="{{ $ticket->ticket_code }}" 
                                       readonly>
                                <small class="text-muted">Mã vé không thể thay đổi</small>
                            </div>

                            <!-- Trạng thái -->
                            <div class="col-md-6">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="status" 
                                        class="form-select @error('status') is-invalid @enderror" 
                                        required>
                                    <option value="active" {{ old('status', $ticket->status) == 'active' ? 'selected' : '' }}>
                                        Kích hoạt
                                    </option>
                                    <option value="used" {{ old('status', $ticket->status) == 'used' ? 'selected' : '' }}>
                                        Đã sử dụng
                                    </option>
                                    <option value="cancelled" {{ old('status', $ticket->status) == 'cancelled' ? 'selected' : '' }}>
                                        Đã hủy
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Loại vé -->
                            <div class="col-md-6">
                                <label class="form-label">Loại vé <span class="text-danger">*</span></label>
                                <select name="ticket_type_id" 
                                        class="form-select @error('ticket_type_id') is-invalid @enderror" 
                                        required>
                                    <option value="">-- Chọn loại vé --</option>
                                    @foreach($ticketTypes as $type)
                                        <option value="{{ $type->id }}" 
                                                {{ old('ticket_type_id', $ticket->ticket_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }} - {{ number_format($type->price, 0, ',', '.') }}đ
                                        </option>
                                    @endforeach
                                </select>
                                @error('ticket_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Đặt vé (readonly) -->
                            <div class="col-md-6">
                                <label class="form-label">Mã đặt vé</label>
                                <input type="text" 
                                       class="form-control bg-light" 
                                       value="{{ $ticket->booking->booking_code ?? 'N/A' }}" 
                                       readonly>
                                <small class="text-muted">Không thể thay đổi đặt vé gốc</small>
                            </div>

                            <!-- Tên người tham dự -->
                            <div class="col-md-12">
                                <label class="form-label">Tên người tham dự</label>
                                <input type="text" 
                                       name="attendee_name" 
                                       class="form-control @error('attendee_name') is-invalid @enderror" 
                                       value="{{ old('attendee_name', $ticket->attendee_name) }}"
                                       placeholder="Nhập tên người tham dự">
                                @error('attendee_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Để trống nếu sử dụng thông tin từ đặt vé</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin Check-in -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Thông tin Check-in</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Trạng thái check-in -->
                            <div class="col-md-12">
                                @if($ticket->checked_in_at)
                                    <div class="alert alert-info mt-3">
                                        <i class="bi bi-info-circle"></i>
                                        <strong>Đã check-in:</strong> 
                                        {{ \Carbon\Carbon::parse($ticket->checked_in_at)->format('d/m/Y H:i:s') }}
                                    </div>
                                @else
                                    <div class="alert alert-warning mt-3">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <strong>Chưa check-in</strong>
                                    </div>
                                @endif
                            </div>

                            <!-- Đặt lại check-in -->
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="reset_checkin" 
                                           id="resetCheckin"
                                           value="1">
                                    <label class="form-check-label" for="resetCheckin">
                                        Đặt lại trạng thái check-in (xóa thời gian check-in)
                                    </label>
                                </div>
                                <small class="text-muted">
                                    Chọn tùy chọn này để cho phép check-in lại vé
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Column - Preview -->
        <div class="col-lg-4">
            <!-- Thông tin hiện tại -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin hiện tại</h5>
                </div>
                <div class="card-body mt-1">
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted">Mã vé:</td>
                            <td><strong>{{ $ticket->ticket_code }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Trạng thái:</td>
                            <td>
                                @switch($ticket->status)
                                    @case('active')
                                        <span class="badge bg-success">Kích hoạt</span>
                                        @break
                                    @case('used')
                                        <span class="badge bg-primary">Đã sử dụng</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Loại vé:</td>
                            <td>{{ $ticket->ticketType->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Người tham dự:</td>
                            <td>{{ $ticket->attendee_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Check-in:</td>
                            <td>
                                @if($ticket->checked_in_at)
                                    <span class="badge bg-success">Đã check-in</span>
                                @else
                                    <span class="badge bg-warning">Chưa check-in</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày tạo:</td>
                            <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- QR Code -->
            @if($ticket->qr_code)
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-qr-code"></i> Mã QR</h5>
                </div>
                <div class="card-body text-center mt-3">
                    <div class="p-3 bg-white border rounded d-inline-block">
                        {!! QrCode::size(150)->generate($ticket->qr_code) !!}
                    </div>
                    <p class="mt-2 mb-0 small text-muted">{{ $ticket->ticket_code }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Form validation
document.getElementById('editTicketForm').addEventListener('submit', function(e) {
    const status = document.querySelector('select[name="status"]').value;
    const ticketTypeId = document.querySelector('select[name="ticket_type_id"]').value;
    
    if (!status || !ticketTypeId) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Vui lòng điền đầy đủ thông tin bắt buộc',
            confirmButtonText: 'Đóng'
        });
        return false;
    }
});

// Show validation errors
@if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Có lỗi xảy ra!',
        html: '@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach',
        confirmButtonText: 'Đóng'
    });
@endif
</script>
@endsection