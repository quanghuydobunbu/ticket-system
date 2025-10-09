@extends('layouts/admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Báo cáo vé bán</h5>

            <canvas id="lineChart" style="max-height: 400px;"></canvas>
            
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    new Chart(document.querySelector('#lineChart'), {
                        type: 'line',
                        data: {
                            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                                     'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                            datasets: [{
                                label: 'Tổng vé bán ra',
                                data: @json($chartData['sold']),
                                fill: false,
                                borderColor: 'rgb(75, 192, 192)',
                                tension: 0.1
                            },
                            {
                                label: 'Doanh thu (VNĐ)',
                                data: @json($chartData['revenue']),
                                fill: false,
                                borderColor: 'rgb(75, 0, 192)',
                                tension: 0.1
                            },
                            {
                                label: 'Vé còn lại',
                                data: @json($chartData['remaining']),
                                fill: false,
                                borderColor: 'rgb(255, 99, 132)',
                                tension: 0.1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            // Format số cho doanh thu
                                            if (context.datasetIndex === 1) {
                                                label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' đ';
                                            } else {
                                                label += context.parsed.y;
                                            }
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Thống kê sự kiện năm {{ date('Y') }}</h5>

        <canvas id="barChart" style="max-height: 400px; display: block; box-sizing: border-box; height: 400px; width: 100%;"></canvas>
        
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            // Lấy dữ liệu từ Laravel Controller
            const statistics = @json($statistics);

            new Chart(document.querySelector('#barChart'), {
              type: 'bar',
              data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                        'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                datasets: [
                  {
                    label: 'Tổng số sự kiện',
                    data: statistics.total,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 2
                  },
                  {
                    label: 'Sự kiện đã hoàn thành',
                    data: statistics.completed,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 2
                  },
                  {
                    label: 'Sự kiện đang diễn ra',
                    data: statistics.published,
                    backgroundColor: 'rgba(255, 205, 86, 0.5)',
                    borderColor: 'rgb(255, 205, 86)',
                    borderWidth: 2
                  },
                  {
                    label: 'Sự kiện bị hủy',
                    data: statistics.cancelled,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 2
                  }
                ]
              },
              options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                  y: {
                    beginAtZero: true,
                    ticks: {
                      stepSize: 1,
                      precision: 0
                    }
                  }
                },
                plugins: {
                  legend: {
                    display: true,
                    position: 'top',
                  },
                  tooltip: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y + ' sự kiện';
                      }
                    }
                  }
                }
              }
            });
          });
        </script>

      </div>
    </div>

    <div class="card">
      <div class="card-body">
          <h5 class="card-title">Thống kê người dùng</h5>

          <!-- Hiển thị số liệu -->
          <div class="row mb-3">
              <div class="col-md-4 text-center">
                  <h4 class="text-primary">{{ number_format($userStats['total']) }}</h4>
                  <small>Tổng người dùng</small>
              </div>
              <div class="col-md-4 text-center">
                  <h4 class="text-success">{{ number_format($userStats['active']) }}</h4>
                  <small>Đang hoạt động</small>
              </div>
              <div class="col-md-4 text-center">
                  <h4 class="text-danger">{{ number_format($userStats['inactive']) }}</h4>
                  <small>Không hoạt động</small>
              </div>
          </div>

          <canvas id="pieChart" style="max-height: 400px; display: block; box-sizing: border-box; height: 400px; width: 100%;"></canvas>
          
          <script>
              document.addEventListener("DOMContentLoaded", () => {
                  // Lấy dữ liệu từ Laravel
                  const userStats = @json($userStats);

                  new Chart(document.querySelector('#pieChart'), {
                      type: 'pie',
                      data: {
                          labels: [
                              'Tài khoản không hoạt động',
                              'Tài khoản hoạt động',
                          ],
                          datasets: [{
                              label: 'Số người dùng',
                              data: [userStats.inactive, userStats.active],
                              backgroundColor: [
                                  'rgb(255, 99, 132)',
                                  'rgb(50, 200, 200)',
                              ],
                              borderColor: [
                                  'rgb(255, 255, 255)',
                                  'rgb(255, 255, 255)',
                              ],
                              borderWidth: 2,
                              hoverOffset: 10
                          }]
                      },
                      options: {
                          responsive: true,
                          maintainAspectRatio: true,
                          plugins: {
                              legend: {
                                  position: 'bottom',
                                  labels: {
                                      padding: 20,
                                      font: {
                                          size: 14
                                      }
                                  }
                              },
                              tooltip: {
                                  callbacks: {
                                      label: function(context) {
                                          const label = context.label || '';
                                          const value = context.parsed;
                                          const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                          const percentage = ((value / total) * 100).toFixed(1);
                                          return label + ': ' + value.toLocaleString() + ' (' + percentage + '%)';
                                      }
                                  }
                              }
                          }
                      }
                  });
              });
          </script>
      </div>
  </div>
@endsection