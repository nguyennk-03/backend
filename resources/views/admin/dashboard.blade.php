@extends('admin.layout')

@section('title', 'Bảng Điều Khiển')

@section('content')
<div class="container-fluid">

    <div class="container mt-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="page-title-box d-flex justify-content-between align-items-center p-4 rounded shadow-lg bg-gradient-primary text-white">
                    <h4 class="page-title mb-0 fw-bold">
                        <i class="la la-dashboard me-2"></i>Trang Quản Lý
                    </h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active ">Trang Quản Lý</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Tổng quan thống kê - Dạng hình tròn -->
        <div class="row mb-5 d-flex flex-row flex-nowrap justify-content-center g-4 text-white">
            @php
            $stats = [
            ['title' => 'Tổng Đơn Hàng', 'value' => $totalOrders, 'icon' => 'fas fa-box', 'color' => 'primary'],
            ['title' => 'Đơn Hàng Chờ', 'value' => $totalPendingOrders, 'icon' => 'fas fa-clock', 'color' => 'warning'],
            ['title' => 'Doanh Thu', 'value' => number_format($revenue, 0, ',', '.') . ' VND', 'icon' => 'fas fa-money-bill-wave', 'color' => 'success'],
            ['title' => 'Số Lượng Sản Phẩm', 'value' => $totalProducts, 'icon' => 'fas fa-cogs', 'color' => 'info'],
            ];
            @endphp

            @foreach($stats as $stat)
            <div class="col-md-3 col-sm-6 stat-card-wrapper">
                <div class="card shadow-lg text-center rounded-circle p-4 stat-card hover-scale border-0">
                    <div class="card-body">
                        <i class="{{ $stat['icon'] }} fa-3x mb-3 text-{{ $stat['color'] }}"></i>
                        <h5 class="card-title text-muted">{{ $stat['title'] }}</h5>
                        <p class="h4 fw-bold text-{{ $stat['color'] }}">{{ $stat['value'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Bố cục chính: Biểu đồ bên trái, Đơn hàng mới nhất bên phải -->
        <div class="row g-4">
            <!-- Cột trái: Biểu đồ Doanh Thu và Trạng Thái Đơn Hàng -->
            <div class="col-lg-7">
                <!-- Biểu đồ Doanh Thu và Số Đơn Hàng -->
                <div class="card shadow-lg border-0 rounded-lg mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title fw-bold text-primary">
                                <i class="bi bi-bar-chart me-2"></i>Doanh Thu và Số Đơn Hàng
                            </h5>
                            <!-- Bộ lọc thời gian -->
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                    id="timeFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Theo Tháng
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="timeFilterDropdown">
                                    <li><a class="dropdown-item" href="#" data-filter="week">Theo Tuần</a></li>
                                    <li><a class="dropdown-item active " href="#" data-filter="month">Theo Tháng</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="year">Theo Năm</a></li>
                                </ul>
                            </div>
                        </div>
                        <canvas id="comboChart" style="max-height: 400px;"></canvas>
                    </div>
                </div>

                <!-- Biểu đồ Trạng Thái Đơn Hàng -->
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 fw-bold text-danger">
                            <i class="bi bi-pie-chart me-2"></i>Trạng Thái Đơn Hàng
                        </h5>
                        <canvas id="orderStatusChart" style="max-height: 400px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Đơn Hàng Mới Nhất -->
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body p-3">
                        <h5 class="card-title mb-4 fw-bold text-primary">
                            <i class="bi bi-clock-history me-2"></i>Đơn Hàng Mới Nhất
                        </h5>
                        <div class="order-list">
                            @forelse($latestOrders as $order)
                            <div class="order-item mb-3 p-3 border border-light rounded shadow-sm hover-scale">
                                <div class="row g-3 align-items-center">
                                    <!-- Cột trái -->
                                    <div class="col-8">
                                        <h6 class="mb-1 fw-bold">{{ $order->user->name }}</h6>
                                        <div class="d-flex flex-column">
                                            <span class="text-muted fs-13 mt-1">
                                                <strong>
                                                    <i class="{{ $order->status->iconClass() }} me-1"></i> Trạng thái:
                                                </strong>
                                                <span class="badge {{ $order->status->badgeClass() }} fs-12 mb-1">
                                                    {{ $order->status->label() }}
                                                </span>
                                            </span>
                                            <span class="text-muted fs-12">
                                                <strong>
                                                    <i class="bi bi-upc-scan me-1"></i> Mã đơn hàng:
                                                </strong>
                                                #{{ $order->code ?? 'N/A' }}
                                            </span>
                                            <span class="text-muted fs-12">
                                                <strong>
                                                    <i class="bi bi-credit-card me-1"></i> Phương thức thanh toán:
                                                </strong>
                                                {{ optional($order->payment)->name ?? 'Chưa xác định' }}
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Cột phải: Tổng tiền & nút xem -->
                                    <div class="col-4 text-end">
                                        <div class="text-primary fw-bold fs-5">
                                            {{ number_format($order->total_price * 100, 0, ',', '.') }}₫
                                        </div>
                                        <a href="{{ route('don-hang.show', $order->id) }}"
                                            class="btn btn-sm btn-outline-primary mt-2 hover-btn">
                                            <i class="bi bi-eye"></i> Xem
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                            <hr class="my-3">
                            @endif
                            @empty
                            <p class="text-muted text-center">Không có đơn hàng nào để hiển thị.</p>
                            @endforelse
                        </div>
                        <!-- Nút xem tất cả -->
                        <div class="text-end mt-4">
                            <a href="{{ route('don-hang.index') }}" class="btn btn-primary btn-sm hover-btn">
                                Xem tất cả <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Dữ liệu cho biểu đồ
    const weeklyLabels = @json($weeklyLabels);
    const weeklyValues = @json($weeklyValues);
    const weeklyOrderData = @json($weeklyOrderData);

    const monthlyLabels = @json($comboLabels);
    const monthlyRevenueData = @json($comboRevenueData);
    const monthlyOrderData = @json($comboOrderData);

    const yearlyLabels = @json($yearlyLabels);
    const yearlyValues = @json($yearlyValues);
    const yearlyOrderData = @json($yearlyOrderData);

    // Dữ liệu mặc định (theo tháng)
    let currentLabels = monthlyLabels;
    let currentRevenueData = monthlyRevenueData;
    let currentOrderData = monthlyOrderData;

    // Cấu hình dữ liệu cho biểu đồ
    const comboData = {
        labels: currentLabels,
        datasets: [{
                label: 'Doanh Thu (VND)',
                data: currentRevenueData,
                type: 'bar',
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                yAxisID: 'y1',
            },
            {
                label: 'Số Đơn Hàng',
                data: currentOrderData,
                type: 'line',
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                fill: true,
                tension: 0.4,
                yAxisID: 'y2',
            }
        ]
    };

    const comboConfig = {
        type: 'bar',
        data: comboData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Doanh Thu (Cột) và Số Đơn Hàng (Đường)',
                    font: {
                        size: 18,
                        weight: 'bold'
                    },
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                },
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label === 'Doanh Thu (VND)') {
                                return `${label}: ${context.parsed.y.toLocaleString()} VND`;
                            }
                            return `${label}: ${context.parsed.y}`;
                        },
                        title: function(tooltipItems) {
                            const label = tooltipItems[0].label;
                            if (currentLabels === weeklyLabels) {
                                const dayIndex = weeklyLabels.indexOf(label);
                                const startOfLastWeek = new Date();
                                startOfLastWeek.setDate(startOfLastWeek.getDate() - 7 - startOfLastWeek.getDay() + dayIndex);
                                const day = startOfLastWeek.getDate().toString().padStart(2, '0');
                                const month = (startOfLastWeek.getMonth() + 1).toString().padStart(2, '0');
                                const year = startOfLastWeek.getFullYear();
                                return `${label} (${day}/${month}/${year})`;
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    type: 'category',
                    title: {
                        display: true,
                        text: 'Thời Gian',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        callback: function(value, index, values) {
                            const label = this.getLabelForValue(value);
                            if (currentLabels === monthlyLabels) {
                                const [year, month] = label.split('-');
                                return `Tháng ${parseInt(month)}/${year}`;
                            }
                            return label;
                        }
                    },
                    grid: {
                        display: false
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Doanh Thu (VND)',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value.toLocaleString() + ' VND';
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                },
                y2: {
                    type: 'linear',
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Số Đơn Hàng',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    };

    // Khởi tạo biểu đồ
    const comboChart = new Chart(document.getElementById('comboChart'), comboConfig);

    // Xử lý sự kiện khi chọn bộ lọc thời gian
    document.querySelectorAll('.dropdown-item[data-filter]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.getAttribute('data-filter');

            if (filter === 'week') {
                currentLabels = weeklyLabels;
                currentRevenueData = weeklyValues;
                currentOrderData = weeklyOrderData;
                document.getElementById('timeFilterDropdown').textContent = 'Theo Tuần (Tuần Trước)';
            } else if (filter === 'month') {
                currentLabels = monthlyLabels;
                currentRevenueData = monthlyRevenueData;
                currentOrderData = monthlyOrderData;
                document.getElementById('timeFilterDropdown').textContent = 'Theo Tháng';
            } else if (filter === 'year') {
                currentLabels = yearlyLabels;
                currentRevenueData = yearlyValues;
                currentOrderData = yearlyOrderData;
                document.getElementById('timeFilterDropdown').textContent = 'Theo Năm';
            }

            comboChart.data.labels = currentLabels;
            comboChart.data.datasets[0].data = currentRevenueData;
            comboChart.data.datasets[1].data = currentOrderData;
            comboChart.update();

            document.querySelectorAll('.dropdown-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Biểu đồ Trạng Thái Đơn Hàng
    const orderStatusChart = new Chart(document.getElementById('orderStatusChart'), {
        type: 'pie',
        data: {
            labels: @json($orderStatusLabels),
            datasets: [{
                label: 'Trạng Thái Đơn Hàng',
                data: @json($orderStatusValues),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Tỷ Lệ Trạng Thái Đơn Hàng',
                    font: {
                        size: 18,
                        weight: 'bold'
                    },
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                },
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });
</script>
@endsection