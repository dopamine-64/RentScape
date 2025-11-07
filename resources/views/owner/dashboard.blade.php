@extends('layouts.dashboard')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #FFDEE9, #B5FFFC); /* Tenant dashboard colors */
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

    .top-nav .brand {
        font-weight: bold;
        font-size: 1.5rem;
        color: #FF6B6B;
    }

    .top-nav .nav-links {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .top-nav .nav-links a {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s;
    }

    .top-nav .nav-links a:hover {
        color: #FF6B6B;
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
    <div class="brand">Rentscape</div>
    <div class="nav-links">
        <a href="#">Dashboard</a>
        <a href="#">My Properties</a>
        <a href="#">Applicants</a>
        <a href="#">Messages</a>
        <a href="#">Settings</a>
        <a href="#" class="logout-btn" id="logoutBtn">Logout</a>

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
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = "{{ route('login') }}"; // Redirect to login
                    }
                });
            });
        </script>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>
        <p>Role: <strong>{{ ucfirst(Auth::user()->role) }}</strong></p>
    </div>

    <div class="card-container">
        <div class="card">
            <i class="ri-building-2-line"></i>
            <h3>Manage Properties</h3>
            <p>Post, edit, or remove your property listings.</p>
        </div>
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
@endsection