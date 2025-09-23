@extends('layouts/admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Thêm danh mục mới</h5>
            <form class="row g-3" method="POST" action="{{ route('categories.store') }}">
                @csrf
                <div class="col-12">
                    <label for="name" class="form-label">Tên danh mục</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="col-12">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" readonly>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="status" name="status">
                            <option value="1">Kích hoạt</option>
                            <option value="0">Không kích hoạt</option>
                        </select>
                        <label for="status">
                            <i class="bi bi-toggle-on me-1"></i>Trạng thái hoạt động
                        </label>
                    </div>
                </div>   
                <div class="text-between">
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                    <a class="btn btn-warning" href="{{ route('categories.index') }}">Quay lại</a>
                    {{-- <button type="" class="btn btn-secondary">Quay lại</button> --}}
                </div>
            </form><!-- Vertical Form -->
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    let isSlugManuallyEdited = false;

    function removeVietnameseTones(str) {
        const from = "àáãạảăắằẳẵặâấầẩẫậèéẹẻẽêềếểễệđìíĩỉịòóõọỏôốồổỗộơớờởỡợùúũụủưứừửữựỳýỵỷỹ";
        const to   = "aaaaaaaaaaaaaaaaaeeeeeeeeeeeediiiiiooooooooooooooooouuuuuuuuuuuyyyyy";
        
        for (let i = 0, l = from.length; i < l; i++) {
            str = str.replace(RegExp(from[i], "gi"), to[i]);
        }
        
        str = str.toLowerCase()
                 .trim()
                 .replace(/[^a-z0-9\s-]/g, "") // Loại bỏ ký tự đặc biệt
                 .replace(/\s+/g, "-")          // Thay thế khoảng trắng bằng dấu gạch ngang
                 .replace(/-+/g, "-")           // Loại bỏ nhiều dấu gạch ngang liên tiếp
                 .replace(/^-+|-+$/g, "");      // Loại bỏ dấu gạch ngang ở đầu và cuối
        
        return str;
    }

    nameInput.addEventListener('input', function() {
        if (!isSlugManuallyEdited) {
            const slug = removeVietnameseTones(this.value);
            slugInput.value = slug;
        }
    });

    slugInput.addEventListener('input', function() {
        isSlugManuallyEdited = this.value !== removeVietnameseTones(nameInput.value);
    });

    document.querySelector('form').addEventListener('reset', function() {
        isSlugManuallyEdited = false;
        setTimeout(() => {
            nameInput.value = '';
            slugInput.value = '';
        }, 10);
    });

    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                slugInput.style.borderColor = '#28a745';
                slugInput.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
                
                setTimeout(() => {
                    slugInput.style.borderColor = '';
                    slugInput.style.boxShadow = '';
                }, 500);
            }
        });
    });

    nameInput.addEventListener('blur', function() {
        if (this.value.trim() === '') {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    slugInput.addEventListener('blur', function() {
        if (this.value.trim() === '') {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
});
</script>

<style>
.form-text {
    color: #6c757d;
    font-size: 0.875rem;
}

.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

/* Animation cho slug input */
#slug {
    transition: all 0.3s ease;
}

#slug:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>