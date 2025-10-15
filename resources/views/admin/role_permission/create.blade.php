@extends('layouts/admin')

@section('title')
   Cấp quyền cho vai trò người dùng
@endsection

@section('content')

<?php
    $role_id = request()->query('role_id');
?>

<div class="pagetitle">
    <h1>Cấp quyền cho vai trò người dùng</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('role_premissions.index') }}">Quản lý phân quyền</a></li>
        <li class="breadcrumb-item active">Cấp quyền cho vai trò</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section py-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
              <h5 class="card-title mb-0 text-white">Cấp quyền cho vai trò</h5>
            </div>
            <div class="card-body">
              <form action="{{ route('role_premissions.store') }}" method="POST" id="rolePermissionForm">
                @csrf

                @if (!empty($role_id))
                  <div class="my-4">
                    <label for="role_id" class="form-label fw-bold">Vai trò</label>
                    <select disabled class="form-select form-select-lg mb-3" name="role_id_display" id="role_id_display" aria-label="Chọn vai trò">
                      <option value="">-- Chọn vai trò --</option>
                      @foreach ($list_roles as $role)
                        <option value="{{ $role->id }}" {{ $role->id == $role_id ? 'selected' : '' }}>{{ $role->description }}</option>
                      @endforeach
                    </select>
                    <input type="hidden" name="role_id" id="role_id" value="{{ $role_id }}">
                  </div>
                @else
                  <div class="mb-4">
                    <label for="role_id" class="form-label fw-bold">Vai trò</label>
                    <select class="form-select form-select-lg mb-3" name="role_id" id="role_id" aria-label="Chọn vai trò">
                      <option value="">-- Chọn vai trò --</option>
                      @foreach ($list_roles as $role)
                        <option value="{{ $role->id }}">{{ $role->description }}</option>
                      @endforeach
                    </select>
                  </div>
                @endif

                <div class="mt-4 mb-4" id="permission_id">
                  <h6 class="fw-bold mb-3">Danh sách quyền</h6>
                  <div class="card">
                    <div class="card-body">
                      <div class="row" id="permissionsContainer">
                        @foreach ($list_permissions as $i => $permission)
                          <div class="col-md-6 mb-2">
                            <div class="form-check">
                              <input class="form-check-input" name="permission_id[]" type="checkbox" value="{{ $permission->id }}" id="permission_id_{{ $i }}">
                              <label class="form-check-label" for="permission_id_{{ $i }}">
                                {{ $permission->description }}
                              </label>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                  <a href="{{ route('role_premissions.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                  </a>
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Lưu lại
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
$(document).ready(function(){
    // Hàm load permissions cho role
    function loadRolePermissions(role_id) {
        if(!role_id) {
            // Nếu không có role_id, bỏ check hết
            $("#permission_id input[type='checkbox']").prop('checked', false);
            return;
        }
        
        var apiUrl = "{{ route('admin.api.role_has_permission.getRoleId', '') }}/" + role_id;
        
        $.ajax({
            method: 'GET',
            url: apiUrl,
            dataType: 'json'
        })
        .done(function(response){
            console.log('API Response:', response); // Debug
            
            if(response.success && response.data) {
                var arrPermission = response.data;
                
                $("#permission_id input[type='checkbox']").prop('checked', false);
                
                $("#permission_id input[type='checkbox']").each(function(){
                    var value = parseInt($(this).val());
                    if(arrPermission.includes(value)){
                        $(this).prop('checked', true);
                    }
                });
            }
        })
        .fail(function(xhr, status, error) {
            console.error('AJAX Error:', error); // Debug
            alert('Có lỗi khi tải dữ liệu quyền');
        });
    }
    
    $('#role_id').on('change', function(){
        var role_id = $(this).val();
        loadRolePermissions(role_id);
    });

    @if (!empty($role_id))
        loadRolePermissions({{ $role_id }});
    @endif
});
</script>

@endsection

