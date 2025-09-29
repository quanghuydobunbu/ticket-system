@extends('layouts/admin')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-plus-circle text-success"></i>
                        Tạo vé mới
                    </h5>
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('tickets.store') }}" method="POST" id="ticketForm">
                        @csrf
                        
                        <!-- Ticket Code -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ticket_code" class="form-label">
                                    Mã vé <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control @error('ticket_code') is-invalid @enderror" 
                                           id="ticket_code" 
                                           name="ticket_code" 
                                           value="{{ old('ticket_code', 'TK' . strtoupper(Str::random(8))) }}" 
                                           required>
                                    <button type="button" class="btn btn-outline-secondary" id="generateCodeBtn" title="Tạo mã tự động">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </div>
                                @error('ticket_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Mã vé phải là duy nhất</div>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    Trạng thái <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                        Kích hoạt
                                    </option>
                                    <option value="used" {{ old('status') == 'used' ? 'selected' : '' }}>
                                        Đã sử dụng
                                    </option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                        Đã hủy
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Attendee Name -->
                        <div class="mb-3">
                            <label for="attendee_name" class="form-label">
                                Tên người tham dự <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('attendee_name') is-invalid @enderror" 
                                   id="attendee_name" 
                                   name="attendee_name" 
                                   value="{{ old('attendee_name') }}" 
                                   placeholder="Nhập tên người tham dự..." 
                                   required>
                            @error('attendee_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ticket Type and Booking -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ticket_type_id" class="form-label">
                                    Loại vé <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('ticket_type_id') is-invalid @enderror" 
                                        id="ticket_type_id" 
                                        name="ticket_type_id" 
                                        required>
                                    <option value="">-- Chọn loại vé --</option>
                                    @foreach($ticketTypes as $ticketType)
                                        <option value="{{ $ticketType->id }}" 
                                                data-price="{{ $ticketType->price }}"
                                                data-event="{{ $ticketType->event->title ?? '' }}"
                                                {{ old('ticket_type_id') == $ticketType->id ? 'selected' : '' }}>
                                            {{ $ticketType->name }} 
                                            ({{ number_format($ticketType->price, 0, ',', '.') }}đ)
                                            @if($ticketType->event)
                                                - {{ $ticketType->event->title }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('ticket_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="booking_id" class="form-label">
                                    Booking (Tùy chọn)
                                </label>
                                <select class="form-select @error('booking_id') is-invalid @enderror" 
                                        id="booking_id" 
                                        name="booking_id">
                                    <option value="">-- Chọn booking --</option>
                                    @foreach($bookings as $booking)
                                        <option value="{{ $booking->id }}" 
                                                {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                                            #{{ $booking->booking_code ?? $booking->id }} 
                                            - {{ $booking->attendee_info }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booking_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Liên kết vé với booking có sẵn</div>
                            </div>
                        </div>

                        <!-- QR Code Options -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       role="switch" 
                                       id="generate_qr" 
                                       name="generate_qr" 
                                       value="1"
                                       {{ old('generate_qr', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="generate_qr">
                                    <i class="bi bi-qr-code"></i>
                                    Tự động tạo QR Code
                                </label>
                            </div>
                            <div class="form-text">QR Code sẽ chứa mã vé để check-in nhanh</div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Thêm ghi chú cho vé...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="bi bi-check-circle"></i>
                                Tạo vé
                            </button>
                            <button type="submit" name="action" value="save_and_new" class="btn btn-primary" id="saveAndNewBtn">
                                <i class="bi bi-plus-square"></i>
                                Lưu và tạo tiếp
                            </button>
                            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                                Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-eye"></i>
                        Xem trước vé
                    </h6>
                </div>
                <div class="card-body">
                    <div class="ticket-preview p-3 border rounded" id="ticketPreview">
                        <div class="text-center mb-3">
                            <h6 class="mb-1">Vé tham dự sự kiện</h6>
                            <div id="preview-event" class="text-muted small">Chọn loại vé</div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-5"><small class="text-muted">Mã vé:</small></div>
                            <div class="col-7"><strong id="preview-code">TK12345678</strong></div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-5"><small class="text-muted">Người tham dự:</small></div>
                            <div class="col-7" id="preview-attendee">Chưa nhập</div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-5"><small class="text-muted">Loại vé:</small></div>
                            <div class="col-7" id="preview-type">Chưa chọn</div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-5"><small class="text-muted">Giá:</small></div>
                            <div class="col-7 text-success fw-bold" id="preview-price">0đ</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-5"><small class="text-muted">Trạng thái:</small></div>
                            <div class="col-7">
                                <span class="badge bg-success" id="preview-status">Kích hoạt</span>
                            </div>
                        </div>
                        
                        <div class="text-center" id="preview-qr">
                            <div class="bg-light p-3 rounded d-inline-block">
                                <i class="bi bi-qr-code fs-2 text-muted"></i>
                                <div class="small text-muted mt-1">QR Code sẽ được tạo</div>
                            </div>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <small class="text-muted">Ngày tạo: {{ now()->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-3">
                        <h6 class="mb-2">Thao tác nhanh</h6>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="bulkCreateBtn">
                                <i class="bi bi-stack"></i>
                                Tạo nhiều vé cùng lúc
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" id="importBtn">
                                <i class="bi bi-upload"></i>
                                Import từ Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-lightbulb text-warning"></i>
                        Gợi ý
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Mã vé tự động được tạo với format TK + 8 ký tự
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            QR Code giúp check-in nhanh tại sự kiện
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Liên kết với booking để quản lý đơn hàng
                        </li>
                        <li>
                            <i class="bi bi-check-circle text-success"></i>
                            Có thể tạo nhiều vé cùng lúc với thông tin tương tự
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Create Modal -->
    <div class="modal fade" id="bulkCreateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo nhiều vé cùng lúc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="POST">
                    {{-- {{ route('tickets.bulk-create') }} --}}
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="bulk_ticket_type_id" class="form-label">Loại vé <span class="text-danger">*</span></label>
                                <select class="form-select" name="bulk_ticket_type_id" required>
                                    <option value="">-- Chọn loại vé --</option>
                                    @foreach($ticketTypes as $ticketType)
                                        <option value="{{ $ticketType->id }}">
                                            {{ $ticketType->name }} - {{ number_format($ticketType->price, 0, ',', '.') }}đ
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="bulk_quantity" class="form-label">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="bulk_quantity" min="1" max="100" value="1" required>
                                <div class="form-text">Tối đa 100 vé/lần</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="bulk_attendee_names" class="form-label">Danh sách người tham dự</label>
                            <textarea class="form-control" name="bulk_attendee_names" rows="5" 
                                      placeholder="Mỗi dòng một tên người tham dự&#10;Ví dụ:&#10;Nguyễn Văn A&#10;Trần Thị B&#10;Lê Văn C"></textarea>
                            <div class="form-text">Mỗi dòng một tên. Nếu để trống, tên sẽ được tạo tự động</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="bulk_booking_id" class="form-label">Booking (Tùy chọn)</label>
                            <select class="form-select" name="bulk_booking_id">
                                <option value="">-- Không liên kết --</option>
                                @foreach($bookings as $booking)
                                    <option value="{{ $booking->id }}">
                                        #{{ $booking->booking_code ?? $booking->id }} - {{ $booking->customer_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tạo vé
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const ticketCodeInput = document.getElementById('ticket_code');
    const attendeeNameInput = document.getElementById('attendee_name');
    const ticketTypeSelect = document.getElementById('ticket_type_id');
    const statusSelect = document.getElementById('status');
    const generateCodeBtn = document.getElementById('generateCodeBtn');
    const bulkCreateBtn = document.getElementById('bulkCreateBtn');
    const importBtn = document.getElementById('importBtn');
    
    // Preview elements
    const previewCode = document.getElementById('preview-code');
    const previewAttendee = document.getElementById('preview-attendee');
    const previewType = document.getElementById('preview-type');
    const previewPrice = document.getElementById('preview-price');
    const previewStatus = document.getElementById('preview-status');
    const previewEvent = document.getElementById('preview-event');

    // Generate random ticket code
    function generateTicketCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = 'TK';
        for (let i = 0; i < 8; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return code;
    }

    // Update preview
    function updatePreview() {
        // Update code
        previewCode.textContent = ticketCodeInput.value || 'Chưa nhập';
        
        // Update attendee
        previewAttendee.textContent = attendeeNameInput.value || 'Chưa nhập';
        
        // Update ticket type and price
        const selectedOption = ticketTypeSelect.options[ticketTypeSelect.selectedIndex];
        if (selectedOption.value) {
            previewType.textContent = selectedOption.text.split(' (')[0];
            previewPrice.textContent = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(selectedOption.dataset.price || 0);
            previewEvent.textContent = selectedOption.dataset.event || 'Sự kiện';
        } else {
            previewType.textContent = 'Chưa chọn';
            previewPrice.textContent = '0đ';
            previewEvent.textContent = 'Chọn loại vé';
        }
        
        // Update status
        const statusText = statusSelect.options[statusSelect.selectedIndex].text || 'Kích hoạt';
        const statusValue = statusSelect.value || 'active';
        previewStatus.textContent = statusText;
        previewStatus.className = 'badge ' + 
            (statusValue === 'active' ? 'bg-success' : 
             statusValue === 'used' ? 'bg-primary' : 'bg-danger');
    }

    // Event listeners
    generateCodeBtn.addEventListener('click', function() {
        ticketCodeInput.value = generateTicketCode();
        updatePreview();
    });

    ticketCodeInput.addEventListener('input', updatePreview);
    attendeeNameInput.addEventListener('input', updatePreview);
    ticketTypeSelect.addEventListener('change', updatePreview);
    statusSelect.addEventListener('change', updatePreview);

    bulkCreateBtn.addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('bulkCreateModal')).show();
    });

    importBtn.addEventListener('click', function() {
        Swal.fire({
            title: 'Tính năng sắp có',
            text: 'Tính năng import từ Excel sẽ được phát triển trong phiên bản tiếp theo.',
            icon: 'info'
        });
    });

    // Form submission
    document.getElementById('ticketForm').addEventListener('submit', function(e) {
        const submitBtn = e.submitter;
        const isLoading = submitBtn.querySelector('.spinner-border');
        
        if (!isLoading) {
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
            submitBtn.disabled = true;
            
            // Re-enable after 5 seconds to prevent permanent disable if there's an error
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.innerHTML = originalContent;
                    submitBtn.disabled = false;
                }
            }, 5000);
        }
    });

    // Initialize preview
    updatePreview();
    
    // Auto focus on attendee name if ticket code is already filled
    if (ticketCodeInput.value) {
        attendeeNameInput.focus();
    }
});
</script>

<style>
.ticket-preview {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 0.9rem;
}

.ticket-preview .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

.ticket-preview .bg-light {
    background: rgba(255, 255, 255, 0.1) !important;
    border: 1px dashed rgba(255, 255, 255, 0.3);
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

#ticketPreview {
    transition: all 0.3s ease;
}

#ticketPreview:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>