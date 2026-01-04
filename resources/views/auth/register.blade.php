@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #FFDEE9, #B5FFFC);
        height: 100vh;
        overflow: hidden;
        color: #333;
        margin: 0;
    }

    /* ✅ Center the card (no scroll) */
    .container-center {
        height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* ✅ Register Card */
    .auth-card {
        background: rgba(40, 40, 40, 0.95);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        color: #fff;
        padding: 2.5rem;
        width: 100%;
        max-width: 480px;
        transition: 0.3s ease-in-out;
    }

    .auth-card h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #FF6B6B;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #fff;
        margin-bottom: 6px;
    }

    /* ✅ Input Wrapper */
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

    .form-control {
        padding-left: 45px;
        background: rgba(255, 255, 255, 0.1) !important;
        border: none;
        border-radius: 12px;
        color: #fff !important;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background: rgba(60, 60, 60, 0.8) !important;
        box-shadow: 0 0 7px #FF6B6B;
    }

    /* ✅ Dropdown Styling (same height as inputs) */
    .select-wrapper {
        position: relative;
    }

    .select-wrapper select {
        padding-left: 16px;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 12px;
        color: #fff;
        width: 100%;
        height: 38px; /* Same as input height */
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        font-size: 1rem;
        cursor: pointer;
    }

    .select-wrapper::after {
        content: "▼";
        position: absolute;
        top: 50%;
        right: 1rem;
        transform: translateY(-50%);
        pointer-events: none;
        color: rgba(255,255,255,0.8);
        font-size: 0.7rem;
    }

    .select-wrapper select:focus {
        outline: none;
        box-shadow: 0 0 7px #FF6B6B;
    }

    /* ✅ Prevent Autofill White Background */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0px 1000px rgba(40, 40, 40, 0.95) inset !important;
        -webkit-text-fill-color: #fff !important;
        transition: background-color 5000s ease-in-out 0s;
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
        margin-top: 1rem;
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

<!-- ✅ Centered Wrapper -->
<div class="container-center">
    <div class="auth-card">

        <h3>Create Your Account</h3>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Name</label>
                <div class="input-wrapper">
                    <i class="bi bi-person"></i>
                    <input id="name" type="text" class="form-control" name="name" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="bi bi-envelope"></i>
                    <input id="email" type="email" class="form-control" name="email" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-key"></i>
                    <input id="password" type="password" class="form-control" name="password" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-shield-lock"></i>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Register As</label>
                <div class="select-wrapper">
                    <select name="role" id="role" required>
                        <option value="" hidden>Select a role</option>
                        <option value="owner">Owner</option>
                        <option value="tenant">Tenant</option>
                        <option value="both">Both</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-modern w-100">Register</button>
        </form>

        <p class="mt-3 text-center text-light">
            Already have an account? 
            <a href="{{ route('login') }}">Login here</a>
        </p>
    </div>
</div>
@endsection