@extends('layouts/admin')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Tạo sự kiện mới</h5>
        
        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <!-- Thông tin cơ bản -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="mt-3">
                                <label for="title" class="form-label">Tên sự kiện <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug') }}" 
                                       placeholder="Tự động tạo từ tên sự kiện">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả sự kiện</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="5">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="featured_image" class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                                       id="featured_image" name="featured_image" accept="image/*">
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Thời gian -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Thời gian tổ chức</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_datetime" class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-control @error('start_datetime') is-invalid @enderror" 
                                               id="start_datetime" name="start_datetime" value="{{ old('start_datetime') }}" required>
                                        @error('start_datetime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_datetime" class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-control @error('end_datetime') is-invalid @enderror" 
                                               id="end_datetime" name="end_datetime" value="{{ old('end_datetime') }}" required>
                                        @error('end_datetime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="registration_end" class="form-label">Hạn đăng ký</label>
                                <input type="datetime-local" class="form-control @error('registration_end') is-invalid @enderror" 
                                       id="registration_end" name="registration_end" value="{{ old('registration_end') }}">
                                @error('registration_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Để trống nếu cho phép đăng ký đến khi sự kiện bắt đầu</div>
                            </div>
                        </div>
                    </div>

                    <!-- Số lượng tham gia -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Quản lý tham gia</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="max_attendees" class="form-label">Số lượng tham gia tối đa</label>
                                <input type="number" class="form-control @error('max_attendees') is-invalid @enderror" 
                                       id="max_attendees" name="max_attendees" value="{{ old('max_attendees') }}" 
                                       min="1" placeholder="Để trống nếu không giới hạn">
                                @error('max_attendees')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_free" name="is_free" value="0" 
                                       {{ old('is_free') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_free">
                                    Sự kiện miễn phí
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Cài đặt sự kiện</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="organizer_id" class="form-label">Người tổ chức <span class="text-danger">*</span></label>
                                <select class="form-select @error('organizer_id') is-invalid @enderror" 
                                        id="organizer_id" name="organizer_id" required>
                                    <option value="">Chọn người tổ chức</option>
                                    @foreach($organizers as $organizer)
                                        <option value="{{ $organizer->id }}" {{ old('organizer_id') == $organizer->id ? 'selected' : '' }}>
                                            {{ $organizer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('organizer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Chọn danh mục</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="venue_id" class="form-label">Địa điểm</label>
                                <select class="form-select @error('venue_id') is-invalid @enderror" 
                                        id="venue_id" name="venue_id">
                                    <option value="">Chọn địa điểm</option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                            {{ $venue->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('venue_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status">
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Nháp</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="0" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Sự kiện nổi bật
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">
                                    Tạo sự kiện
                                </button>
                                <a href="{{ route('events.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Quay lại
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function removeVietnameseTones(str) {
    const accents = {
        'à': 'a', 'á': 'a', 'ạ': 'a', 'ả': 'a', 'ã': 'a',
        'â': 'a', 'ầ': 'a', 'ấ': 'a', 'ậ': 'a', 'ẩ': 'a', 'ẫ': 'a',
        'ă': 'a', 'ằ': 'a', 'ắ': 'a', 'ặ': 'a', 'ẳ': 'a', 'ẵ': 'a',
        'è': 'e', 'é': 'e', 'ẹ': 'e', 'ẻ': 'e', 'ẽ': 'e',
        'ê': 'e', 'ề': 'e', 'ế': 'e', 'ệ': 'e', 'ể': 'e', 'ễ': 'e',
        'ì': 'i', 'í': 'i', 'ị': 'i', 'ỉ': 'i', 'ĩ': 'i',
        'ò': 'o', 'ó': 'o', 'ọ': 'o', 'ỏ': 'o', 'õ': 'o',
        'ô': 'o', 'ồ': 'o', 'ố': 'o', 'ộ': 'o', 'ổ': 'o', 'ỗ': 'o',
        'ơ': 'o', 'ờ': 'o', 'ớ': 'o', 'ợ': 'o', 'ở': 'o', 'ỡ': 'o',
        'ù': 'u', 'ú': 'u', 'ụ': 'u', 'ủ': 'u', 'ũ': 'u',
        'ư': 'u', 'ừ': 'u', 'ứ': 'u', 'ự': 'u', 'ử': 'u', 'ữ': 'u',
        'ỳ': 'y', 'ý': 'y', 'ỵ': 'y', 'ỷ': 'y', 'ỹ': 'y',
        'đ': 'd',
        // Các ký tự hoa
        'À': 'A', 'Á': 'A', 'Ạ': 'A', 'Ả': 'A', 'Ã': 'A',
        'Â': 'A', 'Ầ': 'A', 'Ấ': 'A', 'Ậ': 'A', 'Ẩ': 'A', 'Ẫ': 'A',
        'Ă': 'A', 'Ằ': 'A', 'Ắ': 'A', 'Ặ': 'A', 'Ẳ': 'A', 'Ẵ': 'A',
        'È': 'E', 'É': 'E', 'Ẹ': 'E', 'Ẻ': 'E', 'Ẽ': 'E',
        'Ê': 'E', 'Ề': 'E', 'Ế': 'E', 'Ệ': 'E', 'Ể': 'E', 'Ễ': 'E',
        'Ì': 'I', 'Í': 'I', 'Ị': 'I', 'Ỉ': 'I', 'Ĩ': 'I',
        'Ò': 'O', 'Ó': 'O', 'Ọ': 'O', 'Ỏ': 'O', 'Õ': 'O',
        'Ô': 'O', 'Ồ': 'O', 'Ố': 'O', 'Ộ': 'O', 'Ổ': 'O', 'Ỗ': 'O',
        'Ơ': 'O', 'Ờ': 'O', 'Ớ': 'O', 'Ợ': 'O', 'Ở': 'O', 'Ỡ': 'O',
        'Ù': 'U', 'Ú': 'U', 'Ụ': 'U', 'Ủ': 'U', 'Ũ': 'U',
        'Ư': 'U', 'Ừ': 'U', 'Ứ': 'U', 'Ự': 'U', 'Ử': 'U', 'Ữ': 'U',
        'Ỳ': 'Y', 'Ý': 'Y', 'Ỵ': 'Y', 'Ỷ': 'Y', 'Ỹ': 'Y',
        'Đ': 'D'
    };
    return str.replace(/[àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐ]/g, function(match) {
        return accents[match] || match;
    });
}

function createSlug(str) {
    return removeVietnameseTones(str)
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-') 
        .replace(/-+/g, '-')
        .replace(/^-+|-+$/g, ''); 
}
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = createSlug(title);
    document.getElementById('slug').value = slug;
});

// Validate thời gian
document.getElementById('start_datetime').addEventListener('change', function() {
    const startTime = new Date(this.value);
    const endTimeInput = document.getElementById('end_datetime');
    
    if (endTimeInput.value) {
        const endTime = new Date(endTimeInput.value);
        if (endTime <= startTime) {
            alert('Thời gian kết thúc phải sau thời gian bắt đầu');
            endTimeInput.value = '';
        }
    }
});

document.getElementById('end_datetime').addEventListener('change', function() {
    const endTime = new Date(this.value);
    const startTimeInput = document.getElementById('start_datetime');
    
    if (startTimeInput.value) {
        const startTime = new Date(startTimeInput.value);
        if (endTime <= startTime) {
            alert('Thời gian kết thúc phải sau thời gian bắt đầu');
            this.value = '';
        }
    }
});

// Validate registration end
document.getElementById('registration_end').addEventListener('change', function() {
    const regEndTime = new Date(this.value);
    const startTimeInput = document.getElementById('start_datetime');
    
    if (startTimeInput.value) {
        const startTime = new Date(startTimeInput.value);
        if (regEndTime >= startTime) {
            alert('Hạn đăng ký phải trước thời gian bắt đầu sự kiện');
            this.value = '';
        }
    }
});
</script>
@endsection