@extends('layouts/admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-eye me-2"></i>Chi tiết danh mục
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Tên danh mục -->
                <div class="col-12">
                    <label class="form-label fw-bold">
                        <i class="bi bi-tag me-1"></i>Tên danh mục
                    </label>
                    <div class="form-control-plaintext border rounded p-2 bg-light">
                        {{ $category->name }}
                    </div>
                </div>

                <!-- Slug -->
                <div class="col-12">
                    <label class="form-label fw-bold">
                        <i class="bi bi-link me-1"></i>Slug
                    </label>
                    <div class="form-control-plaintext border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                        <span class="text-muted">{{ $category->slug }}</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('{{ $category->slug }}')">
                            <i class="bi bi-clipboard me-1"></i>Sao chép
                        </button>
                    </div>
                </div>

                <!-- Trạng thái -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="bi bi-toggle-on me-1"></i>Trạng thái hoạt động
                    </label>
                    <div class="form-control-plaintext border rounded p-2 bg-light">
                        @if($category->is_active == 1)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Kích hoạt
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle me-1"></i>Không kích hoạt
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="bi bi-calendar-plus me-1"></i>Ngày tạo
                    </label>
                    <div class="form-control-plaintext border rounded p-2 bg-light">
                        <span class="text-muted">{{ $category->created_at ?? '' }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="bi bi-calendar-check me-1"></i>Cập nhật lần cuối
                    </label>
                    <div class="form-control-plaintext border rounded p-2 bg-light">
                        <span class="text-muted">{{ $category->updated_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="bi bi-hash me-1"></i>ID
                    </label>
                    <div class="form-control-plaintext border rounded p-2 bg-light">
                        <span class="text-muted">#{{ $category->id }}</span>
                    </div>
                </div>

                @if(isset($category->posts_count))
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="bi bi-file-text me-1"></i>Số bài viết
                    </label>
                    <div class="form-control-plaintext border rounded p-2 bg-light">
                        <span class="badge bg-info">{{ $category->posts_count }} bài viết</span>
                    </div>
                </div>
                @endif

                @if(isset($category->description) && $category->description)
                <div class="col-12">
                    <label class="form-label fw-bold">
                        <i class="bi bi-card-text me-1"></i>Mô tả
                    </label>
                    <div class="form-control-plaintext border rounded p-3 bg-light">
                        {{ $category->description }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Action buttons -->
            <div class="action-buttons mt-4">
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
                </a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash me-1"></i>Xóa danh mục
                </button>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                        Xác nhận xóa danh mục
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Cảnh báo:</strong> Hành động này không thể hoàn tác!
                    </div>
                    <p>Bạn có chắc chắn muốn xóa danh mục <strong>"{{ $category->name }}"</strong> không?</p>
                    @if(isset($category->posts_count) && $category->posts_count > 0)
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Danh mục này có <strong>{{ $category->posts_count }} bài viết</strong>. 
                            Vui lòng di chuyển hoặc xóa các bài viết trước khi xóa danh mục.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Hủy bỏ
                    </button>
                    @if(!isset($category->posts_count) || $category->posts_count == 0)
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i>Xóa danh mục
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy to clipboard function
    window.copyToClipboard = function(text) {
        if (navigator.clipboard && window.isSecureContext) {
            // Modern clipboard API
            navigator.clipboard.writeText(text).then(function() {
                showToast('Đã sao chép slug vào clipboard!', 'success');
            }, function(err) {
                console.error('Không thể sao chép: ', err);
                fallbackCopyTextToClipboard(text);
            });
        } else {
            // Fallback for older browsers
            fallbackCopyTextToClipboard(text);
        }
    };

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showToast('Đã sao chép slug vào clipboard!', 'success');
            } else {
                showToast('Không thể sao chép slug!', 'error');
            }
        } catch (err) {
            console.error('Fallback: Không thể sao chép', err);
            showToast('Không thể sao chép slug!', 'error');
        }

        document.body.removeChild(textArea);
    }

    function showToast(message, type = 'success') {
        // Tạo toast element
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        toast.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideInRight 0.3s ease;
        `;
        toast.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
        `;

        document.body.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    // Hover effects for action buttons
    const buttons = document.querySelectorAll('.action-buttons .btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
        });

        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>

<style>
/* Card styling */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.card-title {
    font-weight: 600;
}

/* Form styling */
.form-label.fw-bold {
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-control-plaintext {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef !important;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    min-height: 45px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.form-control-plaintext:hover {
    background-color: #e9ecef !important;
    border-color: #dee2e6 !important;
}

/* Badge styling */
.badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
}

.badge.bg-success {
    background: linear-gradient(45deg, #28a745, #20c997) !important;
}

.badge.bg-danger {
    background: linear-gradient(45deg, #dc3545, #e83e8c) !important;
}

/* .badge.bg-info {
    background: linear-gradient(45deg, #17a2b8, #6f42c1) !important;
} */

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.action-buttons .btn {
    transition: all 0.3s ease;
    border-radius: 8px;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
}


.btn-secondary {
    background: linear-gradient(45deg, #6c757d, #495057);
    border: none;
}

.btn-danger {
    background: linear-gradient(45deg, #dc3545, #c82333);
    border: none;
}

/* Alert animations */
.alert {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOutRight {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

/* Modal styling */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    border-radius: 12px 12px 0 0;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    border-radius: 0 0 12px 12px;
}

/* Responsive design */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .form-control-plaintext {
        font-size: 0.9rem;
    }
}

/* Copy button hover effect */
.btn-outline-secondary:hover {
    transform: scale(1.05);
}

/* Smooth transitions *
* {
    transition: all 0.3s ease;
}
</style>