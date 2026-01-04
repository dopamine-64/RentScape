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
        object-fit: contain;
    }

    .nav-links {
        display: flex;
        gap: 15px;
    }

    .nav-links a {
        text-decoration: none;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 6px;
        color: #333;
    }

    .nav-links a:hover {
        background: rgba(255, 107, 107, 0.2);
        color: #FF6B6B;
    }

    .main-content {
        padding: 40px;
        max-width: 1000px;
        margin: auto;
    }

    .card {
        background: rgba(255, 255, 255, 0.25);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .status {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        display: inline-block;
    }

    .pending {
        background: #FFD166;
        color: #333;
    }

    .resolved {
        background: #06D6A0;
        color: white;
    }

    textarea, input {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: none;
        outline: none;
        margin-bottom: 15px;
    }

    button {
        background: #FF6B6B;
        border: none;
        color: white;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
    }

    button:hover {
        background: #FF4D4D;
    }
</style>

<div class="top-nav">
    <div class="brand">
        <img src="/images/logo.png" alt="Logo">
        RentScape
    </div>

    <div class="nav-links">
        <a href="{{ route('tenant.dashboard') }}">Dashboard</a>
        <a href="{{ route('properties.index') }}">Properties</a>
        <a href="{{ route('wishlist.index') }}">Wishlist ❤️</a>
        <a href="{{ route('tenant.applications') }}">Applications</a>
        <a href="{{ route('tenant.complaints') }}">Complaints</a>
    </div>
</div>

<div class="main-content">

    <h2 style="color:#FF6B6B; margin-bottom:20px;">📩 Submit a Complaint</h2>

    @if(session('success'))
        <div class="card" style="background:#d4f8e8;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('tenant.complaints.store') }}">
            @csrf

            <input type="text" name="subject" placeholder="Subject" required>
            <textarea name="message" rows="5" placeholder="Describe your issue..." required></textarea>

            <button type="submit">Submit Complaint</button>
        </form>
    </div>

    <h2 style="color:#FF6B6B; margin-bottom:20px;">📋 My Complaints</h2>

    @forelse($complaints as $complaint)
        <div class="card">
            <h3>{{ $complaint->subject }}</h3>
            <p>{{ $complaint->message }}</p>

            <span class="status {{ $complaint->status }}">
                {{ ucfirst($complaint->status) }}
            </span>

            <p style="font-size:12px; margin-top:10px;">
                Submitted on {{ $complaint->created_at->format('d M Y') }}
            </p>
        </div>
    @empty
        <div class="card">
            <p>No complaints submitted yet.</p>
        </div>
    @endforelse

</div>

<link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">
@endsection
