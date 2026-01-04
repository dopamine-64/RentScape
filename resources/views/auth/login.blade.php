@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #FFDEE9, #B5FFFC);
        min-height: 100vh;
        overflow: hidden;
        color: #333;
        margin: 0;
    }

    .auth-card {
        background: rgba(40, 40, 40, 0.95);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        color: #fff;
        padding: 2.5rem;
        transition: 0.3s ease-in-out;
        max-width: 450px;
        width: 100%;
    }

    .auth-card h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #FF6B6B;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-wrapper i {
        position: absolute;
        left: 15px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.2rem;
    }

    .input-wrapper .form-control {
        padding-left: 45px;
        background: rgba(255, 255, 255, 0.1) !important;
        border: none;
        border-radius: 12px;
        color: #fff !important;
        transition: all 0.3s ease;
    }

    .input-wrapper .form-control:focus {
        background: rgba(60, 60, 60, 0.8) !important;
        box-shadow: 0 0 7px #FF6B6B;
    }

    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0px 1000px rgba(40, 40, 40, 0.95) inset !important;
        -webkit-text-fill-color: #fff !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .form-label {
        font-weight: 500;
        color: #fff;
        margin-bottom: 6px;
    }

    .form-check-label {
        color: #fff;
    }

    .btn-modern {
        background: #FF6B6B;
        border: none;
        border-radius: 30px;
        font-weight: 600;
        padding: 0.7rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        color: #fff;
    }

    .btn-modern:hover {
        background: #FF4D4D;
        transform: translateY(-2px);
    }

    .text-light a {
        color: #FF6B6B;
        text-decoration: none;
        transition: 0.3s;
    }

    .text-light a:hover {
        text-decoration: underline;
        color: #FF4D4D;
    }

    @media (max-width: 576px) {
        .auth-card {
            padding: 2rem 1.5rem;
        }
    }
</style>

<!-- ✅ Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- ✅ Centered Login Card -->
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="auth-card">
        <h3>Welcome to Rentscape</h3>

        <!-- ✅ Show success or error messages -->
        @if(session('success'))
            <div class="alert text-center mt-2 mb-3" style="background: rgba(0, 255, 0, 0.2); color: #b6ffb6; border: none; border-radius: 10px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert text-center mt-2 mb-3" style="background: rgba(255, 0, 0, 0.2); color: #ffb6b6; border: none; border-radius: 10px;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert text-center mt-2 mb-3" style="background: rgba(255, 0, 0, 0.2); color: #ffb6b6; border: none; border-radius: 10px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="bi bi-envelope"></i>
                    <input id="email" type="email" class="form-control" name="email" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-key"></i>
                    <input id="password" type="password" class="form-control" name="password" required>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>

            <button type="submit" class="btn-modern w-100">Login</button>
        </form>

        <p class="mt-3 text-center text-light">
            Don't have an account?
            <a href="{{ route('register') }}">Register here</a>
        </p>
    </div>
</div>
@endsection