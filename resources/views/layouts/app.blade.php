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
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFDEE9, #B5FFFC);
            min-height: 100vh;
            color: #333;
        }

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

        nav .nav-links a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
            padding: 8px 12px;
            border-radius: 6px;
        }

        nav .nav-links a:hover {
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

<nav class="navbar">
    <div class="brand">
        <img src="/images/logo.png" alt="Logo"> RentScape
    </div>

    <div class="nav-links">

        {{-- Guest --}}
        @guest
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endguest

        {{-- Logged in --}}
        @auth
            @if(session('active_role') === 'owner')
                <a href="{{ route('owner.dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('tenant.dashboard') }}">Dashboard</a>
            @endif

            @if(auth()->user()->role === 'owner' || auth()->user()->role === 'both')
                <a href="{{ route('property.create') }}">
                    <i class="ri-add-line me-1"></i> Post Property
                </a>
                <a href="{{ route('owner.applications.index') }}">
                    <i class="ri-group-line me-1"></i> View Applicants
                </a>
            @endif

            <a href="#" class="logout-btn" id="logoutBtn">Logout</a>
        @endauth

    </div>
</nav>

<main class="container py-5">
    @yield('content')
</main>

<script>
document.getElementById('logoutBtn')?.addEventListener('click', function(e){
    e.preventDefault();
    fetch("{{ route('logout') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json",
            "Content-Type": "application/json"
        }
    }).then(response => {
        if(response.ok){
            window.location.href = "{{ route('login') }}";
        }
    });
});
</script>

@yield('scripts')
</body>
</html>
