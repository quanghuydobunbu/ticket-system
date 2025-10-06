<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link " href="{{ route('admin.dashboard') }}">
          <i class="bi bi-grid"></i>
          <span>Bảng điều khiển</span>
        </a>
      </li><!-- End Dashboard Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Quản lý người dùng</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('users.index') }}">
              <i class="bi bi-circle"></i><span>Quản lý người dùng</span>
            </a>
          </li>
          {{-- <li>
            <a href="components-accordion.html">
              <i class="bi bi-circle"></i><span>Quản lý vai trò</span>
            </a>
          </li> --}}
          <li>
            <a href="{{ route('role_premissions.index') }}">
              <i class="bi bi-circle"></i><span>Phân quyền cho vai trò</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Quản lý sự kiện</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('categories.index') }}">
              <i class="bi bi-circle"></i><span>Danh mục sự kiện</span>
            </a>
          </li>
          <li>
            <a href="{{ route('events.index') }}">
              <i class="bi bi-circle"></i><span>Các sự kiện</span>
            </a>
          </li>
          <li>
            <a href="{{ route('venues.index') }}">
              <i class="bi bi-circle"></i><span>Các địa điểm</span>
            </a>
          </li>
        </ul>
      </li><!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Quản lý vé</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('tickets.index') }}">
              <i class="bi bi-circle"></i><span>Quản lý vé sự kiện</span>
            </a>
          </li>
          <li>
            <a href="{{ route('ticket-types.index') }}">
              <i class="bi bi-circle"></i><span>Quản lý loại vé sự kiện</span>
            </a>
          </li>
          <li>
            <a href="{{ route('check-in.scanner') }}">
              <i class="bi bi-circle"></i><span>Quét vé</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" href="{{ route('bookings.index') }}">
          <i class="bi bi-bar-chart"></i><span>Quản lý đơn hàng</span>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          {{-- <li>
            <a href="{{ route('bookings.index') }}">
              <i class="bi bi-circle"></i><span>Quản lý đơn hàng</span>
            </a>
          </li> --}}
          {{-- <li>
            <a href="charts-apexcharts.html">
              <i class="bi bi-circle"></i><span>Chi tiết đơn hàng</span>
            </a>
          </li> --}}
          {{-- <li>
            <a href="charts-echarts.html">
              <i class="bi bi-circle"></i><span>Quản lý phương thức thanh toán</span>
            </a>
          </li> --}}
        </ul>
      </li><!-- End Charts Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gem"></i><span>Cài đặt</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          
          <li>
            <a href="icons-remix.html">
              <i class="bi bi-circle"></i><span>Remix Icons</span>
            </a>
          </li>
          <li>
            <a href="icons-boxicons.html">
              <i class="bi bi-circle"></i><span>Boxicons</span>
            </a>
          </li>
        </ul>
      </li><!-- End Icons Nav -->
    </ul>

  </aside><!-- End Sidebar-->