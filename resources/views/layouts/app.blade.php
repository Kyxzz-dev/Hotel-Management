<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management System | {{ config('app.name', 'Cuti Pegawai') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts + Bootstrap + FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="{{ asset('css/panel.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Rubik', sans-serif;
            min-height: 100vh;
            /* Gradasi dibuat lebih elegan (Deep Maroon to Dark Charcoal) */
            background: linear-gradient(135deg, #2b1115 0%, #5c1824 50%, #1a0a0c 100%);
            background-attachment: fixed;
            background-size: cover;
            color: #f8f9fa;
            display: flex;
            flex-direction: column;
        }

        #app {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .navbar {
            background-color: rgba(15, 5, 7, 0.4);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .navbar-brand,
        .nav-link {
            color: #ffffff !important;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 20px;
            letter-spacing: 1px;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
        }

        /* Perbaikan Dropdown Menu agar teks terlihat */
        .dropdown-menu {
            background-color: #ffffff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .dropdown-item {
            color: #333333 !important;
            font-weight: 500;
            padding: 10px 20px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #7b1e2b !important;
            border-radius: 4px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            color: #333;
        }

        .btn-primary {
            background-color: #7b1e2b;
            border: none;
            font-weight: 500;
            padding: 8px 20px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4a0f18;
        }

        /* Teks footer dibuat jauh lebih jelas dan terang */
        footer {
            text-align: center;
            padding: 25px 20px;
            color: black;
            font-size: 15px;
            font-weight: 500;
            letter-spacing: 0.5px;
            opacity: 0.95;
            background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
        }
    </style>
</head>
<body>

<div id="app">
    <nav class="navbar navbar-expand-md shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fa fa-hotel me-2 text-warning"></i> Hotel Leave
            </a>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <!-- Login / Register Links can go here -->
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle py-2 px-3" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user-circle me-1"></i> {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out-alt me-2"></i> Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-5 container">
        @yield('content')
    </main>

    <footer>
        <i class="fa fa-leaf me-1 text-warning"></i> Leave Management System – Hotel {{ date('Y') }}
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>