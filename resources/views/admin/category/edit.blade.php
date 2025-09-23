@extends('layouts/admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Sửa danh mục</h5>
            <form class="row g-3" method="POST" action="{{ route('categories.update', $category->id) }}">
                @csrf
                @method('PUT')
                <div class="col-12">
                    <label for="name" class="form-label">Tên danh mục</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $category->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                           id="slug" name="slug" value="{{ old('slug', $category->slug) }}" readonly>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        Slug sẽ được tự động tạo từ tên danh mục hoặc bạn có thể chỉnh sửa thủ công
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="1" {{ old('status', $category->is_active) == 1 ? 'selected' : '' }}>Kích hoạt</option>
                            <option value="0" {{ old('status', $category->is_active) == 0 ? 'selected' : '' }}>Không kích hoạt</option>
                        </select>
                        <label for="status">
                            <i class="bi bi-toggle-on me-1"></i>Trạng thái hoạt động
                        </label>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="auto_generate_slug" checked>
                        <label class="form-check-label" for="auto_generate_slug">
                            <i class="bi bi-gear me-1"></i>Tự động tạo slug từ tên danh mục
                        </label>
                    </div>
                </div>
                <div class="text-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Cập nhật
                    </button>
                    <a class="btn btn-warning" href="{{ route('categories.index') }}">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại
                    </a>
                    <button type="button" class="btn btn-secondary" id="reset-form">
                        <i class="bi bi-arrow-clockwise me-1"></i>Khôi phục
                    </button>
                </div>
            </form><!-- Vertical Form -->
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
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const autoGenerateCheckbox = document.getElementById('auto_generate_slug');
    const resetButton = document.getElementById('reset-form');
    
    // Lưu giá trị ban đầu
    const originalValues = {
        name: nameInput.value,
        slug: slugInput.value,
        status: document.getElementById('status').value
    };
    
    let isSlugManuallyEdited = false;

    function removeVietnameseTones(str) {
        const from = "àáãạảăắằẳẵặâấầẩẫậèéẹẻẽêềếểễệđìíĩỉịòóõọỏôốồổỗộơớờởỡợùúũụủưứừửữựỳýỵỷỹ";
        const to   = "aaaaaaaaaaaaaaaaaeeeeeeeeeeeediiiiiooooooooooooooooouuuuuuuuuuuyyyyy";
        
        for (let i = 0, l = from.length; i < l; i++) {
            str = str.replace(RegExp(from[i], "gi"), to[i]);
        }
        
        str = str.toLowerCase()
                 .trim()
                 .replace(/[^a-z0-9\s-]/g, "") 
                 .replace(/\s+/g, "-")          
                 .replace(/-+/g, "-")          
                 .replace(/^-+|-+$/g, "");    
        
        return str;
    }

    // Tự động tạo slug khi nhập tên (chỉ khi checkbox được check và slug chưa được sửa thủ công)
    nameInput.addEventListener('input', function() {
        if (autoGenerateCheckbox.checked && !isSlugManuallyEdited) {
            const slug = removeVietnameseTones(this.value);
            slugInput.value = slug;
            
            slugInput.style.borderColor = '#28a745';
            slugInput.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
            
            setTimeout(() => {
                slugInput.style.borderColor = '';
                slugInput.style.boxShadow = '';
            }, 500);
        }
    });

    // Theo dõi việc chỉnh sửa slug thủ công
    slugInput.addEventListener('input', function() {
        if (autoGenerateCheckbox.checked) {
            const expectedSlug = removeVietnameseTones(nameInput.value);
            isSlugManuallyEdited = this.value !== expectedSlug;
        }
    });

    // Xử lý checkbox tự động tạo slug
    autoGenerateCheckbox.addEventListener('change', function() {
        if (this.checked) {
            slugInput.readOnly = true;
            isSlugManuallyEdited = false;
            // Tạo lại slug từ tên hiện tại
            const slug = removeVietnameseTones(nameInput.value);
            slugInput.value = slug;
        } else {
            slugInput.readOnly = false;
        }
    });

    // Nút khôi phục về giá trị ban đầu
    resetButton.addEventListener('click', function() {
        nameInput.value = originalValues.name;
        slugInput.value = originalValues.slug;
        document.getElementById('status').value = originalValues.status;
        isSlugManuallyEdited = false;
        autoGenerateCheckbox.checked = true;
        slugInput.readOnly = true;
        
        // Xóa các lớp validation
        [nameInput, slugInput].forEach(input => {
            input.classList.remove('is-invalid', 'is-valid');
        });
        
        // Hiệu ứng visual
        [nameInput, slugInput].forEach(input => {
            input.style.borderColor = '#17a2b8';
            input.style.boxShadow = '0 0 0 0.2rem rgba(23, 162, 184, 0.25)';
        });
        
        setTimeout(() => {
            [nameInput, slugInput].forEach(input => {
                input.style.borderColor = '';
                input.style.boxShadow = '';
            });
        }, 1000);
    });

    // Validation khi blur
    nameInput.addEventListener('blur', function() {
        if (this.value.trim() === '') {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    slugInput.addEventListener('blur', function() {
        if (this.value.trim() === '') {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    // Hiển thị confirmation khi có thay đổi
    const form = document.querySelector('form');
    let hasChanges = false;

    [nameInput, slugInput, document.getElementById('status')].forEach(input => {
        input.addEventListener('input', function() {
            checkForChanges();
        });
        input.addEventListener('change', function() {
            checkForChanges();
        });
    });

    function checkForChanges() {
        hasChanges = (
            nameInput.value !== originalValues.name ||
            slugInput.value !== originalValues.slug ||
            document.getElementById('status').value !== originalValues.status
        );
        
        // Thay đổi text của nút submit nếu có thay đổi
        // const submitBtn = document.querySelector('button[type="submit"]');
        // if (hasChanges) {
        //     submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Lưu thay đổi';
        //     submitBtn.classList.add('btn-success');
        //     submitBtn.classList.remove('btn-primary');
        // } else {
        //     submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Cập nhật';
        //     submitBtn.classList.remove('btn-success');
        //     submitBtn.classList.add('btn-primary');
        // }
    }

    // Confirmation khi rời trang mà có thay đổi
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = 'Bạn có thay đổi chưa được lưu. Bạn có chắc chắn muốn rời trang?';
        }
    });

    // Không hiển thị confirmation khi submit form
    form.addEventListener('submit', function() {
        hasChanges = false;
    });
});
</script>

<style>
.form-text {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.is-valid {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
}

/* Animation cho các input */
#slug, #name {
    transition: all 0.3s ease;
}

#slug:focus, #name:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Style cho checkbox */
.form-check {
    padding: 0.5rem 0;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

/* Button hover effects */
.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Alert animations */
.alert {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Text between buttons */
.text-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .text-between {
        flex-direction: column;
        align-items: stretch;
    }
    
    .text-between .btn {
        margin-bottom: 0.5rem;
    }
}
</style>