<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | RentScape</title>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Basic styling (you can move to CSS later) --}}
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #fde2e4, #dbeafe);
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 240px;
            background: #ffffff;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.08);
        }

        .admin-sidebar .logo {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .admin-sidebar ul {
            list-style: none;
            padding: 0;
        }

        .admin-sidebar ul li {
            margin-bottom: 15px;
        }

        .admin-sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .admin-main {
            flex: 1;
            padding: 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #ffffff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        button {
            background: #22c55e;
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="admin-container">

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar">
        <div class="logo">🏠 RentScape</div>

        <ul>
            <li><a href="{{ route('admin.dashboard') }}">📊 Dashboard</a></li>
            <li><a href="#">👥 Users</a></li>
            <li><a href="#">🏠 Properties</a></li>
            <li><a href="#">📄 Applications</a></li>
            <li><a href="#">🛑 Pending Approvals</a></li>
            <li><a href="#">📈 Reports</a></li>
            <li><a href="#">💬 Chat Moderation</a></li>
            <li><a href="#">⚙️ Settings</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button style="width:100%;background:#ef4444">Logout</button>
                </form>
            </li>
        </ul>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="admin-main">
        @yield('content')
    </main>

</div>

</body>
</html>
