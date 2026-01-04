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

    .brand { display: flex; align-items: center; gap: 7px; font-weight: bold; font-size: 1.6rem; color: #FF6B6B; }
    .brand img { height: 42px; width: 42px; }
    .nav-links { display: flex; align-items: center; gap: 15px; }
    .nav-links a, .nav-links form button {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 6px;
        background: none;
        border: none;
        cursor: pointer;
    }
    .nav-links a:hover, .nav-links form button:hover { background: rgba(255,107,107,0.2); color: #FF6B6B; }
    .logout-btn { background: #FF4D4D; color: white; padding: 8px 15px; border-radius: 8px; font-weight: bold; }
    .logout-btn:hover { background: #FF1A1A; }

    .main-content { padding: 40px; max-width: 1200px; margin: auto; }
    .header { text-align: center; margin-bottom: 40px; }
    .header h1 { color: #FF6B6B; }
    .applications-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }
    .application-card {
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        transition: 0.4s;
    }
    .application-card:hover { transform: translateY(-5px) scale(1.02); background: rgba(255,107,107,0.25); color: white; }
    .application-card img { width: 100%; border-radius: 12px; margin-bottom: 15px; }
    .badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        color: white;
    }
    .badge.pending { background-color: #FF9F43; }
    .badge.approved { background-color: #2ED573; }
    .badge.rejected { background-color: #FF6B6B; }
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
        <a href="#">Messages</a>

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
        <h1>My Applications</h1>
        <p>Track status of properties you've applied for.</p>
    </div>

    <div class="applications-container">
        @forelse($applications as $app)
            <div class="application-card">
                @if($app->property->images->first())
                    <img src="{{ asset('storage/' . $app->property->images->first()->path) }}" alt="Property Image">
                @endif
                <h3>{{ $app->property->title }}</h3>
                <p>{{ $app->property->city }}, {{ $app->property->address }}</p>
                <p>Price: {{ $app->property->price ?? 'N/A' }} BDT</p>
                <span class="badge {{ strtolower($app->status) }}">{{ ucfirst($app->status) }}</span>
            </div>
        @empty
            <p>No applications yet.</p>
        @endforelse
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">

<script>
document.getElementById('logoutBtn').addEventListener('click', function(e){
    e.preventDefault();
    fetch("{{ route('logout') }}", { method: "POST", headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" } })
        .then(() => window.location.href = "{{ route('login') }}");
});
</script>
@endsection
