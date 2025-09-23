@extends('layouts/admin')

@section('content')

                      
                      @foreach (\App\Enums\UserRole::cases() as $status)
                        <option value="">{{ $status->value }}</option>
                      @endforeach



    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Danh mục sự kiện</h5>
              <a href="{{ route('categories.create') }}" class="btn btn-success">Thêm danh mục</a>

              <!-- Table with stripped rows -->
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tên sự kiện</th>
                    <th scope="col">Địa điểm diễn ra</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <th scope="row">{{ $event->id }}</th>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->slug }}</td>
                            <td>{{ $event->is_active == 1 ? 'Hoạt động' : 'Không hoạt động'}}</td>
                            <td>
                                <a href="">Thêm</a>
                                <a href="">Sửa</a>
                                <a href="">Xóa</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
              <!-- End Table with stripped rows -->
            </div>
          </div>
@endsection