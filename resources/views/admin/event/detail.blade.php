@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            @if($event->featured_image)
                <img src="{{ asset('storage/events'. '/' . $event->featured_image) }}" class="card-img-top" alt="{{ $event->title }}" style="height: 400px; object-fit: cover;">
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                    <span class="text-muted" style="font-size: 3rem;">📷</span>
                </div>
            @endif
            
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mt-3">
                    <h1 class="card-title h3 mb-0">{{ $event->title }}</h1>
                    @if($event->is_featured)
                        <span class="badge bg-warning text-dark">⭐ Nổi bật</span>
                    @endif
                </div>

                <div class="mb-3">
                    {{-- @dd($event->status) --}}
                    <span class="badge bg-black">
                        {{ $event->status->label() }}
                    </span>
                    
                    {{-- @if($event->status == "draft")  
                        <span class="badge bg-secondary fs-6">Nháp</span>
                    @elseif($event->status == 'published')
                        <span class="badge bg-success fs-6">Đã xuất bản</span>
                    @elseif($event->status == 'cancelled')
                        <span class="badge bg-danger fs-6">Đã hủy</span>
                    @else
                        <span class="badge bg-info fs-6">Đã hoàn thành</span>
                    @endif --}}
                    
                    @if($event->is_free)
                        <span class="badge bg-info ms-2">Miễn phí</span>
                    @endif
                </div>

                @if($event->description)
                    <div class="mb-4">
                        <h5>Mô tả sự kiện</h5>
                        <p class="text-muted">{{ $event->description }}</p>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body">
                                <h6 class="card-title text-success">📅 Thời gian bắt đầu</h6>
                                <p class="card-text">
                                    <strong>{{ \Carbon\Carbon::parse($event->start_datetime)->format('d/m/Y') }}</strong><br>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($event->start_datetime)->format('H:i') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-danger">
                            <div class="card-body">
                                <h6 class="card-title text-danger">📅 Thời gian kết thúc</h6>
                                <p class="card-text">
                                    <strong>{{ \Carbon\Carbon::parse($event->end_datetime)->format('d/m/Y') }}</strong><br>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($event->end_datetime)->format('H:i') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($event->registration_end)
                    <div class="alert alert-warning">
                        ⏰ <strong>Hạn đăng ký:</strong> {{ \Carbon\Carbon::parse($event->registration_end)->format('d/m/Y H:i') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">ℹ️ Thông tin sự kiện</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Người tổ chức</small>
                    {{-- <div class="fw-bold">{{ $event->organizer->name }}</div> --}}
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Danh mục</small>
                    <div>
                        <span class="badge bg-primary">{{ $event->category->name }}</span>
                    </div>
                </div>

                @if($event->venue)
                    <div class="mb-3">
                        <small class="text-muted">Địa điểm</small>
                        <div class="fw-bold">📍 {{ $event->venue->name }}</div>
                        @if($event->venue->address)
                            <small class="text-muted">{{ $event->venue->address }}</small>
                        @endif
                    </div>
                @endif

                <div class="mb-3">
                    <small class="text-muted">Ngày tạo</small>
                    <div>{{ $event->created_at->format('d/m/Y H:i') }}</div>
                </div>

                @if($event->updated_at != $event->created_at)
                    <div class="mb-3">
                        <small class="text-muted">Cập nhật lần cuối</small>
                        <div>{{ $event->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">👥 Thông tin tham gia</h6>
            </div>
            <div class="card-body">
                @if($event->max_attendees)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Sức chứa:</span>
                            <strong>{{ number_format($event->max_attendees) }} người</strong>
                        </div>
                        
                        @php
                            $registeredCount = $event->registrations ? $event->registrations->count() : 0;
                            $percentage = $event->max_attendees > 0 ? ($registeredCount / $event->max_attendees) * 100 : 0;
                        @endphp
                        
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%"></div>
                        </div>
                        <small class="text-muted">
                            {{ $registeredCount }} / {{ number_format($event->max_attendees) }} đã đăng ký
                        </small>
                    </div>
                @else
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Sức chứa:</span>
                            <strong class="text-success">Không giới hạn</strong>
                        </div>
                        @php
                            $registeredCount = $event->registrations ? $event->registrations->count() : 0;
                        @endphp
                        <small class="text-muted">{{ $registeredCount }} người đã đăng ký</small>
                    </div>
                @endif

                @if(!$event->is_free)
                    <div class="alert alert-info">
                        💰 <strong>Sự kiện có phí</strong>
                    </div>
                @endif

                @php
                    $now = now();
                    $startTime = \Carbon\Carbon::parse($event->start_datetime);
                    $endTime = \Carbon\Carbon::parse($event->end_datetime);
                @endphp

                @if($now < $startTime)
                    <div class="alert alert-success">
                        ⏳ <strong>Sự kiện sẽ diễn ra sau:</strong><br>
                        {{ $startTime->diffForHumans() }}
                    </div>
                @elseif($now >= $startTime && $now <= $endTime)
                    <div class="alert alert-warning">
                        📡 <strong>Sự kiện đang diễn ra</strong>
                    </div>
                @else
                    <div class="alert alert-secondary">
                        ✅ <strong>Sự kiện đã kết thúc</strong>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-primary">
                        ✏️ Chỉnh sửa
                    </a>
                    
                    @if($event->status === 'published')
                        <a href="#" class="btn btn-info">
                            👥 Quản lý đăng ký
                        </a>
                    @endif
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        🗑️ Xóa sự kiện
                    </button>
                    
                    <a href="{{ route('events.index') }}" class="btn btn-secondary">
                        ⬅️ Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sự kiện <strong>"{{ $event->title }}"</strong>?</p>
                <p class="text-danger"><small>Hành động này không thể hoàn tác.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.card-img-top {
    border-radius: 0.375rem 0.375rem 0 0;
}

.progress {
    background-color: #e9ecef;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 0.5rem 0.5rem 0 0;
}

@media (max-width: 768px) {
    .card-img-top {
        height: 250px !important;
    }
}
</style>
@endsection