<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rentscape</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        /* Body & Fonts */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFDEE9, #B5FFFC);
            min-height: 100vh;
            color: #333;
        }

        /* Top Navbar */
        nav.navbar {
            width: 100%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        nav .brand {
            font-weight: bold;
            font-size: 1.6rem;
            color: #FF6B6B;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        nav .brand img {
            height: 42px;
            width: 42px;
            object-fit: contain;
        }

        nav .nav-links {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        nav .nav-links a,
        nav .nav-links form button {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 6px;
        }

        nav .nav-links a:hover,
        nav .nav-links form button:hover {
            color: #FF6B6B;
            background: rgba(255, 107, 107, 0.2);
        }

        nav .logout-btn {
            background: #FF4D4D;
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: 0.3s;
        }

        nav .logout-btn:hover {
            background: #FF1A1A;
        }

        /* Auth Card */
        .auth-card {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2.5rem;
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            color: #333;
        }

        .auth-card h3 {
            font-weight: bold;
            color: #FF6B6B;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #333;
        }

        .form-control:focus {
            box-shadow: 0 0 8px rgba(255,107,107,0.5);
        }

        .btn-modern {
            background: #FF6B6B;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            padding: 0.6rem 1.4rem;
            transition: all 0.3s ease;
            color: white;
        }

        .btn-modern:hover {
            background: #FF4D4D;
            transform: scale(1.05);
        }

        .text-light a {
            color: #FF6B6B;
            text-decoration: none;
            font-weight: 600;
        }

        .text-light a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width:768px) {
            nav .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>

    @yield('styles')
</head>
<body>

{{-- Navbar --}}
<nav class="navbar">
    <div class="brand">
        <img src="/images/logo.png" alt="Logo"> RentScape
    </div>
    <div class="nav-links">
        @guest
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @else
            <a href="{{ route('home') }}">Dashboard</a>
            <a href="#" class="logout-btn"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
               Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        @endguest
    </div>
</nav>

<main class="container py-5">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
