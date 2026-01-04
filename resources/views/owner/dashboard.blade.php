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

    /* Top Navbar */
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
        object-fit: contain;
        vertical-align: middle;
    }

    .top-nav .nav-links {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .top-nav .nav-links a, 
    .top-nav .nav-links form button {
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

    .top-nav .nav-links a:hover, 
    .top-nav .nav-links form button:hover {
        color: #FF6B6B;
        background: rgba(255, 107, 107, 0.2);
    }

    .top-nav .logout-btn {
        background: #FF4D4D;
        padding: 8px 15px;
        border-radius: 8px;
        text-decoration: none;
        color: white;
        font-weight: bold;
        transition: 0.3s;
    }

    .top-nav .logout-btn:hover {
        background: #FF1A1A;
    }

    .main-content {
        padding: 40px;
        max-width: 1200px;
        margin: auto;
    }

    .header {
        margin-bottom: 40px;
        text-align: center;
    }

    .header h1 {
        font-size: 32px;
        color: #FF6B6B;
        margin-bottom: 10px;
    }

    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px;
    }

    .card {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        transition: 0.4s ease-in-out;
        cursor: pointer;
    }

    .card:hover {
        transform: translateY(-8px) scale(1.03);
        background: rgba(255, 107, 107, 0.25);
        color: white;
    }

    .card i {
        font-size: 40px;
        color: #FF6B6B;
        margin-bottom: 15px;
    }

    .card h3 {
        margin-top: 10px;
        font-weight: 600;
    }

    .card p {
        font-size: 0.95rem;
        margin-top: 5px;
        opacity: 0.9;
    }

    .card-post-property {
        min-height: 280px; 
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .card-container {
            grid-template-columns: 1fr;
        }
        .top-nav {
            flex-direction: column;
            gap: 10px;
        }
        .top-nav .nav-links {
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
    }
</style>

<div class="top-nav">
    <div class="brand">
        <img src="/images/logo.png" alt="Logo">
        RentScape
    </div>
    <div class="nav-links">
        <a href="{{ route('owner.dashboard') }}">Dashboard</a>
        <a href="{{ route('property.create') }}">Post Property</a>
        <a href="{{ route('properties.index') }}">Manage Properties</a> 
        <a href="#">Applicants</a>
        <a href="#">Messages</a>
        <a href="#">Settings</a>

        @if(Auth::user()->role === 'both')
        <form action="{{ route('switch.role') }}" method="POST">
            @csrf
            @if(session('active_role') === 'owner')
                <input type="hidden" name="role" value="tenant">
                <button type="submit" class="btn-switch">Switch to Tenant</button>
            @else
                <input type="hidden" name="role" value="owner">
                <button type="submit" class="btn-switch">Switch to Owner</button>
            @endif
        </form>
        @endif

        <a href="#" class="logout-btn" id="logoutBtn">Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>
        <p>Role: <strong>{{ ucfirst(session('active_role', Auth::user()->role)) }}</strong></p>
    </div>

    <div class="card-container">
        <a href="{{ route('property.create') }}" style="text-decoration: none;">
            <div class="card card-post-property">
                <i class="ri-home-smile-line"></i>
                <h3>Post Property</h3>
                <p>Add a new property for rent with details and images.</p>
            </div>
        </a>

        <a href="{{ route('properties.index') }}" style="text-decoration: none;">
            <div class="card">
                <i class="ri-building-2-line"></i>
                <h3>Manage Properties</h3>
                <p>Browse and see all your listed properties.</p>
            </div>
        </a>

        <div class="card">
            <i class="ri-user-settings-line"></i>
            <h3>Applicants</h3>
            <p>View and approve tenants who applied for your listings.</p>
        </div>

        <div class="card">
            <i class="ri-chat-4-line"></i>
            <h3>Chats</h3>
            <p>Communicate directly with your tenants.</p>
        </div>
    </div>
</div>

<!-- Remix Icon CDN -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">

<script>
document.getElementById('logoutBtn').addEventListener('click', function(e){
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
@endsection