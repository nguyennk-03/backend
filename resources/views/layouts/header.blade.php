<header class="header" data-header>
    <div class="container">

        <a href="{{ route('trang-chu') }}" class="logo">
            <img src="{{ asset('images/logo-sm1.png') }}" height="100" alt="Footcap logo">
        </a>

        <nav class="navbar" data-navbar>
            <button class="nav-close-btn" data-nav-close-btn aria-label="Close Menu">
                <ion-icon name="close-outline"></ion-icon>
            </button>

            <a href="{{ route('trang-chu') }}" class="logo">
                <img src="{{ asset('images/logo-sm1.png') }}" width="190" height="50" alt="Footcap logo">
            </a>

            <ul class="navbar-list">
                <li class="navbar-item">
                    <a href="{{ route('trang-chu') }}" class="navbar-link">Trang chủ</a>
                </li>
                <li class="navbar-item">
                    <a href="#" class="navbar-link">Giới thiệu</a>
                </li>
                <li class="navbar-item">
                    <a href="#" class="navbar-link">Sản phẩm</a>
                </li>
                <li class="navbar-item">
                    <a href="#" class="navbar-link">Cửa hàng</a>
                </li>
                <li class="navbar-item">
                    <a href="#" class="navbar-link">Blog</a>
                </li>
                <li class="navbar-item">
                    <a href="#" class="navbar-link">Liên hệ</a>
                </li>
            </ul>

            <ul class="nav-action-list">
                <li>
                    <button class="nav-action-btn">
                        <ion-icon name="search-outline" aria-hidden="true"></ion-icon>
                        <span class="nav-action-text">Search</span>
                    </button>
                </li>

                <li>
                    @if (Auth::check())
                            <button class="nav-action-btn"
                                onclick="event.preventDefault(); document.getElementById('dang-xuat-form').submit();">
                                <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
                                <span class="nav-action-text">{{ Auth::user()->name }} (Đăng xuất)</span>
                            </button>
                        <form id="dang-xuat-form" action="{{ route('dang-xuat') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                        <button class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('dang-xuat-form').submit();">
                            Đăng xuất
                        </button>
                    @else
                        <button class="nav-action-btn" onclick="window.location.href='/dang-nhap'">
                            <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Đăng nhập / Đăng ký</span>
                        </button>
                    @endif
                </li>

                <li>
                    <button class="nav-action-btn">
                        <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                        <span class="nav-action-text">Wishlist</span>
                        <data class="nav-action-badge" value="0" aria-hidden="true">0</data>
                    </button>
                </li>

                <li>
                    <button class="nav-action-btn">
                        <ion-icon name="bag-outline" aria-hidden="true"></ion-icon>
                        <data class="nav-action-text" value="318.00">Basket: <strong>$318.00</strong></data>
                        <data class="nav-action-badge" value="4" aria-hidden="true">0</data>
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</header>