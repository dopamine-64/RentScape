@extends('layouts.app')

@section('content')
<style>
    body {
        background: url('/images/bg.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    .overlay {
        background: rgba(0, 0, 0, 0.6);
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        backdrop-filter: blur(6px);
        z-index: -1;
    }

    .card {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        color: white;
    }

    .card-header {
        background: transparent;
        border-bottom: none;
        text-align: center;
        font-size: 1.8rem;
        font-weight: bold;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: white;
    }

    .form-control:focus {
        box-shadow: 0 0 8px rgba(255,255,255,0.6);
    }

    .btn-primary {
        background: #00b4d8;
        border: none;
        border-radius: 30px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background: #0096c7;
        transform: scale(1.05);
    }

    .text-light a {
        color: #00b4d8;
        text-decoration: none;
        transition: 0.3s;
    }

    .text-light a:hover {
        text-decoration: underline;
    }
</style>

<div class="overlay"></div>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="col-md-5">
        <div class="card p-4">
            <div class="card-header">Welcome Back to Rentscape</div>

            <div class="card-body">

                {{-- ✅ Success message after registration --}}
                @if(session('success'))
                    <div class="alert alert-success text-center" style="background: rgba(0, 255, 0, 0.2); color: #b6ffb6; border: none; border-radius: 10px;">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label>Email Address</label>
                        <input id="email" type="email" class="form-control" name="email" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>

                    <div class="mb-3 form-check text-white">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <p class="mt-3 text-center text-light">
                    Don’t have an account?
                    <a href="{{ route('register') }}">Register here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
