@extends('admin.layout')

@section('content')

<h1>Welcome back, {{ auth()->user()->name }}</h1>
<p style="margin-bottom:30px;">Role: <strong>Administrator</strong></p>

{{-- STAT CARDS --}}
<div class="stats-grid" style="display:grid; grid-template-columns: repeat(4, 1fr); gap:20px; margin-bottom:30px;">
    <div class="card" style="padding:20px; background:#f3f4f6; border-radius:8px; text-align:center;">
        👥 <strong>Total Users</strong>
        <h2>{{ $users }}</h2>
    </div>

    <div class="card" style="padding:20px; background:#f3f4f6; border-radius:8px; text-align:center;">
        🏠 <strong>Properties</strong>
        <h2>{{ $properties }}</h2>
    </div>

    <div class="card" style="padding:20px; background:#f3f4f6; border-radius:8px; text-align:center;">
        📄 <strong>Applications</strong>
        <h2>{{ $applications }}</h2>
    </div>

    <div class="card" style="padding:20px; background:#f3f4f6; border-radius:8px; text-align:center;">
        🛑 <strong>Pending</strong>
        <h2>{{ $pending }}</h2>
    </div>
</div>

{{-- BAR CHART --}}
<div class="card" style="padding:20px; background:#f3f4f6; border-radius:8px; margin-bottom:30px;">
    <h3>Property Status Overview</h3>
    <canvas id="propertyChart"></canvas>
</div>

{{-- PENDING APPROVALS --}}
<div class="card" style="padding:20px; background:#f3f4f6; border-radius:8px;">
    <h3>Pending Property Approvals</h3>

    <table style="width:100%; border-collapse: collapse;">
        <tr style="background:#e5e7eb;">
            <th style="padding:10px; border:1px solid #d1d5db;">Title</th>
            <th style="padding:10px; border:1px solid #d1d5db;">Owner</th>
            <th style="padding:10px; border:1px solid #d1d5db;">Action</th>
        </tr>

        @forelse($pendingProperties as $property)
            <tr>
                <td style="padding:10px; border:1px solid #d1d5db;">{{ $property->title }}</td>
                <td style="padding:10px; border:1px solid #d1d5db;">{{ $property->owner->name }}</td>
                <td style="padding:10px; border:1px solid #d1d5db;">
                    <form method="POST" action="{{ route('admin.property.approve', $property->id) }}">
                        @csrf
                        <button type="submit" style="padding:5px 10px; background:#4ade80; border:none; border-radius:4px; color:white;">Approve</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" style="padding:10px; border:1px solid #d1d5db; text-align:center;">No pending properties 🎉</td>
            </tr>
        @endforelse
    </table>
</div>

{{-- CHART SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('propertyChart'), {
    type: 'bar',
    data: {
        labels: ['Approved', 'Pending', 'Rejected'],
        datasets: [{
            label: 'Properties',
            data: [{{ $approved ?? 0 }}, {{ $pending ?? 0 }}, {{ $rejected ?? 0 }}],
            backgroundColor: ['#4ade80', '#facc15', '#f87171']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

@endsection
