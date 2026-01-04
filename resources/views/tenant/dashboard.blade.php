@extends('layouts.dashboard')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #FFDEE9, #B5FFFC);
        color: #333;
        min-height: 100vh;
    }

    .top-nav {
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

    .brand {
        display: flex;
        align-items: center;
        gap: 7px;
        font-weight: bold;
        font-size: 1.6rem;
        color: #FF6B6B;
    }

    .brand img {
        height: 42px;
        width: 42px;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .nav-links a,
    .nav-links form button {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 6px;
        background: none;
        border: none;
        cursor: pointer;
    }

    .nav-links a:hover,
    .nav-links form button:hover {
        background: rgba(255,107,107,0.2);
        color: #FF6B6B;
    }

    .logout-btn {
        background: #FF4D4D;
        color: white;
        padding: 8px 15px;
        border-radius: 8px;
        font-weight: bold;
    }

    .logout-btn:hover {
        background: #FF1A1A;
    }

    .main-content {
        padding: 40px;
        max-width: 1200px;
        margin: auto;
    }

    .header {
        text-align: center;
        margin-bottom: 40px;
    }

    .header h1 {
        color: #FF6B6B;
    }

    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px;
    }

    .card {
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        text-decoration: none;
        color: inherit;
        transition: 0.4s;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }

    .card:hover {
        transform: translateY(-8px) scale(1.03);
        background: rgba(255,107,107,0.25);
        color: white;
    }

    .card i {
        font-size: 40px;
        color: #FF6B6B;
        margin-bottom: 15px;
    }
</style>

<div class="top-nav">
    <div class="brand">
        <img src="/images/logo.png">
        RentScape
    </div>

    <div class="nav-links">
        <a href="{{ route('tenant.dashboard') }}">Dashboard</a>
        <a href="{{ route('properties.index') }}">View Properties</a>
        <a href="{{ route('wishlist.index') }}">Wishlist ❤️</a>
        <a href="{{ route('tenant.applications') }}">My Applications</a>

        <a href="{{ route('tenant.complaints') }}">Complaints</a>


        @if(Auth::user()->role === 'both')
            <form action="{{ route('switch.role') }}" method="POST">
                @csrf
                <button type="submit">Switch Role</button>
            </form>
        @endif

        <a href="#" class="logout-btn" id="logoutBtn">Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>
        <p>Role: <strong>{{ ucfirst(session('active_role')) }}</strong></p>
    </div>

    <div class="card-container">

        <a href="{{ route('properties.index') }}" class="card">
            <i class="ri-building-2-line"></i>
            <h3>View Properties</h3>
            <p>Explore available rentals.</p>
        </a>

        <a href="{{ route('wishlist.index') }}" class="card">
            <i class="ri-heart-3-line"></i>
            <h3>Wishlist</h3>
            <p>Saved properties for later viewing.</p>
        </a>

        <!-- ✅ FIXED -->
        <a href="{{ route('tenant.applications') }}" class="card">
            <i class="ri-file-list-3-line"></i>
            <h3>My Applications</h3>
            <p>Track application status.</p>
        </a>

        <a href="{{ route('tenant.complaints') }}" class="card">
            <i class="ri-message-3-line"></i>
            <h3>Complaints</h3>
            <p>Submit issues or contact support.</p>
        </a>


    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">

<script>
document.getElementById('logoutBtn').addEventListener('click', function(e){
    e.preventDefault();
    fetch("{{ route('logout') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
    }).then(() => window.location.href = "{{ route('login') }}");
});
</script>
@endsection
