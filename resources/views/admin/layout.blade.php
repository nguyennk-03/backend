<!-- resources/views/layouts/admin.layout.blade.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Admin| @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Giao diện quản trị đầy đủ tính năng để xây dựng CRM, CMS, v.v." name="description">
    <meta content="Coderthemes" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <!-- CSS Libraries -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/apexcharts.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/line-awesome@1.3.0/dist/line-awesome/css/line-awesome.min.css">
</head>

<body>
    <div class="wrapper">
        <!-- Topbar Start -->
        <div class="navbar-custom d-flex align-items-center justify-content-between">
            <!-- Phần bên trái - Logo -->
            <div class="d-flex align-items-center">
                <a href="{{ url('/admin/bang-dieu-khien') }}" class="d-flex align-items-center">
                    <img src="{{ asset('images/logo-sm1.png') }}" alt="Logo" height="80" class="logo-img">
                </a>
            </div>

            <!-- Phần bên phải - Menu người dùng -->
            <ul class="navbar-nav d-flex align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('images/users/user-1.jpg') }}" alt="user-image" class="rounded-circle me-2"
                            width="32" height="32">
                        <span class="pro-user-name text-dark">
                            {{ Auth::guard('web')->user()->name ?? 'Admin' }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown">
                        <li>
                            <h6 class="dropdown-header text-white bg-primary mb-0">Chào Mừng!</h6>
                        </li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Tài Khoản Của Tôi</a>
                        </li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Cài Đặt</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-lock me-2"></i> Khóa Màn Hình</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('dang-xuat') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Đăng Xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>



        <!-- Sidebar Start -->
        <div id="sidebar-menu" class="active">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="active">
                    <a href="{{ route('admin') }}">
                        <i class="la la-dashboard"></i> <span>Trang quản lý</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('san-pham.index') }}" class="nav-link" data-bs-toggle="collapse"
                        data-bs-target="#product-management" aria-expanded="false" aria-controls="product-management">
                        <i class="la la-cube"></i> <span>Quản lý sản phẩm</span>
                        <span class="float-end"><i class="la la-angle-right"></i></span>
                    </a>
                    <ul class="nav-second-level collapse" id="product-management">
                        <li class="nav-item">
                            <a href="{{ route('san-pham.index') }}" class="nav-link"><i class="la la-list"></i> Danh
                                sách sản phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('mau-sac.index')  }}" class="nav-link"><i class="la la-palette"></i> Quản lý theo màu</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('kich-thuoc.index')  }}" class="nav-link"><i class="la la-ruler"></i> Quản lý theo size</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('don-hang.index') }}">
                        <i class="la la-shopping-cart"></i> <span>Quản lý đơn hàng</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('binh-luan.index') }}">
                        <i class="la la-comments"></i> <span>Quản lý bình luận</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('danh-gia.index') }}">
                        <i class="la la-award"></i> <span>Quản lý đánh giá</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('nguoi-dung.index') }}">
                        <i class="la la-users"></i> <span>Quản lý người dùng</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('danh-muc.index') }}">
                        <i class="la la-tags"></i> <span>Quản lý danh mục</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('thuong-hieu.index') }}">
                        <i class="la la-bookmark"></i> <span>Quản lý thương hiệu</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('giam-gia.index') }}">
                        <i class="la la-percent"></i> <span>Quản lý giảm giá</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('bai-viet.index') }}">
                        <i class="la la-newspaper-o"></i> <span>Quản lý bài viết</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content-page">
            @yield('content')
        </div>
        <!-- Content End -->

        <div class="rightbar-overlay"></div>
    </div>
    <!-- External Scripts -->
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>