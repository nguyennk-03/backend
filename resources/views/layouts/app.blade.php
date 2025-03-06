<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StepViet | Home</title>

    <link rel="shortcut icon" href="{{asset('favicon.ico')  }}" type="images/favicon.ico">

    <link rel="stylesheet" href="{{asset('css/style1.css')  }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <link rel="preload" href="{{asset('images/hero-banner.png')  }}" as="image">

</head>

<body id="top">

    <header class="header" data-header>
        <div class="container">

            <div class="overlay" data-overlay></div>

            <a href="#" class="logo">
                <img src="{{asset('images/logo-sm1.png')  }}" height="100" alt="Footcap logo">
            </a>

            <button class="nav-open-btn" data-nav-open-btn aria-label="Open Menu">
                <ion-icon name="menu-outline"></ion-icon>
            </button>

            <nav class="navbar" data-navbar>

                <button class="nav-close-btn" data-nav-close-btn aria-label="Close Menu">
                    <ion-icon name="close-outline"></ion-icon>
                </button>

                <a href="#" class="logo">
                    <img src="{{asset('images/logo-sm1.png')  }}" width="190" height="50" alt="Footcap logo">
                </a>

                <ul class="navbar-list">

                    <li class="navbar-item">
                        <a href="#" class="navbar-link">Home</a>
                    </li>

                    <li class="navbar-item">
                        <a href="#" class="navbar-link">About</a>
                    </li>

                    <li class="navbar-item">
                        <a href="#" class="navbar-link">Products</a>
                    </li>

                    <li class="navbar-item">
                        <a href="#" class="navbar-link">Shop</a>
                    </li>

                    <li class="navbar-item">
                        <a href="#" class="navbar-link">Blog</a>
                    </li>

                    <li class="navbar-item">
                        <a href="#" class="navbar-link">Contact</a>
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
                        <button class="nav-action-btn" onclick="window.location.href='{{ route('login') }}'">
                            <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Login / Register</span>
                        </button>
                    </li>

                    <li>
                        <button class="nav-action-btn">
                            <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Wishlist</span>
                            <data class="nav-action-badge" value="5" aria-hidden="true">5</data>
                        </button>
                    </li>

                    <li>
                        <button class="nav-action-btn">
                            <ion-icon name="bag-outline" aria-hidden="true"></ion-icon>
                            <data class="nav-action-text" value="318.00">Basket: <strong>$318.00</strong></data>
                            <data class="nav-action-badge" value="4" aria-hidden="true">4</data>
                        </button>
                    </li>
                </ul>


            </nav>

        </div>
    </header>

    <main class="container my-5">
        @yield('content')
    </main>

    <footer class="footer">

        <div class="footer-top section">
            <div class="container">

                <div class="footer-brand">

                    <a href="#" class="logo">
                        <img src="{{asset('images/logo.svg')  }}" width="160" height="50" alt="Footcap logo">
                    </a>

                    <ul class="social-list">

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-facebook"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-twitter"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-pinterest"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-linkedin"></ion-icon>
                            </a>
                        </li>

                    </ul>

                </div>

                <div class="footer-link-box">

                    <ul class="footer-list">

                        <li>
                            <p class="footer-list-title">Contact Us</p>
                        </li>

                        <li>
                            <address class="footer-link">
                                <ion-icon name="location"></ion-icon>

                                <span class="footer-link-text">
                                    2751 S Parker Rd, Aurora, CO 80014, United States
                                </span>
                            </address>
                        </li>

                        <li>
                            <a href="tel:+557343673257" class="footer-link">
                                <ion-icon name="call"></ion-icon>

                                <span class="footer-link-text">+557343673257</span>
                            </a>
                        </li>

                        <li>
                            <a href="mailto:footcap@help.com" class="footer-link">
                                <ion-icon name="mail"></ion-icon>

                                <span class="footer-link-text">footcap@help.com</span>
                            </a>
                        </li>

                    </ul>

                    <ul class="footer-list">

                        <li>
                            <p class="footer-list-title">My Account</p>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>

                                <span class="footer-link-text">My Account</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>

                                <span class="footer-link-text">View Cart</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>

                                <span class="footer-link-text">Wishlist</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>

                                <span class="footer-link-text">Compare</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>

                                <span class="footer-link-text">New Products</span>
                            </a>
                        </li>

                    </ul>

                    <div class="footer-list">

                        <p class="footer-list-title">Opening Time</p>

                        <table class="footer-table">
                            <tbody>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Mon - Tue:</th>

                                    <td class="table-data">8AM - 10PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Wed:</th>

                                    <td class="table-data">8AM - 7PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Fri:</th>

                                    <td class="table-data">7AM - 12PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Sat:</th>

                                    <td class="table-data">9AM - 8PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Sun:</th>

                                    <td class="table-data">Closed</td>
                                </tr>

                            </tbody>
                        </table>

                    </div>

                    <div class="footer-list">

                        <p class="footer-list-title">Newsletter</p>

                        <p class="newsletter-text">
                            Authoritatively morph 24/7 potentialities with error-free partnerships.
                        </p>

                        <form action="" class="newsletter-form">
                            <input type="email" name="email" required placeholder="Email Address"
                                class="newsletter-input">

                            <button type="submit" class="btn btn-primary">Subscribe</button>
                        </form>

                    </div>

                </div>

            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">

                <p class="copyright">
                    &copy; 2022 <a href="#" class="copyright-link">codewithsadee</a>. All Rights Reserved
                </p>

            </div>
        </div>

    </footer>

    <a href="#top" class="go-top-btn" data-go-top>
        <ion-icon name="arrow-up-outline"></ion-icon>
    </a>

    <script src="{{asset('js/script1.js')  }}"></script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Bootstrap 5 JS (Popper.js included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>