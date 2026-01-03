<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Rentscape</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

<div class="d-flex admin-wrapper">

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar">
        <h4 class="logo">Rentscape</h4>

        <ul class="nav flex-column">
            <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="#"><i class="bi bi-people"></i> Users</a></li>
            <li><a href="#"><i class="bi bi-house-check"></i> Properties</a></li>
            <li><a href="#"><i class="bi bi-file-earmark-text"></i> Applications</a></li>
            <li><a href="#"><i class="bi bi-flag"></i> Reports</a></li>
            <li><a href="#"><i class="bi bi-chat-dots"></i> Chat Moderation</a></li>
            <li><a href="#"><i class="bi bi-gear"></i> Settings</a></li>

            <li class="mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger w-100">Logout</button>
                </form>
            </li>
        </ul>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="admin-content flex-fill">
        <div class="admin-topbar">
            <span>Welcome, {{ auth()->user()->name }}</span>
        </div>

        <div class="container-fluid mt-4">
            @yield('content')
        </div>
    </main>

</div>

</body>
</html>
